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

    function home()
    {
        $data['head_title'] = 'Welcome to colibri:house';
        $data['view_a'] = 'app/home_v';
        $this->App_model->view(TPL_ADMIN, $data);
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
                0 => 'pictures/explore/',  //Developer
                1 => 'pictures/explore/',  //Administrador
                13 => 'accounts/edit/', //Professional
                23 => 'accounts/edit/',  //Homeowner
            );
                
            $destination = $arr_destination[$this->session->userdata('role')];
        }
        
        redirect($destination);
    }

    function denied()
    {
        $data['status'] = 0;
        $data['message'] = 'Forbidden';

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function subscribe()
    {
        $data['head_title'] = 'Susbcribe';
        $data['view_a'] = 'app/susbcribe_v';
        $this->App_model->view('templates/bssocial/start_v', $data);
    }

    function test()
    {
        $this->load->view('app/test_v');
    }
}