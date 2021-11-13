<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Professionals extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Professional_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect("professionals/info/{$user_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Professional_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            $data['arr_id_number_types'] = $this->Item_model->arr_item('category_id = 53', 'cod_abr');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Professional_model->get($filters, $num_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $qty_deleted = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $qty_deleted += $this->Professional_model->delete($row_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        $result['qty_deleted'] = $qty_deleted;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Formulario para la creación de un nuevo usuario
     */
    function add($role_type = 'person')
    {
        //Variables específicas
            $data['role_type'] = $role_type;

        //Variables generales
            $data['head_title'] = 'Users';
            $data['nav_2'] = 'users/explore/menu_v';
            //$data['nav_3'] = 'users/add/menu_v';
            $data['view_a'] = 'users/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla user. Devuelve
     * result del proceso en JSON
     * 2019-11-07
     */ 
    function insert()
    {
        $res_validation = $this->Professional_model->validate();
        
        if ( $res_validation['status'] )
        {
            $data = $this->Professional_model->insert();
        } else {
            $data = $res_validation;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Información general del usuario
     */
    function profile($user_id)
    {        
        //Datos básicos
        $data = $this->Professional_model->basic($user_id);

        $data['view_a'] = 'users/profile/profile_v';
        if ( $data['row']->role == 23  ) { $data['view_a'] = 'users/profile/client_v'; }
        
        //Variables específicas
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// GESTIÓN DE METADATOS, TAGS Y CATEGORÍAS
//-----------------------------------------------------------------------------

    /**
     * Formulario edición de categorías y tags de un usuario professional
     * 2020-08-01
     */
    function categories($user_id)
    {
        //Datos básicos
        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);

        //Opciones para agregar
        $data['options_services'] = $this->Item_model->options('category_id = 716');
        $data['options_tag'] = $this->App_model->options_tag('category_id = 1');

        //Datos actuales
        $data['services'] = $this->Professional_model->metadata($user_id, 716);
        $data['tags'] = $this->Professional_model->tags($user_id);
        
        //$data['nav_3'] = 'users/edit/menu_v';
        $data['view_a'] = 'users/edit/categories_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Guardar cambios en metadatos y categorías
     * 2020-08-03
     */
    function update_categories($user_id)
    {
        //Save services
        $services = ( is_null($this->input->post('services')) ) ? array() : $this->input->post('services');
        $data['updated_services'] = $this->Professional_model->save_meta_array($user_id, 716, $services);

        //Save tags
        $tags = ( is_null($this->input->post('tags')) ) ? array() : $this->input->post('tags');
        $data['updated_tags'] = $this->Professional_model->save_meta_array($user_id, 27, $tags);

        //Result
        $data['status'] = 1;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ESPECIAL METADATA
//-----------------------------------------------------------------------------

    /**
     * Detalles del usuario
     */
    function details_ant($user_id)
    {
        $data = $this->Professional_model->basic($user_id);

        $data['tags'] = $this->Professional_model->tags($user_id);
        $data['professional_services'] = $this->Professional_model->metadata($user_id);

        $data['view_a'] = 'users/details_v';
        $data['subtitle_head'] = 'Details';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// GALERÍA DE IMÁGENES
//-----------------------------------------------------------------------------

    function images($user_id)
    {
        $data = $this->Professional_model->basic($user_id);

        $data['images'] = $this->Professional_model->images($user_id);

        $data['view_a'] = 'admin/users/images/images_v';
        $data['nav_2'] = 'admin/users/menus/professional_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Imágenes asociadas a un usuario
     */
    function get_images($user_id, $album_id = 10)
    {
        $images = $this->Professional_model->images($user_id, $album_id);
        $data['images'] = $images->result();
        
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INSPIRATION
//-----------------------------------------------------------------------------

    /**
     * Listado de imágenes de usuarios, filtradas por descriptores
     * 2020-06-23
     */
    function inspiration_images($tag_slug, $num_page = 1)
    {
        $images = $this->Professional_model->inspiration_images($tag_slug, $num_page);

        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
        //$this->output->enable_profiler(TRUE);
    }

}