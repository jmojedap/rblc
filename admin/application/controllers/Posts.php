<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("posts/explore/");
        } else {
            redirect("posts/info/{$post_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** Exploración de Posts */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     */
    function get($num_page = 1)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Post_model->get($filters, $num_page);
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
            $data['qty_deleted'] += $this->Post_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina un post a partir del ID
     * 2020-07-20
     */
    function delete($post_id)
    {
        $data['qty_deleted'] = $this->Post_model->delete($post_id);
        $data['status'] = ( $data['qty_deleted'] > 0 ) ? 1 : 0 ;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($post_id)
    {
        $row = $this->Db_model->row_id('posts', $post_id);
        $destination = "posts/read/{$post_id}";

        if ( $row->type_id == 41 ) { $destination = "polls/build/{$row->id}"; }

        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Post_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del post
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['view_a'] = $data['type_folder'] . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del post desde la perspectiva de base de datos
     * 2021-04-07
     */
    function details($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($post_id)
    {
        $data = $this->Post_model->basic($post_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// CREACIÓN DE UN POST
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo post
     */
    function add()
    {
        //Variables generales
            $data['head_title'] = 'posts';
            $data['head_subtitle'] = 'Nuevo';
            $data['nav_2'] = 'posts/explore/menu_v';
            $data['view_a'] = 'posts/add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function save()
    {
        $data = $this->Post_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
        $data = $this->Post_model->basic($post_id);

        $data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['head_subtitle'] = 'Editar';
            $data['view_a'] = $data['type_folder'] . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
// IMAGEN PRINCIPAL DEL POST
//-----------------------------------------------------------------------------

    function image($post_id)
    {
        $data = $this->Post_model->basic($post_id);        

        $data['view_a'] = 'posts/image/image_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function cropping($post_id)
    {
        $data = $this->Post_model->basic($post_id);        

        $data['image_id'] = $data['row']->image_id;
        $data['url_image'] = $data['att_img']['src'];
        $data['back_destination'] = "posts/image/{$post_id}";

        $data['view_a'] = 'files/cropping_v';
        $data['nav_2'] = 'posts/menu_v';
        $data['subtitle_head'] = 'Imagen asociada al post';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Carga file de image y se la asigna a un post.
     * 2020-02-22
     */
    function set_image($post_id)
    {
        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();
        
        $data = $data_upload;
        if ( $data_upload['status'] )
        {
            $this->Post_model->remove_image($post_id);                                  //Quitar image actual, si tiene una
            $data = $this->Post_model->set_image($post_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Desasigna y elimina la image asociada a un post, si la tiene.
     */
    function remove_image($post_id)
    {
        $data = $this->Post_model->remove_image($post_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// PROJECT IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista, gestión de imágenes de un post
     * 2020-07-14
     */
    function images($post_id)
    {
        $data = $this->Post_model->basic($post_id);

        $data['images'] = $this->Post_model->images($post_id);
        
        //Para formulario file
        $data['form_table_id'] = 2000;
        $data['form_related_1'] = $post_id;

        $data['view_a'] = 'posts/images/images_v';
        $data['subtitle_head'] = 'Images';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un post
     * 2020-07-07
     */
    function get_images($post_id)
    {
        $images = $this->Post_model->images($post_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un post
     * 2020-07-07
     */
    function set_main_image($post_id, $file_id)
    {
        $data = $this->Post_model->set_main_image($post_id, $file_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// COMENTARIOS
//-----------------------------------------------------------------------------

    /**
     * Vista comentarios de un post
     * 2020-06-08
     */
    function comments($post_id)
    {
        $data = $this->Post_model->basic($post_id);

        $data['view_a'] = 'posts/comments/comments_v';
        $data['nav_2'] = 'posts/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// METADATA
//-----------------------------------------------------------------------------

    /**
     * Guardar metadato de un post
     * 2020-07-03
     */
    function save_meta($post_id, $meta_id = 0)
    {
        $data = $this->Post_model->save_meta($post_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina registro de la tabla posts_meta
     * 2020-07-03
     */
    function delete_meta($post_id, $meta_id)
    {
        $data = $this->Post_model->delete_meta($post_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE POSTS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de posts
     * con archivo Excel. El resultado del formulario se envía a 
     * 'posts/import_e'
     */
    function import($type = 'general')
    {
        $data = $this->Post_model->import_config($type);

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];

        $data['head_title'] = 'Posts';
        $data['nav_2'] = 'posts/explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de posts con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Post_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "posts/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Posts';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = 'posts/explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// INTERACCIÓN DE USUARIO
//-----------------------------------------------------------------------------

    /**
     * Alternar like and unlike a un post por parte del usuario en sesión
     * 2020-07-17
     */
    function alt_like($post_id)
    {
        $data = $this->Post_model->alt_like($post_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}