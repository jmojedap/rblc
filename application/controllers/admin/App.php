<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('accounts/login');
        }    
    }

    function denied()
    {
        /*$data['head_title'] = 'Acceso No Permitido';
        $data['view_a'] = 'app/denied_v';
        $this->load->view('templates/bootstrap/start_v', $data);*/
        $data['status'] = 0;
        $data['message'] = 'Forbidden';

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//GENERAL AJAX SERVICES
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX - POST
     * Return String, with unique slug
     */
    function unique_slug()
    {
        $text = $this->input->post('text');
        $table = $this->input->post('table');
        $field = $this->input->post('field');
        
        $unique_slug = $this->Db_model->unique_slug($text, $table, $field);
        
        $this->output->set_content_type('application/json')->set_output($unique_slug);
    }    

    function test()
    {
        $data['view_a'] = 'app/test_v';
        $data['head_title'] = 'Prueba Display';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar email para suscripción a newsletter
     * 2020-07-22
     */
    function save_subscription()
    {
        $data = array('status' => 0);

        //Se valida con ReCaptcha por ser un proceso sin sesión de usuario
        $this->load->model('Validation_model');
        $recaptcha = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3

        if ( $recaptcha->score > 0.5 )
        {
            $data = $this->App_model->save_subscription();
            $data['recaptcha_score'] = $recaptcha->score;
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Notificaciones
//-----------------------------------------------------------------------------

    /**
     * Cantidad de alertas de notificaciones sin leer por parte del usuario
     * 2021-08-12
     */
    function qty_unread_notifications(){
        $this->load->model('Notification_model');
        $data['qty_unread_notifications'] = $this->Notification_model->qty_unread_notifications();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Lista de notificaciones para el usuario
     * 2021-08-12
     */
    function get_notifications(){
        $this->load->model('Notification_model');
        $notifications = $this->Notification_model->notifications();
        $data['notifications'] = $notifications->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Marca una notificación como leída y retorna link de la notificación
     */
    function open_notification($event_id)
    {
        $this->load->model('Notification_model');
        $data = $this->Notification_model->open($event_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// HELP
//-----------------------------------------------------------------------------

    function help($post_id = 0)
    {
        $data['head_title'] = 'Ayuda';
        $data['view_a'] = 'admin/app/help/help_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }


//AUTOCOMPLETAR
//---------------------------------------------------------------------------------------------------
    
    function autocomplete()
    {
        $data['head_title'] = 'Autocomplete';
        $data['view_a'] = 'app/autocomplete_v';
        $this->load->view(TPL_ADMIN, $data);
    }

    function arr_elements($table)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['q'] = $this->input->get('term');
        
        switch ($table) 
        {
            case 'user':
                $this->load->model('User_model');
                $elements = $this->User_model->autocomplete($filters);
                break;
            case 'tag':
                $this->load->model('Tag_model');
                $elements = $this->Tag_model->autocomplete($filters);
                break;

            default:
                break;
        }
        
        $arr_elements = $elements->result_array();
        
        $this->output->set_content_type('application/json')->set_output(json_encode($arr_elements));
        //$this->output->enable_profiler(TRUE);
    }
}