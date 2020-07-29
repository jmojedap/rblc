<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Comment_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($comment_id)
    {
        redirect("comments/info/{$comment_id}");
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------
    
    function explore()
    {
        //Basic data for exploration
            $data = $this->Comment_model->data_explore(1);
        
        //Opciones de filtros de búsqueda
            $data['arr_filters'] = array('type');
            //$data['type_options'] = $this->Item_model->options('category_id = 58', 'Todos');
            
            //Arrays con valores para contenido en lista
            //$data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * JSON Listado de Comments, número de página determinada, según criterios
     * de búsqueda definidos por GET en la URL.
     */
    function get($num_page = 1)
    {
        $data = $this->Comment_model->data_list($num_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CRUD
//-----------------------------------------------------------------------------
    
    //Elimina un comentario, tabla comment
    function delete($comment_id, $element_id)
    {
        $data = $this->Comment_model->delete($comment_id, $element_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina un grupo de comentarios seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $qty_deleted = 0;
        
        foreach ( $selected as $element_id ) 
        {
            $qty_deleted += $this->Comment_model->delete($element_id);
        }
        
        $result['status'] = 1;
        $result['message'] = 'Cantidad eliminados : ' . $qty_deleted;
        
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Form to add new doccument
     * 
     * @param type $tipo_rol
     */
    function add()
    {
        //Variables específicas
            //$data['tipo_rol'] = $tipo_rol;

        //Variables generales
            $data['head_title'] = 'Comentario';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'comments/explore/menu_v';
            $data['view_a'] = 'comments/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla Comment. Devuelve
     * result del proceso en JSON
     */ 
    function save($table_id, $element_id)
    {
        $data = $this->Comment_model->save($table_id, $element_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * Formulario para la edición de los datos de un Comment. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($comment_id, $section = 'basic')
    {
        //Datos básicos
            $data = $this->Comment_model->basic($comment_id);
        
            $view_a = "comments/edit/{$section}_v";
        
        //Array data espefícicas
            //$data['form_values'] = $this->Pcrn->valores_form($data['row'], 'comment');
            $data['nav_2'] = 'comments/menu_v';
            //$data['nav_3'] = 'comments/edit/menu_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
        
    }

    /**
     * POST JSON
     * 
     * @param type $comment_id
     */
    function update($comment_id)
    {
        $result = $this->Comment_model->update($comment_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    
    function info($comment_id)
    {
        //Datos básicos
        $data = $this->Comment_model->basic($comment_id);
        
        //Variables específicas
        $data['nav_2'] = 'comments/menu_v';
        $data['view_b'] = 'comments/info_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// INFO 
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Listado de comentarios de un elemento
     * 2020-06-08
     */
    function element_comments($table_id, $element_id, $parent_id = 0, $num_page = 1)
    {
        $data = array('qty_comments' => 0, 'comments' => array());
        $comments = $this->Comment_model->element_comments($table_id, $element_id, $parent_id, $num_page);

        if ( $comments->num_rows() > 0 )
        {
            $data['qty_comments'] = $comments->num_rows();
            $data['comments'] = $comments->result();
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}