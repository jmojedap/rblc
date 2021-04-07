<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tags extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Tag_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($tag_id = NULL)
    {
        if ( is_null($tag_id) ) {
            redirect("tags/explore/");
        } else {
            redirect("tags/info/{$tag_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Tags */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Tag_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_category'] = $this->Item_model->options('category_id = 27', 'All');
            
        //Arrays con valores para contenido en lista
            $data['arr_category'] = $this->Item_model->arr_cod('category_id = 27');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Tags, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Tag_model->get($filters, $num_page);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de tags seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_affected'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_affected'] += $this->Tag_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_affected'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Información general del tag
     */
    function info($tag_id)
    {        
        //Datos básicos
        $data = $this->Tag_model->basic($tag_id);

        $data['qty_files'] = $this->Db_model->num_rows('files_meta', "type_id = 27 AND related_1 = {$tag_id}");

        $data['view_a'] = 'tags/info_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista Formulario para la creación de un nuevo tag
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Tag';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'tags/explore/menu_v';
            $data['view_a'] = 'tags/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Crea un nuevo registro en la tabla tag
     * 2019-11-29
     */
    function insert()
    {
        $data = $this->Tag_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($tag_id)
    {
        //Datos básicos
        $data = $this->Tag_model->basic($tag_id);

        $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['nav_2'] = 'tags/menu_v';
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = 'tags/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar un registro en la tabla tag, si tag_id = 0, se crea nuevo registro
     * 2019-11-29
     */
    function update($tag_id)
    {
        $data = $this->Tag_model->update($tag_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}