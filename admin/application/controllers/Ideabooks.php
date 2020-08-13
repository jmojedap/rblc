<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ideabooks extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->model('Ideabook_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("ideabooks/explore/");
        } else {
            redirect("ideabooks/info/{$post_id}");
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
            $data = $this->Ideabook_model->explore_data($filters, $num_page);
            
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

        $data = $this->Ideabook_model->get($filters, $num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Ideabook_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo ideabook
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'Ideabook';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'ideabooks/explore/menu_v';
            $data['view_a'] = 'ideabooks/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Crea un nuevo registro de ideabook en la tabla post
     * 2019-11-29
     */
    function insert()
    {
        $data = $this->Ideabook_model->insert();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del ideabook
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Ideabook_model->basic($post_id);
        $data['view_a'] = 'ideabooks/info_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Ideabook_model->basic($post_id);

        //$data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['nav_2'] = 'ideabooks/menu_v';
            $data['head_subtitle'] = 'Edit';
            $data['view_a'] = 'ideabooks/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar un registro en la tabla post, si post_id = 0, se crea nuevo registro
     * 2019-11-29
     */
    function update($post_id)
    {
        $data = $this->Ideabook_model->update($post_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMÁGENES
//-----------------------------------------------------------------------------

    /**
     * Agrega imagen asociada a un ideabook
     * 2020-07-03
     */
    function add_image($ideabook_id, $file_id)
    {
        $data = $this->Ideabook_model->add_image($ideabook_id, $file_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de imágenes que están incluidos en un ideabook
     * 2020-07-18
     */
    function get_images($ideabook_id)
    {
        $images = $this->Ideabook_model->images($ideabook_id);

        $data['list'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PROJECTS
//-----------------------------------------------------------------------------

    function projects($ideabook_id)
    {
        $data = $this->Ideabook_model->basic($ideabook_id);

        $data['projects'] = $this->Ideabook_model->projects($ideabook_id);

        $data['view_a'] = 'ideabooks/projects_v';
        $data['nav_2'] = 'ideabooks/menu_v';
        $data['subtitle_head'] = 'Projects';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de projects incluidos en un ideabook
     */
    function get_projects($ideabook_id)
    {
        $projects = $this->Ideabook_model->projects($ideabook_id);
        $data['list'] = $projects->result();
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Agrega un project a un ideabook
     * 2020-07-03
     */
    function add_project($ideabook_id, $project_id)
    {
        //No existe, se crea un nuevo ideabook
        if ( $ideabook_id == 0 && $this->input->post('post_name') ) 
        {
            $new_ideabook = $this->Ideabook_model->insert();
            if ( $new_ideabook['status'] )
            {
                $ideabook_id = $new_ideabook['saved_id'];
            }

            //Recargar ideabooks en variables de sesión
            $this->load->model('User_model');
            $ideabooks = $this->User_model->ideabooks($this->session->userdata('user_id')); 
            $this->session->set_userdata('ideabooks', $this->pml->query_to_array($ideabooks, 'title', 'id'));
        }

        $data = $this->Ideabook_model->add_project($ideabook_id, $project_id);
        $data['ideabooks'] = $this->session->userdata('ideabooks');

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}