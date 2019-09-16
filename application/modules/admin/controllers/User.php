<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');

        // check admin groups or not
        $group = 'admin';
        if (!$this->ion_auth->in_group($group)) {
            $this->session->set_flashdata('message', 'You must be an administrator to view the users page.');
            redirect('admin/dashboard/access_denied');
        }
    }

    public function index()
    {
        $this->data['title'] = 'Manage users';
        $this->data['breadcrumbs'] = 'Manage users';
        $this->load->view('admin/user/manage', $this->data);
    }

    // get all records
    public function get_all()
    {
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {
            $this->data['all'] = $this->user_model->get_all_users();
            $view = $this->load->view('admin/user/all', $this->data, true);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    // insert form
    public function create_form()
    {

        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $view = $this->load->view('admin/user/add', $this->data, true);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    // insert records
    public function create()
    {

        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $email = $this->input->post('email');
                $group_id = array($this->input->post('group_id'));
                $file_path = "assets/images/user/1482829335.png";
                $uploadOk = 1;

                //set validations
                $this->form_validation->set_rules('username', 'User Name', 'trim|required|callback_users_name_check');
                $this->form_validation->set_rules('password', 'Password');
                $this->form_validation->set_rules('email', 'User Email',
                    'trim|required|callback_email_address_check|is_unique[users.email]',
                    array(
                        'is_unique' => 'This User Email already exists.'
                    )
                );

                if ($this->form_validation->run() == false) {
                    $errors = array();
                    foreach ($this->input->post() as $key => $value) {
                        $errors[$key] = form_error($key);
                    }
                    $response_array['errors'] = array_filter($errors);

                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                    echo json_encode($response_array);

                } else {

                    if (!empty($_FILES)) {

                        $new_file = $_FILES["user_image"]["name"];
                    } else {
                        $new_file = "";
                    }

                    if (!empty($new_file)) {
                        $config['upload_path'] = './assets/images/user/';    // APPPATH . 'assets/uploads/';   //'./assets/uploads/';
                        $config['allowed_types'] = 'jpg|jpeg|png';
                        $config['max_size'] = 2000;
                        $config['max_width'] = 1200;
                        $config['max_height'] = 1200;
                        $time = time();
                        $config['file_name'] = $time;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('user_image')) {

                            $uploadOk = 0;
                            $errors = $this->upload->display_errors('', '');
                            $response_array['type'] = 'danger';
                            $response_array['message'] = '<i class="icon fa fa-warning"></i> <strong class="alert  alert-dismissable">' . $errors . '</strong>';

                        } else {

                            $data = $this->upload->data();
                            $file_path = 'assets/images/user/' . $time . $data['file_ext'];
                            $uploadOk = 1;
                        }
                    }


                    if ($uploadOk == 0) {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = $response_array['message']; //'<i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable">' . $response_array['message']. '</strong>';
                        echo json_encode($response_array);
                        // if everything is ok, try to upload file
                    } else {

                        $created_date = date('Y-m-d h:i:s');
                        $created_by = $this->ion_auth->get_user_id();
                        $uploadOk = 1;

                        $additional_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'last_name' => $this->input->post('last_name'),
                            'phone' => $this->input->post('user_phone'),
                            'created_on' => $created_date,
                            'created_by' => $created_by,
                            'file_path' => $file_path,
                        );

                        $user = $this->ion_auth->register($username, $password, $email, $additional_data, $group_id);

                        if ($user) {
                            $response_array['type'] = 'success';
                            $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> users Created  Successfully. </div>';
                            echo json_encode($response_array);

                        } else {
                            $errors = $this->ion_auth->errors();
                            $response_array['type'] = 'danger';
                            $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i>' . $errors . '</div>';
                            echo json_encode($response_array);
                        }
                    }
                }
            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }


    // check name
    public function users_name_check($str)
    {
        if (preg_match('/^[a-zA-Z ]+$/', $str)) {
            return true;
        } else {
            $this->form_validation->set_message('users_name_check', 'The users name contains only characters and underscore.');

            return false;

        }

    }

    // email validation
    public function email_address_check($str)
    {
        if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            $this->form_validation->set_message('email_address_check', 'Please enter a valid email address');

            return false;
        } else {
            return true;
        }

    }

    // get a record by id
    public function view()
    {
        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_user_details'] = $this->user_model->get_user_by_id($id);
            $single_user_details_view = $this->load->view('admin/user/v_user_details', $this->data, true);
            $this->output->set_output($single_user_details_view);
        } else {
            redirect('admin/dashboard');
        }
    }


    //update form
    public function edit_form()
    {

        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->data['user'] = $this->ion_auth->user($id)->row();
            $this->data['user_group'] = $this->ion_auth->get_users_groups($id)->row();
            $view = $this->load->view('admin/user/edit', $this->data, true);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    //update or edit records
    public function edit()
    {

        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $id = $this->input->post('updateId');
                $username = $this->input->post('username');
                $created_date = date('Y-m-d h:i:s');
                $created_by = $this->ion_auth->get_user_id();
                $group_id = $this->input->post('group_id');
                $file_path = $this->input->post('SelectedFileName');
                $active = $this->input->post('status');
                $uploadOk = 1;


                //set validations
                $this->form_validation->set_rules('username', 'users Name', 'trim|required|callback_users_name_check');

                if ($this->form_validation->run() == false) {
                    $errors = array();
                    foreach ($this->input->post() as $key => $value) {
                        $errors[$key] = form_error($key);
                    }
                    $response_array['errors'] = array_filter($errors);

                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                    echo json_encode($response_array);

                } else {

                    if (!empty($_FILES)) {

                        $new_file = $_FILES["user_image"]["name"];
                    } else {
                        $new_file = "";
                    }

                    if (!empty($new_file)) {
                        $config['upload_path'] = './assets/images/user/';    // APPPATH . 'assets/uploads/';   //'./assets/uploads/';
                        $config['allowed_types'] = 'jpg|jpeg|png';
                        $config['max_size'] = 5000;
                        $config['max_width'] = 1000;
                        $config['max_height'] = 1000;
                        $time = time();
                        $config['file_name'] = $time;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('user_image')) {

                            $uploadOk = 0;
                            $errors = $this->upload->display_errors('', '');
                            $response_array['type'] = 'danger';
                            $response_array['message'] = '<i class="icon fa fa-warning"></i> <strong class="alert  alert-dismissable">' . $errors . '</strong>';

                        } else {

                            $data = $this->upload->data();
                            $file_path = 'assets/images/user/' . $time . $data['file_ext'];
                            $uploadOk = 1;
                        }
                    }


                    if ($uploadOk == 0) {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = $response_array['message']; //'<i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable">' . $response_array['message']. '</strong>';
                        echo json_encode($response_array);
                        // if everything is ok, try to upload file
                    } else {

                        $additional_data = array(
                            'first_name' => $this->input->post('first_name'),
                            'username' => $username,
                            'last_name' => $this->input->post('last_name'),
                            'phone' => $this->input->post('user_phone'),
                            'created_on' => $created_date,
                            'created_by' => $created_by,
                            'file_path' => $file_path,
                            'active' => $active,
                        );

                        $this->ion_auth->remove_from_group('', $id);
                        $this->ion_auth->add_to_group($group_id, $id);

                        $results = $this->ion_auth->update($id, $additional_data);

                        if ($results) {
                            $response_array['type'] = 'success';
                            $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> Successfully Updated. </div>';
                            echo json_encode($response_array);

                        } else {
                            $response_array['type'] = 'danger';
                            $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                            echo json_encode($response_array);
                        }
                    }
                }
            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    // change password form form
    public function password_form()
    {

        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $view = $this->load->view('admin/user/add', $this->data, true);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    // delete a record
    public function delete()
    {
        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');

            $result = $this->ion_auth->delete_user($id);

            if ($result) {

                $this->ion_auth->remove_from_group('', $id);

                $response_array['type'] = 'success';
                $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i> Successfully Deleted. </div>';
                echo json_encode($response_array);
            } else {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i> Sorry! Failed.</div>';
                echo json_encode($response_array);
            }
        }
    }

}
