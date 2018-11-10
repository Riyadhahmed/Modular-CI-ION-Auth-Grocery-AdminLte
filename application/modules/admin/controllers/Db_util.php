<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Db_util extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('db_util_model');
        $this->load->dbutil();
    }

    public function index()
    {
        $this->data['title'] = 'Database List';
        $this->data['breadcrumbs'] = 'Database List';
        $this->data['message'] = 'All Database List in MS SQL 2005 Server';

        $dbs = $this->dbutil->list_databases();

        foreach ($dbs as $db) {
            $database[] = $db . '<br/>';
        }

        $this->data['util_database'] = $database;
        $this->load->view('admin/dbutil/db_util_view', $this->data);
    }

    public function database_exist()
    {
        $this->data['title'] = 'Database Exist';
        $this->data['breadcrumbs'] = 'Database Exist';
        $this->data['message'] = 'Checking a Database is already Exist or Not';
        if ($this->dbutil->database_exists('payroll')) {
            $this->data['util_database'] = "Payroll Database Is Exists";
        } else {
            $this->data['util_database'] = "Payroll Database Is Not Exists";
        }
        $this->load->view('admin/dbutil/db_util_view', $this->data);
    }

    public function optimize_table()
    {

        $this->data['title'] = 'Optimize Table';
        $this->data['breadcrumbs'] = 'Optimize Table';
        $this->data['message'] = 'Optimize a Database Table';
        if ($this->dbutil->database_exists('payroll')) {
            if ($this->dbutil->optimize_table('admin')) {
                $this->data['util_database'] = 'Success!';
            }
        }
        $this->load->view('admin/dbutil/db_util_view', $this->data);
    }

    public function csv_view()
    {

        $this->data['title'] = 'Csv Print Data';
        $this->data['breadcrumbs'] = 'Csv Print Data';
        $this->data['message'] = 'Csv Print Data From Admin Table';
        if ($this->dbutil->database_exists('payroll')) {
            $delimiter = ",";
            $newline = "\r\n";
            $enclosure = '"';
            $query = $this->db->query("SELECT * FROM admin");
            $file_name = '/Reports_' . time() . '.csv';
            $new_report = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
            $this->file_path = realpath(APPPATH . '../assets/csv/');
            write_file($this->file_path . $file_name, $new_report);
            //force download from server
            $this->load->helper('download');
            $data = file_get_contents($this->file_path . $file_name);
            $name = 'name_' . time() . '.csv';
            force_download($name, $data);

        }

        //$this->load->view('admin/dbutil/db_util_view', $this->data);
    }

    public function xml_view()
    {

        $this->data['title'] = 'Csv Print Data';
        $this->data['breadcrumbs'] = 'Csv Print Data';
        $this->data['message'] = 'XML Data From Admin Table';
        if ($this->dbutil->database_exists('payroll')) {
            $query = $this->db->query("SELECT * FROM admin");
            $config = array(
                'root' => 'root',
                'element' => 'element',
                'newline' => "\n",
                'tab' => "\t"
            );


            $this->data['util_database'] = $this->dbutil->xml_from_result($query, $config);
        }
        $this->load->view('admin/dbutil/db_util_view', $this->data);
    }


    function print_item()
    {
        // load library
        $this->data['title'] = 'Pdf Reports';
        $this->data['breadcrumbs'] = 'Csv Print Data';

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        // retrieve data from model
        $this->data['all_user'] = $this->db->query("SELECT * FROM admin");
        $html = $this->load->view('admin/dbutil/pdf_view', $this->data, true);
        // render the view into HTML
        $pdf->WriteHTML($html);
        // write the HTML into the PDF
        $output = 'itemreport' . time() . '_.pdf';
        $pdf->Output("$output", 'I');
        // save to file because we can exit();
    }

    public function backup_database()
    {

        $this->data['title'] = 'Backup Database';
        $this->data['breadcrumbs'] = 'Backup Database';
        $this->data['message'] = 'Backup Database';
        if ($this->dbutil->database_exists('payroll')) {

            $prefs = array(
                'tables' => array(),   // Array of tables to backup.
                'ignore' => array(),                     // List of tables to omit from the backup
                'format' => 'txt',                       // gzip, zip, txt
                'filename' => 'backup_payroll.sql',              // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop' => TRUE,                        // Whether to add DROP TABLE statements to backup file
                'add_insert' => TRUE,                        // Whether to add INSERT data to backup file
                'newline' => "\n"                         // Newline character used in backup file
            );

            $backup = $this->dbutil->backup($prefs);
            $this->load->helper('file');
            write_file('/assets/backup/backup_payroll.sql', $backup);

            // Load the download helper and send the file to your desktop
            $this->load->helper('download');
            force_download('backup_payroll.zip', $backup);

            $this->data['util_database'] = "Successfully Backup Payroll Database";
        }
        $this->load->view('admin/dbutil/db_util_view', $this->data);
    }
}