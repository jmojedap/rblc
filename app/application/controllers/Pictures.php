<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pictures extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Picture_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index()
    {
        redirect("pictures/explore/");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    /**
     * Vista exploración de imágenes
     * 2020-07-28
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Picture_model->explore_data($filters, $num_page);
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de imágenes por página y filtradas por criterios de búsqueda
     * 2020-07-28
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Picture_model->get($filters, $num_page);

        //$this->output->enable_profiler(TRUE);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Picture info
//-----------------------------------------------------------------------------

    function details($picture_id)
    {
        $data = $this->Model->basic($picture_id);
        $data['view_a'] = 'view_a';
        $data['nav_2'] = '';
        $data['subtitle_head'] = '';
        $this->App_model->view(TPL_ADMIN, $data);
    }

}