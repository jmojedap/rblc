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

    /**
     * Destinos a los que se redirige después de validar el login de usuario
     * según el rol de usuario (índice del array)
     */
    function logged()
    {
        $destination = 'accounts/login';
        if ( $this->session->userdata('logged') )
        {
            $arr_destination = array(
                0 => 'users/explore/',  //Developer
                1 => 'users/explore/',  //Administrador
                2 => 'users/explore/',  //Editor
                13 => 'accounts/profile/', //Professional
                23 => 'accounts/profile/',  //Homeowner
            );
                
            $destination = $arr_destination[$this->session->userdata('role')];
        }
        
        redirect($destination);
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
     * Return String, with unique slut
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

    function corpo($format = 'creator')
    {
        $data['row'] = $this->Db_model->row_id('posts', '13541');
        $data['view_a'] = ( $format == 'creator') ? 'app/corpo_creator' : 'app/corpo' ;
        $data['head_title'] = 'CorpoTest';
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

// HELP
//-----------------------------------------------------------------------------

    function help($post_id = 0)
    {
        $data['head_title'] = 'Ayuda';
        $data['view_a'] = 'app/help/help_v';
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