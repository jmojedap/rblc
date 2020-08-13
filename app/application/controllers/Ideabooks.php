<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ideabooks extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Ideabook_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }

    function info($ideabook_id)
    {
        $data = $this->Ideabook_model->basic($ideabook_id);
        $data['view_a'] = 'ideabooks/info_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo ideabook
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'New ideabook';
            $data['nav_2'] = 'ideabooks/my_ideabooks/menu_v';
            $data['view_a'] = 'ideabooks/add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista formulario para la edición de los datos básicos de un ideabook
     * 2020-07-18
     */
    function edit($ideabook_id)
    {
        $data = $this->Ideabook_model->basic($ideabook_id);
        $data['view_a'] = 'ideabooks/edit/edit_v';
        $data['nav_2'] = 'ideabooks/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Vista lista de projects incluidos en un ideabook.
     * 2020-08-13
     */
    function projects($ideabook_id)
    {
        $data = $this->Ideabook_model->basic($ideabook_id);
        $data['view_a'] = 'ideabooks/projects_v';
        $data['nav_2'] = 'ideabooks/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * Administración de ideabookos creados por el usuario en sesión
     * 2020-07-16
     */
    function my_ideabooks()
    {        
        //Opciones de filtros de búsqueda
            $data['controller'] = 'ideabooks';                    //Nombre del controlador
            $data['cf'] = 'ideabooks/my_ideabooks/';              //Controlador/Función
            $data['views_folder'] = 'ideabooks/my_ideabooks/';    //Carpeta donde están las vistas de exploración
            
        //Cargar vista
            $data['head_title'] = 'Ideabooks';
            $data['view_a'] = 'ideabooks/my_ideabooks/explore_v';
            $data['nav_2'] = 'ideabooks/my_ideabooks/menu_v';
            $this->App_model->view(TPL_ADMIN, $data);
    }
}