<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/projects/';
    public $url_controller = URL_ADMIN . 'projects/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Project_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($project_id = NULL)
    {
        if ( is_null($project_id) ) {
            redirect("projects/explore/");
        } else {
            redirect("projects/info/{$project_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Projects */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Project_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_project_type'] = $this->Item_model->options('category_id = 722', 'Todos');
            $data['options_feeling'] = $this->Item_model->options('category_id = 714', 'All feeling');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            $data['arr_project_types'] = $this->Item_model->arr_cod('category_id = 722');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Projects, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Project_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de projects seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Project_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo project
     */
    function add()
    {
        //Opciones
            $data['options_project_type'] = $this->Item_model->options('category_id = 722');
            $data['options_feelings'] = $this->Item_model->options('category_id = 714', 'All feelings');

        //Variables generales
            $data['head_title'] = 'Project';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            $data['view_a'] = $this->views_folder . 'add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Crea un nuevo registro en la tabla post
     * 2019-11-29
     */
    function insert()
    {
        $data = $this->Project_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del project
     */
    function info($project_id)
    {        
        //Datos básicos
        $data = $this->Project_model->basic($project_id);

        $data['descriptors'] = $this->Project_model->metadata($project_id, 710);    //710 Descriptors
        $data['styles'] = $this->Project_model->metadata($project_id, 712);         //712 Styles
        $data['feelings'] = $this->Project_model->metadata($project_id, 714);       //714 Feelings

        $data['view_a'] = $this->views_folder . 'info_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($project_id)
    {
        //Datos básicos
        $data = $this->Project_model->basic($project_id);

        //Opciones para agregar
        $data['options_descriptors'] = $this->Item_model->options('category_id = 710');
        $data['options_styles'] = $this->Item_model->options('category_id = 712');
        $data['options_feelings'] = $this->Item_model->options('category_id = 714');
        $data['options_project_types'] = $this->Item_model->options('category_id = 722');

        //Datos actuales
        $data['descriptors'] = $this->Project_model->metadata($project_id, 710);
        $data['styles'] = $this->Project_model->metadata($project_id, 712);
        $data['feelings'] = $this->Project_model->metadata($project_id, 714);
        
        //Array data espefícicas
            $data['nav_2'] = $this->views_folder . 'menu_v';
            $data['view_a'] = $this->views_folder . 'edit_v';
            $data['back_link'] = $this->url_controller . 'explore';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar un registro en la tabla project, si project_id = 0, se crea nuevo registro
     * 2019-11-29
     */
    function update($project_id)
    {
        $data = $this->Project_model->update($project_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// PROJECT IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista, gestión de imágenes de un project
     * 2020-07-14
     */
    function images($project_id)
    {
        $data = $this->Project_model->basic($project_id);

        $data['images'] = $this->Project_model->images($project_id);

        $data['view_a'] = $this->views_folder . 'images/images_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un proyecto
     * 2020-07-07
     */
    function get_images($project_id)
    {
        $images = $this->Project_model->images($project_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Asocia una imagen a un proyecto, lo carga en la tabla file, y lo asocia en la tabla
     * project_meta
     * 2020-07-06
     */
    function add_image($project_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();

        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $data['meta_id'] = $this->Project_model->add_image($project_id, $data_upload['row']->id);   //Asociar en la tabla project_meta
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un proyecto
     * 2020-07-07
     */
    function set_main_image($project_id, $meta_id)
    {
        $data = $this->Project_model->set_main_image($project_id, $meta_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina una imagen de un proyecto, elimina el registro de la tabla file
     * y sus archivos relacionados
     * 2020-07-08
     */
    function delete_image($project_id, $meta_id)
    {
        $data['qty_deleted'] = $this->Project_model->delete_image($project_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PROJECTS DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Actualiza registro y datos asociados de un project
     * 2021-02-24 (related_2)
     */
    function update_full($project_id)
    {
        //Update row
            $arr_row['post_name'] = $this->input->post('post_name');
            if ( ! is_null($this->input->post('related_1')) ) $arr_row['related_1'] = $this->input->post('related_1');
            $arr_row['related_2'] = $this->input->post('related_2');
            $arr_row['integer_1'] = $this->input->post('integer_1');
            $arr_row['updater_id'] = $this->session->userdata('user_id');

            $data['saved_id'] = $this->Db_model->save('posts', "id = {$project_id}", $arr_row);

        //Save descriptors
            $descriptors = ( is_null($this->input->post('descriptors')) ) ? array() : $this->input->post('descriptors');
            $data['updated_descriptors'] = $this->Project_model->save_meta_array($project_id, 710, $descriptors);

        //Save styles
            $styles = ( is_null($this->input->post('styles')) ) ? array() : $this->input->post('styles');
            $data['updated_styles'] = $this->Project_model->save_meta_array($project_id, 712, $styles);

        //Save feelings
            $feelings = ( is_null($this->input->post('feelings')) ) ? array() : $this->input->post('feelings');
            $data['updated_feelings'] = $this->Project_model->save_meta_array($project_id, 714, $feelings);

        //Result
            $data['status'] = 1;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}