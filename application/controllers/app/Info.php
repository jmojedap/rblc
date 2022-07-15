<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'app/info/';
public $url_controller = URL_APP . 'info/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Información sobre professionals
     * 2022-07-11
     */
    function professionals()
    {
        $data['head_title'] = 'Colibri for professionals';
        $data['view_a'] = $this->views_folder . 'professionals_v';

        $this->App_model->view('templates/colibri_blue/main_dark', $data);
    }

    /**
     * Información sobre homeowners
     * 2022-07-11
     */
    function homeowners()
    {
        $data['head_title'] = 'Colibri for homeowners';
        $data['view_a'] = $this->views_folder . 'homeowners_v';

        $this->App_model->view('templates/colibri_blue/main_dark', $data);
    }
}