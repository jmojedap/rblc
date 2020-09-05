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
        $this->home();
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

    /**
     * Formulario de edición de datos de una imagen, datos básicos y tags
     * 2020-08-11
     */
    function edit($file_id)
    {
        $data = $this->Picture_model->basic($file_id);

        //Opciones para agregar
        $data['options_tag'] = $this->App_model->options_tag('category_id = 1');

        //Datos actuales
        $data['tags'] = $this->Picture_model->tags($file_id);

        $data['view_a'] = 'pictures/edit_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// INICIO
//-----------------------------------------------------------------------------

    function home()
    {
        $data['head_title'] = APP_NAME;

        //Carrusel
        $carousel_id = $this->Db_model->field_id('sis_option', 104, 'option_value');
        $this->db->select('id, url');
        $data['carousel_images'] = $this->db->get_where('file', "table_id = 2000 AND related_1 = {$carousel_id}");

        $data['view_a'] = 'info/home_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }


}