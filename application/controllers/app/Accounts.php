<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'app/accounts/';
public $url_controller = URL_APP . 'accounts/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('User_model');
        $this->load->model('Account_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app/accounts/logged');
        } else {
            redirect('app/accounts/login');
        }    
    }
    
//LOGIN
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form login de users se ingresa con nombre de user y 
     * contraseña. Los datos se envían vía ajax a accounts/validate_login
     */
    function login()
    {
        //Verificar si es recordado en el equipo
            //$this->Account_model->login_cookie();
        
        //Verificar si está logueado
            if ( $this->session->userdata('logged') )
            {
                redirect('app/accounts/logged');
            } else {
                $data['head_title'] = APP_NAME;
                $data['view_a'] = 'templates/colibri_blue/login';
                //$data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
                $this->load->view('templates/colibri_blue/start', $data);
            }
    }
    
    /**
     * Destroy session and redirect to login, start.
     */
    function logout()
    {
        $this->Account_model->logout();
        redirect('app/accounts/login');
    }

    /**
     * Destinos a los que se redirige después de validar el login de usuario
     * según el rol de usuario (índice del array)
     */
    function logged()
    {
        $destination = 'accounts/login';
        if ( $this->session->userdata('logged') )
        {
            $arr_destination = array(
                1 => 'app/pictures/explore/',   //Developer
                2 => 'app/pictures/explore/',   //Administrador
                3 => 'app/pictures/explore/',   //Editor
                13 => 'app/accounts/edit/',     //Professional
                23 => 'app/accounts/edit/',     //Homeowner
            );
                
            $destination = $arr_destination[$this->session->userdata('role')];
        }
        
        redirect($destination);
    }
    
//USERS REGISTRATION
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form de registro de nuevos users en el sistema, se envían los
     * datos a accounts/register
     */
    function signup($with_email = FALSE)
    {
        $data['head_title'] = 'Create an account in ' . APP_NAME ;
        $data['view_a'] = $this->views_folder . 'signup/signup_v';
        $data['with_email'] = $with_email;
        //$data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php

        $data['options_cat_1'] = $this->Item_model->options("category_id = 720 AND level = 0");
        $data['items_cat_2'] = $this->Item_model->get_items("category_id = 720 AND level = 1");

        $this->load->view(TPL_FRONTD, $data);
    }

    /**
     * WEB VIEW
     * User signup confirmation message
     */
    function registered($user_id)
    {   
        //Solicitar vista
        $data['head_title'] = 'Account created';
        $data['row'] = $this->Db_model->row_id('users', $user_id);
        $data['view_a'] = $this->views_folder . 'registered_v';
        $this->load->view(TPL_FRONT, $data);
    }

// ACTIVATION
//-----------------------------------------------------------------------------

    function activation($activation_key = '')
    {
        $data['head_title'] = 'Account activation';
        $data['activation_key'] = $activation_key;
        $data['view_a'] = 'app/accounts/activation_v';

        $this->App_model->view('templates/colibri_blue/start', $data);
    }

// ACTUALIZACIÓN DE DATOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Se validan los datos del usuario en sesión, los datos deben cumplir varios criterios
     */
    function validate($type = 'general')
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('Account_model');
        $result = $this->Account_model->validate($user_id, $type);
        
        //Enviar result
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

//RECUPERACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para restaurar contraseña o reactivar cuenta
     * se ingresa con nombre de usuario y contraseña
     */
    function recovery()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app/accounts/logged');
        } else {
            $data['head_title'] = 'Accounts recovery';
            $data['view_a'] = 'app/accounts/recovery_v';
            $this->load->view('templates/colibri_blue/start', $data);
        }
    }

    /**
     * Formulario para reestablecer contraseña
     * 2020-08-21
     */
    function recover($activation_key)
    {
        //Valores por defecto
            $data['head_title'] = 'Unidentified user';
            $data['user_id'] = 0;
    
        //Variables
            $row_user = $this->Db_model->row('users', "activation_key = '{$activation_key}'");        
            $data['activation_key'] = $activation_key;
            $data['row'] = $row_user;
        
        //Verificar que usuario haya sido identificado
            if ( ! is_null($row_user) ) 
            {
                $data['head_title'] = $row_user->display_name;
                $data['user_id'] = $row_user->id;
            }

        //Verificar que no tenga sesión iniciada
            if ( $this->session->userdata('logged') ) redirect('app/accounts/logged');

        //Cargar vista
            $data['view_a'] = 'app/accounts/recover_v';
            $this->load->view('templates/colibri_blue/start', $data);
    }

// ADMINISTRACIÓN DE CUENTA
//-----------------------------------------------------------------------------

    /** Perfil del usuario en sesión */
    function profile()
    {        
        $this->load->model('User_model');
        $data = $this->User_model->basic($this->session->userdata('user_id'));
        
        //Variables específicas
        $data['view_a'] = $this->views_folder . 'profile_v';
        
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Formulario para la edición de los datos del usuario en sessión. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($section = 'basic')
    {
        $this->load->model('User_model');
        $data = $this->User_model->basic($this->session->userdata('user_id'));

        if ( $data['row']->role == 13 && $section == 'basic' ) { redirect('app/accounts/edit/business_profile'); }
        
        $view_a = $this->views_folder . "edit/{$section}_v";
        if ( $section == 'cropping' )
        {
            $view_a = 'admin/files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = $data['row']->url_image;
            $data['back_destination'] = 'accounts/edit/image';
        }
        if ( $section == 'images' )
        {
            $view_a = 'app/professionals/images/images_v';
        }

        //Options form
            $data['options_gender'] = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Gender');
            $data['options_cat_1'] = $this->Item_model->options("category_id = 720 AND level = 0", 'Category');
            $data['items_cat_2'] = $this->Item_model->get_items("category_id = 720 AND level = 1");
        
        //Array data espefícicas
            $data['nav_2'] = $this->views_folder. 'edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_FRONT, $data);
    }

// ELIMINACIÓN DE LA CUENTA
//-----------------------------------------------------------------------------

    /**
     * Formulario para realizar eliminación de la cuenta de usuario
     * 2022-07-14
     */
    function delete($activation_key)
    {
        //Valores por defecto
            $data['head_title'] = 'Unidentified user';
            $data['user_id'] = 0;
    
        //Variables
            $row_user = $this->Db_model->row('users', "activation_key = '{$activation_key}'");        
            $data['activation_key'] = $activation_key;
            $data['row'] = $row_user;
        
        //Verificar que usuario haya sido identificado
            if ( ! is_null($row_user) ) 
            {
                $data['head_title'] = 'Delete account: ' . $row_user->display_name;
                $data['user_id'] = $row_user->id;
            }

        //Verificar que no tenga sesión iniciada
            //if ( $this->session->userdata('logged') ) redirect('app/accounts/logged');

        //Cargar vista
            $data['view_a'] = 'app/accounts/delete_v';
            $this->load->view('templates/colibri_blue/start', $data);
    }
    
// USER LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Google Callback, recibe los datos después de solicitar autorización de
     * acceso a cuenta de Google de user.
     */
    function g_callback()
    {
        $g_client = $this->Account_model->g_client();
        
        $cf_redirect = 'accounts/login';
        
        if ( ! is_null($this->session->userdata('access_token')) )
        {
            //access_token existe, set in g_client
            $g_client->setAccessToken($this->session->userdata('access_token'));
        } else if ( $this->input->get('code') ) {
            //Google redirect to URL app/g_callback with GET variable (in URL) called 'code'
            $g_client->authenticate($this->input->get('code')); //Autenticate with this 'code'
            $access_token = $g_client->getAccessToken();        //
            $this->session->set_userdata('access_token', $access_token);
        }
        
        //Get data from the account
            $g_userinfo = $this->Account_model->g_userinfo($g_client);
        
        //Check if email already exists in the BD
            $row_user = $this->Db_model->row('user', "email = '{$g_userinfo['email']}'");

        //Create session or insert new user
            if ( ! is_null($row_user) )
            {
                $this->Account_model->create_session($row_user->username);
                $this->session->set_userdata('picture', $g_userinfo['picture']);
                $cf_redirect = 'app/accounts/logged';
            } else {
                //Do not exists, insert new user
                $this->Account_model->g_register($g_userinfo);
            }
        
        redirect($cf_redirect);
    }
    
    function g_signup()
    {
        redirect('app/accounts/login');
    }

// LOGIN WITH FACEBOOK
//-----------------------------------------------------------------------------

    function fb_login()
    {
        $this->load->view('accounts/fb_login_v');
    }
}