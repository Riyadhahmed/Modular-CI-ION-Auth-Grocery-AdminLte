<?php

class User_groups extends Admin_Base_Controller
{

   function __construct()
   {
      parent::__construct();
      $this->load->model('group_model');

      // check admin groups or not
      $group = 'admin';
      if (!$this->ion_auth->in_group($group)) {
         $this->session->set_flashdata('message', 'You must be an administrator to view the users page.');
         redirect('admin/dashboard/access_denied');
      }
   }

   public function index()
   {
      $this->data['title'] = 'Manage user groups';
      $this->data['breadcrumbs'] = 'Manage user groups';
      $this->load->view('admin/user_groups/manage', $this->data);
   }

   // get all records
   public function get_all()
   {
      $this->setOutputMode(NORMAL);
      if ($this->input->is_ajax_request()) {
         $this->data['all'] = $this->group_model->get_all();
         $view = $this->load->view('admin/user_groups/all', $this->data, true);
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
         $view = $this->load->view('admin/user_groups/add', $this->data, true);
         $this->output->set_output($view);
      } else {
         redirect('admin/dashboard');
      }
   }


   // Insert New records
   public function create()
   {
      header('Content-Type: application/json');
      $this->setOutputMode(NORMAL);
      if ($this->input->is_ajax_request()) {

         $group_name = $this->input->post('group_name');
         $description = $this->input->post('description');

         //set validations
         $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');
         $this->form_validation->set_rules('description', 'Description', 'trim|required');

         if ($this->form_validation->run() == false) {
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
               $errors[$key] = form_error($key);
            }
            $response_array['errors'] = array_filter($errors);

            $response_array['type'] = 'danger';
            $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-warning"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
            echo json_encode($response_array);

         } else {

            $insertData = array(
              'name' => trim($group_name),
              'description' => trim($description)
            );

            $insertData = $this->security->xss_clean($insertData);

            $results = $this->group_model->create($insertData);

            if ($results) {

               $response_array['type'] = 'success';
               $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong>  Created Successfully. </div>';
               echo json_encode($response_array);

            } else {
               $response_array['type'] = 'danger';
               $response_array['message'] = $response_array['message'] . '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
               echo json_encode($response_array);
            }
         }

      } else {
         redirect('admin/dashboard');
      }
   }

   // get a record by id
   public function view($id)
   {

   }

   //update form
   public function edit_form()
   {

      $this->setOutputMode(NORMAL);

      if ($this->input->is_ajax_request()) {
         $id = $this->input->post('id');
         $this->data['single'] = $this->group_model->get_by_id($id);
         $view = $this->load->view('admin/user_groups/edit', $this->data, true);
         $this->output->set_output($view);
      } else {
         redirect('admin/dashboard');
      }
   }


   // edit a record
   public function edit()
   {
      header('Content-Type: application/json');
      $this->setOutputMode(NORMAL);
      if ($this->input->is_ajax_request()) {

         $updateId = $this->input->post('updateId');
         $group_name = $this->input->post('group_name');
         $description = $this->input->post('description');


         //set validations
         $this->form_validation->set_rules('group_name', 'Group Name', 'trim|required');

         if ($this->form_validation->run() == false) {
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
               $errors[$key] = form_error($key);
            }
            $response_array['errors'] = array_filter($errors);

            $response_array['type'] = 'danger';
            $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-warning"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
            echo json_encode($response_array);

         } else {
            $updateData = array(
              'name' => trim($group_name),
              'description' => trim($description)
            );

            $updateData = $this->security->xss_clean($updateData);

            $results = $this->group_model->edit($updateData, $updateId);

            if ($results) {

               $response_array['type'] = 'success';
               $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong>  Updated Successfully. </div>';
               echo json_encode($response_array);

            } else {
               $response_array['type'] = 'danger';
               $response_array['message'] = $response_array['message'] . '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
               echo json_encode($response_array);
            }
         }
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

         $result = $this->group_model->delete($id);

         if ($result) {
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