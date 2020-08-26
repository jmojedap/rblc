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
        redirect("users/profile/{$user_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
        
    function explore()
    {        
        //Datos básicos de la exploración
            $data = $this->Professional_model->explore_data(1);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            $data['arr_id_number_types'] = $this->Item_model->arr_item('category_id = 53', 'cod_abr');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Información general del usuario
     */
    function profile($user_id)
    {        
        //Datos básicos
        $data = $this->Professional_model->basic($user_id);

        //Variables específicas
        $data['follow_status'] = $this->Professional_model->follow_status($user_id);

        //Vista
        $data['view_a'] = 'professionals/profile_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// GALERÍA DE IMÁGENES
//-----------------------------------------------------------------------------

    function images()
    {
        $user_id = $this->session->userdata('user_id');
        $data = $this->Professional_model->basic($user_id);

        $data['view_a'] = 'professionals/images/images_v';
        $data['nav_2'] = 'accounts/edit/menu_v';
        $data['subtitle_head'] = 'images';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($user_id, $section = 'basic')
    {
        //Datos básicos
        $data = $this->Professional_model->basic($user_id);
        
        $view_a = "users/edit/{$section}_v";
        if ( $section == 'cropping' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = URL_UPLOADS . $data['row']->url_image;
            $data['back_destination'] = "users/edit/{$user_id}/image";
        }
        
        //Array data espefícicas
            //$data['valores_form'] = $this->Pcrn->valores_form($data['row'], 'user');
            //$data['nav_2'] = 'users/menus/user_v';
            $data['nav_3'] = 'users/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario edición de categorías y tags de un usuario professional
     * 2020-08-01
     */
    function categories()
    {
        //Datos básicos
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);

        //Opciones para agregar
        $data['options_services'] = $this->Item_model->options('category_id = 716');
        $data['options_tag'] = $this->App_model->options_tag('category_id = 1');

        //Datos actuales
        $data['services'] = $this->Professional_model->metadata($user_id, 716);
        $data['tags'] = $this->Professional_model->tags($user_id);
        
        $data['nav_2'] = 'accounts/edit/menu_v';
        $data['view_a'] = 'users/edit/categories_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// INSPIRATION
//-----------------------------------------------------------------------------

    function inspiration($descriptor = 'kitchen', $num_page = 1)
    {
        $data['head_title'] = 'Inspiration';
        $data['view_a'] = 'professionals/inspiration/inspiration_v';
        $data['descriptor_slug'] = $descriptor;
        $this->App_model->view(TPL_ADMIN, $data);
    }
}