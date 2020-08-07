<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends CI_Controller {
    
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
            redirect('app/logged');
        } else {
            redirect('accounts/login');
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
                redirect('app/logged');
            } else {
                $data['head_title'] = APP_NAME;
                $data['view_a'] = 'accounts/login_v';
                //$data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
                $this->load->view('templates/admin_pml/start_v', $data);
            }
    }

    function validate_login()
    {
        //Setting variables
            $userlogin = $this->input->post('username');
            $password = $this->input->post('password');
            
            $data = $this->Account_model->validate_login($userlogin, $password);
            
            if ( $data['status'] )
            {
                $this->Account_model->create_session($userlogin, TRUE);
            }
            
        //Salida
            $this->output->set_content_type('application/json')->set_output(json_encode($data));      
    }
    
    /**
     * Destroy session and redirect to login, start.
     */
    function logout()
    {
        $this->Account_model->logout();
        redirect('accounts/login');
    }

    //ML Master Login, 
    function ml($user_id)
    {
        $username = $this->Db_model->field_id('user', $user_id, 'username');
        if ( $this->session->userdata('role') <= 1 ) { $this->Account_model->create_session($username, FALSE); }
        
        redirect('app/logged');
    }
    
//USERS REGISTRATION
//---------------------------------------------------------------------------------------------------
    
    /**
     * Form de registro de nuevos users en el sistema, se envían los
     * datos a accounts/register
     */
    function signup($with_email = FALSE)
    {
        $data['head_title'] = 'Crear tu cuenta de ' . APP_NAME ;
        $data['view_a'] = 'accounts/signup_v';
        $data['with_email'] = $with_email;
        $data['g_client'] = $this->Account_model->g_client(); //Para botón login con Google
        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
        $this->load->view('templates/admin_pml/start_v', $data);
    }
    
    /**
     * AJAX JSON
     * 
     * Recibe los datos POST del form en accounts/signup. Si se validan los 
     * datos, se registra el user. Se devuelve $data, con resultados de registro
     * o de validación (si falló).
     * 2019-11-27
     */
    function register($role_type = 'homeowner')
    {
        $data = array('status' => 0, 'message' => 'Not created', 'recaptcha_valid' => FALSE);  //Initial result values
        $res_validation = $this->Account_model->validate_form();
        $this->load->model('Validation_model');
        $recaptcha = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3
            
        if ( $res_validation['status'] && $recaptcha->score > 0.5 )
        {
            //Construir registro del nuevo user
                $arr_row['first_name'] = $this->input->post('first_name');
                $arr_row['last_name'] = $this->input->post('last_name');
                $arr_row['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
                $arr_row['email'] = $this->input->post('email');
                $arr_row['username'] = explode('@', $this->input->post('email'))[0] . rand(10,99);
                $arr_row['password'] = $this->Account_model->crypt_pw($this->input->post('new_password'));
                $arr_row['status'] = 2;     //Registrado sin confirmar email
                $arr_row['role'] = ( $role_type == 'professional' ) ? 13 : 23;

            //Insert user
                $this->load->model('User_model');
                $data = $this->User_model->insert($arr_row);
                
            //Enviar email con código de activación
                $this->Account_model->activation_key($data['saved_id']);
                $this->Account_model->email_activation($data['saved_id']);

            //Respuesta validación recaptcha
                $data['recaptcha_valid'] = TRUE; 
        } else {
            $data['validation'] = $res_validation['validation'];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Vista previa del mensaje de email, para activación o restauración de cuenta de usuario
     * 2020-07-20
     */
    function test_message($user_id, $activation_type = 'activation')
    {
        $activation_message = $this->Account_model->activation_message($user_id, $activation_type); 
        echo $activation_message;
    }

    /**
     * AJAX JSON
     * Validation of form signup user data
     */
    function validate_signup()
    {
        $data = $this->Account_model->validate();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * WEB VIEW
     * User signup confirmation message
     */
    function registered($user_id)
    {   
        //Solicitar vista
        $data['head_title'] = 'Usuario registrado';
        $data['row'] = $this->Db_model->row_id('user', $user_id);
        $data['view_a'] = 'accounts/registered_v';
        $this->load->view('templates/bootstrap/start_v', $data);
    }

    /**
     * Verificar si un email ya está registrado para una cuenta de usuario
     */
    function check_email()
    {
        $data = array('status' => 0, 'user' => array());

        $row = $this->Db_model->row('user', "email = '{$this->input->post('email')}'");

        if ( ! is_null($row))
        {
            $data['status'] = 1;
            $data['user']['firts_name'] = $row->first_name;
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ACTIVATION
//-----------------------------------------------------------------------------

    /**
     * Vista del resultado de activación de cuenta de usuario
     */
    function activation($activation_key = '')
    {
        $data['head_title'] = 'Activación de cuenta';
        $data['activation_key'] = $activation_key;
        $data['view_a'] = 'accounts/activation_v';

        $this->App_model->view('templates/admin_pml/start_v', $data);
    }

    /**
     * Ejecuta la activación de una cuenta de usuario ($activation_key)
     * 2020-07-20
     */
    function activate($activation_key)
    {
        $data = array('status' => 0, 'user_id' => 0, 'display_name' => '');
        $row_user = $this->Account_model->activate($activation_key);
        
        if ( ! is_null( $row_user ) )
        {
            $data['status'] = 1;
            $data['user_id'] = $row_user->id;
            $data['display_name'] = $row_user->display_name;
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

//RECUPERACIÓN DE CUENTAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Formulario para solicitar restaurar contraseña, se solicita email o nombre de usuario
     * Se genera user.activation_key, y se envía mensaje de correo eletrónico con link
     * para asignar nueva contraseña
     * 2020-07-20
     */
    function recovery()
    {
        if ( $this->session->userdata('logged') )
        {
            redirect('app');
        } else {
            $data['head_title'] = 'Accounts recovery';
            $data['view_a'] = 'accounts/recovery_v';
            $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php
            $this->load->view(TPL_FRONT, $data);
        }
    }

    /**
     * Recibe email por post, y si encuentra usuario, envía mensaje
     * para restaurar contraseña
     * 2020-08-06
     */
    function recovery_email()
    {
        $data = ['status' => 0, 'recaptcha_valid' => FALSE];

        $this->load->model('Validation_model');
        $recaptcha = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3

        //Identificar usuario
        $row = $this->Db_model->row('user', "email = '{$this->input->post('email')}'");

        if ( ! is_null($row) && $recaptcha->score > 0.5 ) 
        {
            //Usuario existe, se envía email para restaurar constraseña
            $this->Account_model->activation_key($row->id);
            $this->Account_model->email_activation($row->id, 'recovery');
            $data = ['status' => 1, 'recaptcha_valid' => TRUE];
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Formulario para reestablecer contraseña, se solicita nueva contraseña y confirmación
     */
    function recover($activation_key)
    {
        $row_user = $this->Db_model->row('user', "activation_key = '{$activation_key}'");        
        
        //Variables
            $data['activation_key'] = $activation_key;
            $data['row'] = $row_user;
            $data['view_a'] = 'accounts/recover_v';
            
        //Evaluar condiciones
            $conditions = 0;
            if ( ! is_null($row_user) ) { $conditions++; }
            if ( $this->session->userdata('logged') != TRUE ) { $conditions++; }
        
        if ( $conditions == 2 ) 
        {
            $data['head_title'] = $row_user->display_name;
            $this->load->view('templates/admin_pml/start_v', $data);
        } else {
            redirect('app/denied');
        }
    }

    /**
     * Recibe datos de POST y establece nueva contraseña a un usuario asociado a la $activation_key
     * 2020-07-20
     */
    function reset_password($activation_key)
    {
        $data = array('status' => 0, 'message' => 'Password no changed');
        $row_user = $this->Db_model->row('user', "activation_key = '{$activation_key}'");        
        
        $conditions = 0;
        if ( $this->input->post('password') == $this->input->post('passconf') ) { $conditions++; }  //Contraseñas coinciden
        if ( strlen($this->input->post('password')) > 0 ) { $conditions++; }                        //Hay contraseña escrita
        if ( ! is_null($row_user) ) { $conditions++; }                                              //Usuario existente
        
        if ( $conditions == 3 ) 
        {
            $this->Account_model->change_password($row_user->id, $this->input->post('password'));
            $this->Account_model->create_session($row_user->username, 1);
            
            $data['status'] = 1;
            $data['message'] = 'Password changed for user: ' . $row_user->username;
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
        $result = $this->Account_model->validate_form($user_id, $type);
        
        //Enviar result
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Actualiza los datos del usuario en sesión.
     * 2020-07-17
     */
    function update()
    {
        $arr_row = $this->input->post();
        if ( is_null($this->input->post('display_name')) )
        {
            $arr_row['display_name'] = $arr_row['first_name'] . ' ' . $arr_row['last_name'];
        }
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->update($user_id, $arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Ejecuta el proceso de cambio de contraseña de un usuario en sesión
     */
    function change_password()
    {
        $conditions = 0;
        $row_user = $this->Db_model->row_id('user', $this->session->userdata('user_id'));
        
        //Valores iniciales para el resultado del proceso
            $data = array('status' => 0, 'message' => 'The password was not changed. ');
        
        //Verificar contraseña actual
            $validar_pw = $this->Account_model->validate_password($row_user->username, $this->input->post('current_password'));
            if ( $validar_pw['status'] ) {
                $conditions++;
            } else {
                $data['message'] = 'The current password is incorrect. ';
            }
        
        //Verificar que contraseña nueva coincida con la confirmación
            if ( $this->input->post('password') == $this->input->post('passconf') ) {
                $conditions++;
            } else {
                $data['message'] .= 'Confirmation password does not match.';
            }
        
        //Verificar condiciones necesarias
            if ( $conditions == 2 )
            {
                $this->Account_model->change_password($row_user->id, $this->input->post('password'));
                $data['status'] = 1;
                $data['message'] = 'The password was successfully changed.';
            }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));   
    }

    /**
     * Guardar links sociales de usuario en sesión
     * 2020-07-13
     */
    function save_social_links()
    {
        $data = $this->User_model->save_social_links($this->session->userdata('user_id'));

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ADMINISTRACIÓN DE CUENTA
//-----------------------------------------------------------------------------

    /** Perfil del usuario en sesión */
    function profile()
    {        
        $this->load->model('User_model');
        $data = $this->User_model->basic($this->session->userdata('user_id'));
        
        //Variables específicas
        $data['nav_2'] = 'accounts/menu_v';
        $data['view_a'] = 'accounts/profile_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición de los datos del usuario en sessión. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($section = 'basic')
    {
        //Datos básicos
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);
        
        $view_a = "accounts/edit/{$section}_v";
        if ( $section == 'crop' )
        {
            $view_a = 'files/cropping_v';
            $data['image_id'] = $data['row']->image_id;
            $data['url_image'] = $data['row']->url_image;
            $data['back_destination'] = "accounts/edit/image";
        }
        
        //Array data espefícicas
            //$data['valores_form'] = $this->Pcrn->valores_form($data['row'], 'user');
            $data['nav_2'] = 'accounts/menu_v';
            $data['nav_3'] = 'accounts/edit/menu_v';
            $data['view_a'] = $view_a;
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

//IMAGEN DE PERFIL
//---------------------------------------------------------------------------------------------------

    /**
     * Carga archivo de imagen, y se la asigna como imagen de perfil al usuario
     * en sesión
     */
    function set_image()
    {
        $user_id = $this->session->userdata('user_id');

        //Cargue
        $this->load->model('File_model');
        $data_upload = $this->File_model->upload();
        
        $data = array('status' => 0);
        if ( $data_upload['status'] )
        {
            $this->load->model('User_model');
            $this->User_model->remove_image($user_id);                                  //Quitar image actual, si tiene una
            $data = $this->User_model->set_image($user_id, $data_upload['row']->id);    //Asignar imagen nueva
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Desasigna y elimina la imagen asociada (si la tiene) al usuario en sesión.
     */
    function remove_image()
    {
        $user_id = $this->session->userdata('user_id');

        $this->load->model('User_model');
        $data = $this->User_model->remove_image($user_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
                $this->session->set_userdata('src_img', $g_userinfo['picture']);
                $cf_redirect = 'app/logged';
            } else {
                //Do not exists, insert new user
                $this->Account_model->g_register($g_userinfo);
            }
        
        redirect($cf_redirect);
    }
    
    function g_signup()
    {
        redirect('accounts/login');
    }

// LOGIN WITH FACEBOOK
//-----------------------------------------------------------------------------

    function fb_login()
    {
        $this->load->view('accounts/fb_login_v');
    }
}