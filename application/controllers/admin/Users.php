<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'admin/users/';
public $url_controller = URL_ADMIN . 'users/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('User_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect("admin/users/info/{$user_id}");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
    
    /**
     * Exploración y búsqueda de usuarios
     * 2020-08-01
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->User_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_role'] = $this->Item_model->options('category_id = 58', 'All');
            
        //Arrays con valores para contenido en lista
            $data['arr_roles'] = $this->Item_model->arr_cod('category_id = 58');
            $data['arr_id_number_types'] = $this->Item_model->arr_options('category_id = 53');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    
    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->User_model->get($filters, $num_page, $per_page);

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
            $qty_deleted += $this->User_model->delete($row_id);
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
     * 
     * @param type $tipo_rol
     */
    function add($role_type = 'person')
    {
        //Variables específicas
            $data['role_type'] = $role_type;

        //Variables generales
            $data['head_title'] = 'Users';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            //$data['nav_3'] = 'users/add/menu_v';
            $data['view_a'] = $this->views_folder . 'add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * POST JSON
     * Toma datos de POST e inserta un registro en la tabla user. Devuelve
     * result del proceso en JSON
     * 2019-11-07
     * 
     */ 
    function insert()
    {
        $res_validation = $this->User_model->validate();
        
        if ( $res_validation['status'] )
        {
            $data = $this->User_model->insert();
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
        $data = $this->User_model->basic($user_id);

        $data['view_a'] = $this->views_folder . 'profile/profile_v';
        if ( $data['row']->role == 13  ) { $data['view_a'] = $this->views_folder . 'profile/professional_v'; }
        
        //Variables específicas
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// METADATA
//-----------------------------------------------------------------------------

    /**
     * Eliminar registro de la tabla meta
     * 2020-05-27
     */
    function delete_meta($user_id, $meta_id)
    {
        $data = $this->User_model->delete_meta($user_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ESPECIAL METADATA
//-----------------------------------------------------------------------------

    /**
     * Content, information about user account
     * 2020-05-14
     */
    function content($user_id)
    {
        $data = $this->User_model->basic($user_id);

        $data['row_content'] = $this->User_model->row_content($user_id);

        $data['view_a'] = $this->views_folder . 'content_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Detalles del usuario
     */
    function details($user_id)
    {
        $data = $this->User_model->basic($user_id);

        $data['tags'] = $this->User_model->tags($user_id);
        $data['professional_services'] = $this->User_model->professional_services($user_id);

        $data['view_a'] = $this->views_folder . 'details_v';
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
        $data = $this->User_model->basic($user_id);
        
        $view_a = $this->views_folder . "edit/{$section}_v";
        if ( $section == 'cropping' )
        {
            $view_a = 'admin/files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = $data['row']->url_image;
            $data['back_destination'] = "users/edit/{$user_id}/image";
        }

        //Opciones formulario
            $data['options_type'] = $this->Item_model->options("category_id = 63", 'Account type');
            $data['options_role'] = $this->Item_model->options("category_id = 58 AND cod >= {$this->session->userdata('role')}");
            $data['options_cat_1'] = $this->Item_model->options("category_id = 720 AND level = 0", 'Category');
            $data['items_cat_2'] = $this->Item_model->get_items("category_id = 720 AND level = 1");
            $data['options_country'] = $this->App_model->options_country();
        
        //Array data espefícicas
            $data['nav_3'] = $this->views_folder . 'edit/menu_v';
            $data['view_a'] = $view_a;
            $data['back_link'] = $this->url_controller . 'explore/';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
    
    /**
     * AJAX JSON
     * Se validan los datos de un user add o existente ($user_id),
     * los datos deben cumplir varios criterios
     * 
     * @param type $user_id
     */
    function validate($user_id = NULL)
    {
        $data = $this->User_model->validate($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * POST JSON
     * 
     * @param type $user_id
     */
    function update($user_id)
    {
        $arr_row = $this->input->post();
        //if ( strlen($arr_row['email']) == 0 ) { unset($arr_row['email']); }  //Si viene vacío evitar que sea nulo

        $data = $this->User_model->update($user_id, $arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo user.activation_key, para activar o restaurar la contraseña de un usuario
     * 2019-11-18
     */
    function set_activation_key($user_id)
    {
        $this->load->model('Account_model');
        $activation_key = $this->Account_model->activation_key($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($activation_key));
    }
    
// IMAGEN DE PERFIL DE USUARIO
//-----------------------------------------------------------------------------
    /**
     * Carga file de image y se la asigna a un user.
     * @param type $user_id
     */
    function set_image($user_id)
    {
        //Cargue
        $this->load->model('File_model');
        
        $data_upload = $this->File_model->upload();
        
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada');
        if ( $data_upload['status'] )
        {
            $this->User_model->remove_image($user_id);                              //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);   //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data_upload));
    }
    
    /**
     * POST REDIRECT
     * 
     * Proviene de la herramienta de recorte users/edit/$user_id/crop, 
     * utiliza los datos del form para hacer el recorte de la image.
     * Actualiza las miniaturas
     * 
     * @param type $user_id
     * @param type $file_id
     */
    function crop_image_e($user_id, $file_id)
    {
        $this->load->model('File_model');
        $this->File_model->crop($file_id);
        redirect("users/edit/{$user_id}/image");
    }
    
    /**
     * AJAX
     * Desasigna y elimina la image asociada a un user, si la tiene.
     * 
     * @param type $user_id
     */
    function remove_image($user_id)
    {
        $data = $this->User_model->remove_image($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de usuarios
     * con archivo Excel. El resultado del formulario se envía a 
     * 'users/import_e'
     */
    function import($type = 'clients')
    {
        $data = $this->User_model->import_config($type);

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
        

        $data['head_title'] = 'Usuarios';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de usuarios con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->User_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "users/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Usuarios';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
    
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX
     * Devuelve un valor de username sugerido disponible, dados los nombres y last_name
     */
    function username()
    {
        $first_name = $this->input->post('first_name');
        $last_name = $this->input->post('last_name');
        $username = $this->User_model->generate_username($first_name, $last_name);
        
        $this->output->set_content_type('application/json')->set_output($username);
    }

// LINKS REDES SOCIALES
//-----------------------------------------------------------------------------

    /**
     * URLs de página web y redes sociales del usuario
     * 2020-05-26
     */
    function social_links($user_id)
    {
        $data = $this->User_model->basic($user_id);

        $data['options_link_type'] = $this->Item_model->options('category_id = 44');
        $data['social_links'] = $this->User_model->social_links($user_id);


        $data['view_a'] = $this->views_folder . 'social_links_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Listado de url, link a redes sociales de un usuario
     * 2020-07-13
     */
    function get_social_links($user_id)
    {
        $social_links = $this->User_model->social_links($user_id);
        $data['list'] = $social_links->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar link de usuario en la tabla meta
     */
    function save_social_link($user_id, $meta_id = 0)
    {
        $data = $this->User_model->save_social_link($user_id, $meta_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar conjuntamente social links de un usuario
     * 2020-07-13
     */
    function save_social_links($user_id)
    {
        $data = $this->User_model->save_social_links($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// SEGUIDORES
//-----------------------------------------------------------------------------

    /** Alternar seguir o dejar de seguir un usuario por parte del usuario en sesión */
    function alt_follow($user_id)
    {
        $data = $this->User_model->alt_follow($user_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de usuarios seguidos por usuario en sesión
     * 2020-07-15
     */
    function following($user_id)
    {
        $users = $this->User_model->following($user_id);
        $data['list'] = $users->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Procesos masivos
//-----------------------------------------------------------------------------

    /**
     * Establecer imagen de perfil por defecto al usuario
     * PENDIENTE
     */
    function set_image_profile()
    {
        $this->db->where('role', 23);
        $this->db->where('image_id', 0);
        $users = $this->db->get('users');

        foreach ($users->result() as $row_user)
        {
            $images = $this->User_model->images($row_user->id);
            
            /*if ( $images->num_rows() > 0 )
            {

            }*/
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// NEWSLETTER SUBSCRIBERS
//-----------------------------------------------------------------------------

    /**
     * Listado de E-mails registrados como subscritores de newsletter
     * 2020-07-22
     */
    function newsletter_subscribers()
    {
        //Seleccionar suscriptores
        $this->db->select('posts.id, text_1 AS email, posts.creator_id, users.display_name AS creator_name');
        $this->db->where('posts.type_id', 112);   //Post subscription
        $this->db->order_by('posts.created_at', 'DESC');
        $this->db->join('users', 'users.id = posts.creator_id');
        $data['subscribers'] = $this->db->get('posts');

        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = $this->views_folder . 'newsletter_subscribers_v';
        $data['head_title'] = 'Newsletter subscribers';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// INVITACIONES A USUARIOS
//-----------------------------------------------------------------------------

    /**
     * 
     */
    function invitations($num_page = 1, $per_page = 10)
    {
        $data['head_title'] = 'Invitations';
        $data['view_a'] = $this->views_folder . 'invitations/invitations_v';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['bcc'] = $this->App_model->option_value(201);
        $data['text_message'] = $this->App_model->option_value(202);

        $this->load->model('Search_model');
        $data['filters'] = $this->Search_model->filters();

        $data['num_page'] = $num_page;

        $this->load->model('Notification_model');
        $data['styles'] = $this->Notification_model->email_styles();

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get_users_invitations($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->User_model->get($filters, $num_page, $per_page);

        $users = $data['list'];
        $list = array();

        foreach($users as $user){
            $condition = "type_id = 121 AND user_id = {$user->id}";
            $user->qty_invitations = $this->Db_model->num_rows('events', $condition);
            $list[] = $user;
        }
        
        $data['list'] = $list;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Resumen del estado de invitaciones de activación de cuenta
     * 2021-11-08
     */
    function invitations_summary()
    {
        $data = $this->User_model->invitations_summary();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Enviar mensaje de correo electrónico, invitación a activar y controlar
     * su cuenta de usuario
     * 2021-11-08
     */
    function send_invitation()
    {
        $this->load->model('Notification_model');
        $user_id = $this->input->post('user_id');
        $data = $this->Notification_model->email_invitation($user_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}