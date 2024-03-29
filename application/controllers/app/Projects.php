<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/projects/';
    public $url_controller = URL_APP . 'projects/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Project_model');
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($project_id = NULL)
    {
        if ( is_null($project_id) ) {
            redirect("app/projects/explore/");
        } else {
            redirect("app/projects/info/{$project_id}");
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
            unset($data['nav_2']);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            $data['options_descriptor'] = $this->Item_model->options('category_id = 710', 'All descriptors');
            $data['options_style'] = $this->Item_model->options('category_id = 712', 'All styles');
            $data['options_feeling'] = $this->Item_model->options('category_id = 714', 'All feelings');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }
    
// CRUD
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo project
     */
    function add()
    {
        //Opciones formulario
            $data['options_project_type'] = $this->Item_model->options('category_id = 722');

        //Variables generales
            $data['head_title'] = 'Project';
            $data['nav_2'] = $this->views_folder . 'my_projects/menu_v';
            $data['view_a'] = $this->views_folder . 'add_v';

        $this->App_model->view(TPL_FRONT, $data);
    }
    
    /**
     * Información general del project
     */
    function info($project_id)
    {        
        //Datos básicos
        $data = $this->Project_model->basic($project_id);

        $data['images'] = $this->Post_model->images($project_id);
        $data['descriptors'] = $this->Project_model->metadata($project_id, 710);    //710 Descriptors
        $data['styles'] = $this->Project_model->metadata($project_id, 712);         //712 Styles
        $data['feelings'] = $this->Project_model->metadata($project_id, 714);       //714 Feelings

        $this->load->model('Post_model');
        $data['like_status'] = $this->Post_model->like_status($project_id);

        $this->load->model('User_model');
        $data['my_ideabooks'] = $this->User_model->ideabooks($this->session->userdata('user_id'));

        //Variables para comentarios
        $data['table_id'] = 2000;   //posts
        $data['element_id'] = $project_id;  //Post ID

        $data['view_a'] = $this->views_folder . 'info_v';
        $this->App_model->view(TPL_FRONT, $data);
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
        $data['options_project_type'] = $this->Item_model->options('category_id = 722');

        //Datos actuales
        $data['descriptors'] = $this->Project_model->metadata($project_id, 710);
        $data['styles'] = $this->Project_model->metadata($project_id, 712);
        $data['feelings'] = $this->Project_model->metadata($project_id, 714);
        
        //Array data espefícicas
            $data['nav_2'] = $this->views_folder . 'menu_v';
            $data['view_a'] = $this->views_folder . 'edit_v';
        
        $this->App_model->view(TPL_FRONT, $data);
    }

// PROJECT IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista formulario y galería de imágenes de un project
     * 2020-07-14
     */
    function images($project_id)
    {
        $data = $this->Project_model->basic($project_id);

        $data['images'] = $this->Post_model->images($project_id);

        $data['view_a'] = $this->views_folder . 'images/images_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

// USER PROJECTS
//-----------------------------------------------------------------------------
    
    /**
     * Administración de projectos creados por el usuario en sesión
     * 2020-07-16
     */
    function my_projects()
    {        
        //Opciones de filtros de búsqueda
            $data['controller'] = 'projects';                   //Nombre del controlador
            $data['cf'] = 'projects/my_projects/';              //Controlador/Función
            $data['views_folder'] = 'app/projects/my_projects/';    //Carpeta donde están las vistas de exploración
            
        //Cargar vista
            $data['head_title'] = 'My projects';
            $data['view_a'] = $this->views_folder . 'my_projects/explore_v';
            $data['nav_2'] = $this->views_folder . 'my_projects/menu_v';
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Listado de projects marcados por el usuario en sesión como favoritos, like, posts_meta.type_id = 10
     * 2020-07-17
     */
    function favorites()
    {
        //Opciones de filtros de búsqueda
        $data['controller'] = 'projects';                   //Nombre del controlador
        $data['cf'] = 'projects/favorites/';              //Controlador/Función
        $data['views_folder'] = 'projects/favorites/';    //Carpeta donde están las vistas de exploración

        //Cargar vista
        $data['head_title'] = 'Favorite projects';
        $data['view_a'] = $this->views_folder . 'favorites/explore_v';
        $this->App_model->view(TPL_FRONT, $data);
    }
}