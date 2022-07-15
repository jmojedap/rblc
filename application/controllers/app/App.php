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
            redirect('app/accounts/login');
        }    
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
        $data['view_a'] = 'app/app/susbcribe_v';
        $this->App_model->view('templates/colibri_blue/start', $data);
    }

    function test()
    {
        $this->load->view('app/test_v');
    }
}