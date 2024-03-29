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

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * JSON
     * Listado de una cantidad aleatoria de imágenes
     * 2020-08-22
     */
    function get_home_pictures($qty = 12)
    {
        $data = $this->Picture_model->get_home_pictures($qty);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Picture info
//-----------------------------------------------------------------------------

    function details($file_id)
    {
        $data = $this->Model->basic($file_id);
        $data['view_a'] = 'view_a';
        $data['nav_2'] = '';
        $data['subtitle_head'] = '';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function edit($file_id)
    {
        $data = $this->Picture_model->basic($file_id);
        $data['view_a'] = 'pictures/edit_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Detalles de picture, row y tags
     * 2020-08-26
     */
    function get_details($file_id)
    {
        $picture = $this->Picture_model->row("id = {$file_id}");
        $user = $this->Db_model->row_id('users', $picture->creator_id);

        $tags = $this->Picture_model->tags($file_id);
        $picture->tags = $tags->result();
        $picture->like_status = $this->Picture_model->like_status($file_id);

        $picture->user['id'] = $user->id;
        $picture->user['display_name'] = $user->display_name;
        $picture->user['url_thumbnail'] = $user->url_thumbnail;

        $data = $picture;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}