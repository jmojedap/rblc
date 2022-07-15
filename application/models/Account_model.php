<?php
class Account_model extends CI_Model{
    
    
    /**
     * Realiza la validación de login, user y password. Valida coincidencia
     * de password, y status del user.
     */
    function validate_login($userlogin, $password)
    {
        $data = array('status' => 0, 'messages' => array());
        $conditions = 0;   //Initial value
        
        //Validation de password (Condición 1)
            $password_validation = $this->validate_password($userlogin, $password);
            $data['messages'][] = $password_validation['message'];

            if ( $password_validation['status'] ) { $conditions++; }
            
        //Verificar que el user esté activo (Condición 2)
            $user_status = $this->user_status($userlogin);
            if ( $user_status['status'] < 1 ) { $data['messages'][] = $user_status['message']; }
            
            if ( $user_status['status'] >= 1 ) { $conditions++; }   //Usuario activo o registrado
            
        //Se valida el login si se cumplen las conditions
        if ( $conditions == 2 ) 
        {
            $data['status'] = 1;    //General login validation
        }
            
        return $data;
    }
    

    //Check if the user has cookie to be remembered in his browser
    function login_cookie()
    {
        $this->load->helper('cookie');
        get_cookie('colibri_sesion');
        $rememberme = $this->input->cookie('colibrisesionrc');

        $condition = "activation_key = '{$rememberme}'";
        $row_user = $this->Db_model->row('users', $condition);

        if ( ! is_null($row_user) && strlen($rememberme) > 0)
        {
            $this->create_sesion($row_user->username, TRUE);
        }    
    }

    /**
     * Guardar evento final de sesión, eliminar cookie y destruir sesión
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
        if ( strlen($this->session->userdata('login_id')) > 0 ) 
        {
            $row_event = $this->Db_model->row_id('events', $this->session->userdata('login_id'));

            $arr_row['end'] = date('Y-m-d H:i:s');
            $arr_row['status'] = 2;    //Cerrado
            $arr_row['seconds'] = $this->pml->seconds($row_event->start, date('Y-m-d H:i:s'));

            if ( ! is_null($row_event) ) 
            {
                //Si el evento existe
                $this->Db_model->save('events', "id = {$row_event->id}", $arr_row);
            }
        }
    
    //Eliminar cookie
        /*$this->load->helper('cookie');
        delete_cookie('colibrisesionrc');*/
        
    //Destruir sesión existente
        $this->session->sess_destroy();
    }
    
// SESSION CONSTRUCT
//-----------------------------------------------------------------------------
    
    /**
     * Crea sesión de usuario $username
     */
    function create_session($username, $register_login = TRUE)
    {
        $data = $this->session_data($username);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ( $register_login )
        {
            $this->load->model('Event_model');
            $this->Event_model->save_ev_login();
        }
        
        //Actualizar users.ultimo_login
            $this->update_last_login($username);
        
        //Si el user solicitó ser recordardo en el equipo
            //if ( $this->input->post('rememberme') ) { $this->rememberme(); }
    }
    
    /**
     * Actualiza el campo users.last_login, con la última fecha en la que el usuario
     * hizo login
     */
    function update_last_login($username)
    {
        $arr_row['last_login'] = date('Y-m-d H:i:s');

        $this->db->where('username', $username);
        $this->db->update('users', $arr_row);
    }

    /**
     * Array con datos de sesión.
     * 2019-06-23
     */
    function session_data($username)
    {
        $this->load->helper('text');
        $row_user = $this->Db_model->row('users', "username = '{$username}' OR email='{$username}' OR id_number='{$username}'");

        //$data general
            $data = array(
                'logged' =>   TRUE,
                'username'    =>  $row_user->username,
                'display_name'    =>  $row_user->display_name,
                'first_name'    =>  $row_user->first_name,
                'user_id'    =>  $row_user->id,
                'role'    => $row_user->role,
                'role_abbr'    => $this->Db_model->field('items', "category_id = 58 AND cod = {$row_user->role}", 'abbreviation'),
                'last_login'    => $row_user->last_login,
                'picture' => $row_user->url_thumbnail
            );
                
        //Datos específicos para la aplicación
            $app_session_data = $this->App_model->app_session_data($row_user);
            $data = array_merge($data, $app_session_data);
        
        //Devolver array
            return $data;
    }

// REGISTER VALIDATION
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un user nuevo o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     * 2020-07-17
     */
    function validate_form($user_id = NULL)
    {
        $data['status'] = 1;
        
        $this->load->model('Validation_model');
        $email_validation = $this->Validation_model->email($user_id);
        $username_validation = $this->Validation_model->username($user_id);

        $validation = array_merge($email_validation, $username_validation);
        $data['validation'] = $validation;

        //Verificar cada condición
        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) { $data['status'] = 0; } //Si algún valor es false, todo se invalida
        }

        return $data;
    }

    /* Esta función genera un string con el username para un registro en la tabla user
    * Se forma: la primera letra del primer nombre + la primera letra del segundo nombre +
    * el primer apellido + la primera letra del segundo apellido.
    * Se verifica que el username construido no exista
    */
    function generate_username()
    {
        
        $this->load->model('User_model');
        
        //Sin espacios iniciales o finales
        $first_name = trim($this->input->post('first_name'));
        $last_name = trim($this->input->post('last_name'));
        
        //Without accents
        $this->load->helper('text');
        $first_name = convert_accented_characters($first_name);
        $last_name = convert_accented_characters($last_name);
        
        $arr_last_name = explode(" ", $last_name);
        $arr_first_name =  explode(" ", $first_name);
        
        //Construyendo por partes
            $username = $arr_first_name[0];
            if ( isset($arr_first_name[1]) ){ $username .= substr($arr_first_name[1], 0, 2); }
            
            $username .= '.' . $arr_last_name[0];
            
            if ( isset($arr_last_name[1]) ){
                $username .= substr($arr_last_name[1], 0, 2);
            }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un sufix numérico para hacerlo único
            $sufix = $this->username_sufix($username);
            $username .= $sufix;
        
        return $username;
    }

// ACTIVACIÓN DE CUENTA
//-----------------------------------------------------------------------------

    /**
     * Establece un código de activación o restauración de contraseña (users.activation_key)
     * 2020-07-20
     */
    function activation_key($user_id)
    {
        $this->load->helper('string');
        $arr_row['activation_key'] = strtolower(random_string('alpha', 12));
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $arr_row);

        return $arr_row['activation_key'];
    }

    /**
     * Activa una cuenta de usuario actualizando el campo users.status = 1
     * 2020-07-20
     */
    function activate($activation_key)
    {
        $row_user = $this->Db_model->row('users', "activation_key = '{$activation_key}'");

        if ( ! is_null($row_user) )
        {
            //Update
                $this->db->where('id', $row_user->id);
                $this->db->update('users', array('status' => 1));

            //Crear sesión de usuario
                $this->create_session($row_user->email);
        }
            
        return $row_user;
    }

// PASSWORDS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Encripta y cambia la contraseña de un usuario, status 1 => Activo
     * 2020-08-21
     */
    function change_password($user_id, $password)
    {
        $arr_row = array(
            'status' => 1,           //Activar usuario
            'activation_key' => '',  //Quitar clave de activación reciente
            'password'  => $this->crypt_pw($password)
        );
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $arr_row);
    }

    /**
     * Verificar la contraseña de de un users. Verifica que la combinación de
     * user y contraseña existan en un mismo registro en la tabla users.
     * 
     */
    function validate_password($userlogin, $password)
    {
        //Valor por defecto
            $data['status'] = 0;
            $data['message'] = 'No existe usuario: "' . $userlogin . '"';   
         
        //Buscar user con username o correo electrónico
            $condition = "username = '{$userlogin}' OR email = '{$userlogin}'";
            $row_user = $this->Db_model->row('users', $condition);
        
        if ( ! is_null($row_user) )
        {    
            //Crypting
                $cpw = crypt($password, $row_user->password);
                $pw_compare = $row_user->password;
            
            if ( $pw_compare == $cpw )
            {
                $data['status'] = 1;    //Contraseña válida
                $data['message'] = 'Contraseña válida para el usuario';
            } else {
                $data['message'] = 'Contraseña no válida para el ususario "'. $userlogin .'"' ;
            }
        }
        
        return $data;
    }

    /**
     * Devuelve password encriptado
     */
    function crypt_pw($input, $rounds = 7)
    {
        $salt = '';
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
          $salt .= $salt_chars[array_rand($salt_chars)];
        }
        
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }

    /**
     * Array con: valor del campo users.estado, y un mensaje explicando 
     * el estado
     */
    function user_status($userlogin)
    {
        $data['status'] = 2;     //Valor inicial, 2 => inexistente
        $data['message'] = 'No existe un user identificado con "'. $userlogin .'"';
        
        $this->db->where("username = '{$userlogin}' OR email = '{$userlogin}'");
        $query = $this->db->get('users');
        
        if ( $query->num_rows() > 0 )
        {
            $data['status'] = $query->row()->status;
            $data['message'] = 'Usuario activo';
            
            if ( $data['status'] == 0 ) { $data['message'] = "El user '{$userlogin}' está inactivo, comuníquese con servicio al cliente"; }
        }
        
        return $data;
        
    }

// RECUPERACIÓN DE CUENTA DE USUARIO Y CONTRASEÑA
//---------------------------------------------------------------------------------------------------

    /**
     * Envía un email de para restauración de la contraseña de user
     */
    function recover($email)
    {
        $data = array('status' => 0, 'message' => 'El proceso no fue ejecutado');
        
        //Identificar user
        $row_user = $this->Db_model->row('users', "email = '{$email}'");

        if ( ! is_null($row_user) ) 
        {
            //$this->email_activation($row_user->id, 'recovery');
            $data = array('status' => 1, 'message' => 'El mensaje de correo electrónico fue enviado');
        } else {
            $data['status'] = 2;    //Usuario inexistente
            $data['message'] = "No existe ningún user con el correo electrónico: '{$email}'";
        }
        
        return $data;
    }

    /**
     * Envía e-mail de activación o restauración de cuenta
     * 
     */
    function email_activation($user_id, $activation_type = 'activation')
    {
        $row_user = $this->Db_model->row_id('users', $user_id);
        
        //Establecer código de activación
            $this->activation_key($user_id);
            
        //Asunto de mensaje
            $subject = APP_NAME . ': Activate your account';
            if ( $activation_type == 'recovery' ) {
                $subject = APP_NAME . ' Recover your account';
            }
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->from('accounts@' . APP_DOMAIN, APP_NAME);
            $this->email->to($row_user->email);
            $this->email->message($this->activation_message($user_id, $activation_type));
            $this->email->subject($subject);
            
            $this->email->send();   //Enviar
    }

    /**
     * Devuelve texto de la vista que se envía por email a un usuario para 
     * activación o restauración de su cuenta
     */
    function activation_message($user_id, $activation_type = 'activation')
    {
        $row_user = $this->Db_model->row_id('users', $user_id);
        $data['row_user'] = $row_user ;
        $data['activation_type'] = $activation_type;
        $data['view_a'] = 'admin/accounts/email_activation_v';

        $this->load->model('Notification_model');
        $data['styles'] = $this->Notification_model->email_styles();
        
        $message = $this->load->view('templates/email/main', $data, TRUE);
        
        return $message;
    }

// ESPECIAL APP
//-----------------------------------------------------------------------------

    /**
     * Guardar información de cuando un usuario cierra su cuenta.
     * 2022-07-14
     */
    function save_cancel_account_info($row_user)
    {
        $arr_row['type_id'] = 15;
        $arr_row['post_name'] = 'Delete Info: ' . $row_user->display_name;
        $arr_row['content'] = $this->input->post('description');
        $arr_row['content_json'] = json_encode($row_user);
        $arr_row['related_1'] = $row_user->id;
        $arr_row['text_1'] = $this->input->post('delete_reason');
        $arr_row['text_2'] = $this->input->post('activation_key');
        $arr_row['created_at'] = date('Y-m-d H:i:s');
        $arr_row['updated_at'] = date('Y-m-d H:i:s');
        $arr_row['updater_id'] = 200001;
        $arr_row['creator_id'] = 200001;
        
        $saved_id = $this->Db_model->save_id('posts', $arr_row);
    
        return $saved_id;
    }
    
// LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Prepara un objeto Google_Client, para solicitar la autorización  de una
     * autenticación de un user de google y obtener información de su cuenta
     * 
     * Credenciales de Cliente para la aplicación, creadas con 
     * la cuenta google pacarinamedialab@gmail.com
     * 
     * @return \Google_Client
     */
    public function g_client()
    {
        //require 'vendor/autoload.php';

        $g_client = new Google_Client();
        $g_client->setClientId(K_GCI);
        $g_client->setClientSecret(K_GCS);
        $g_client->setApplicationName(APP_NAME);
        $g_client->setRedirectUri(base_url() . 'accounts/g_callback');
        $g_client->setScopes('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');
        
        return $g_client;
    }
    
    /**
     * Teniendo como entrada el objeto Google_Cliente autorizado, se solicita
     * y se obtiene la información de la cuenta de un user mediante 
     * Google_Service_Oauth2 y se carga en el array g_userinfo
     * 
     */
    public function g_userinfo($g_client)
    {
        $oAuth = new Google_Service_Oauth2($g_client);
        $g_userinfo = $oAuth->userinfo_v2_me->get();
        
        return $g_userinfo;
    }

    function g_register($g_userinfo)
    {
        //Construir registro del nuevo user
            $arr_row['first_name'] = $g_userinfo['given_name'];
            $arr_row['last_name'] = $g_userinfo['family_name'];
            $arr_row['display_name'] = $g_userinfo['given_name'] . ' ' . $g_userinfo['family_name'];
            $arr_row['username'] = str_replace('@gmail.com', '', $g_userinfo['email']);
            $arr_row['email'] = $g_userinfo['email'];
            $arr_row['role'] = 31;         //31 Registrado
            $arr_row['status'] = 1;        //Activo

        //Insert in table "user"
            $this->load->model('User_model');
            $data = $this->User_model->insert($arr_row);

        //Create user session
            if ( $data['user_id'] > 0 )
            {
                $this->create_session($arr_row['username']);
                //$this->Account_model->g_save_account($result['user_id']);
            }

        return $data;
    }

// LOGIN Y REGISTRO CON CUENTA DE USUARIO DE FACEBOOK
//-----------------------------------------------------------------------------

    /**
     * Valida si un user access token recibido es válido, se verifica teniendo en cuenta
     * un app_access_token generado inicialmente.
     * 2020-08-14
     */
    function facebook_validate_token($input_token)
    {
        $app_access_token = $this->facebook_get_app_access_token();
        $url = "https://graph.facebook.com/debug_token?input_token={$input_token}&access_token={$app_access_token}";    

        $content = $this->pml->get_url_content($url);
        $token_validation = json_decode($content);

        return $token_validation->data;
    }

    /**
     * String, Genera un app_access_token de la Aplicación de Facebook asociada esta WebApp
     * 2020-08-14
     */
    function facebook_get_app_access_token()
    {
        $client_id = K_FBAI;        //Ver config/constants.php
        $client_secret = K_FBAK;    //Ver config/constants.php
        $url = "https://graph.facebook.com/oauth/access_token?client_id={$client_id}&client_secret={$client_secret}&grant_type=client_credentials";

        $str_content = $this->pml->get_url_content($url);   //Recibe JSON
        $content = json_decode($str_content);               //Se convierte en un objeto 

        //devuelve solo el string de access_token
        return $content->access_token;
    }

    /**
     * Tras validar el token de usuario de facebook, se realiza el login de usuario con esa cuenta
     * Se verifica si el email, ya está registrado, y se inicia sesión.
     * Si no existe, se crea usuario con los datos recibidos y se inicia sesión.
     * 2020-08-14
     */
    function facebook_set_login()
    {
        $data['status'] = 0;    //Resultado inicial por defecto        

        //Verificar si existe usuario con ese e-mail
        $row_user = $this->Db_model->row('users', "email = '{$this->input->post('email')}'");

        //Create session or insert new user
            if ( ! is_null($row_user) )
            {
                $data['status'] = 1;
                $data['message'] = 'Ya estás registrado: ' . $row_user->first_name;
                $this->create_session($row_user->username);
            } else {
                //Do not exists, insert new user
                $data = $this->Account_model->facebook_register();
            }
        
        return $data;
    }

    function facebook_register()
    {
        //Construir registro del nuevo user
            $arr_row['first_name'] = $this->input->post('first_name');
            $arr_row['last_name'] = $this->input->post('last_name');
            $arr_row['display_name'] = $arr_row['first_name'] . ' ' . $arr_row['last_name'];
            $arr_row['username'] = explode('@', $this->input->post('email'))[0] . rand(10,99);
            $arr_row['email'] = $this->input->post('email');
            $arr_row['role'] = ( $this->input->post('role_type') == 'professional' ) ? 13 : 23;
            $arr_row['status'] = 1;        //Activo
            $arr_row['linked_accounts'] = "[{'facebook': '{$this->input->post('account_id')}'}]";
            $arr_row['creator_id'] = 200001;
            $arr_row['updater_id'] = 200001;

        //Insert in table "user"
            $this->load->model('User_model');
            $data = $this->User_model->insert($arr_row);

        //Create user session
            if ( $data['saved_id'] > 0 )
            {
                $this->create_session($arr_row['username']);
            }

        return $data;
    }
}