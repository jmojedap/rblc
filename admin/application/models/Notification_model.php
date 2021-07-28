<?php
class Notification_model extends CI_Model{

    /**
     * Array con estilos CSS para mensajes de correo electrónico
     * 2021-07-26
     */
    function email_styles()
    {
        $email_styles = file_get_contents(URL_RESOURCES . 'css/email.json');
        $styles = json_decode($email_styles, true);

        return $styles;
    }
    
// Notificación following
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail notificando a un usuario que tiene un nuevo seguidor
     * 2021-07-27
     */
    function email_new_follower($user_id, $meta_id)
    {
        if ( ENV == 'production' )
        {
            
        //Variables
            $user = $this->Db_model->row_id('users', $user_id);
        
        //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->subject('You have a new follower');
            $this->email->from('info@' . APP_DOMAIN, APP_NAME);
            $this->email->to($user->email);
            $this->email->message($this->new_follower_message($user_id, $meta_id));
            
            $this->email->send();   //Enviar
        }
    }

    /**
     * Devuelve la vista con el mensaje de email para notificar nuevo seguidor
     * 2021-07-27
     */
    function new_follower_message($user_id, $meta_id)
    {
        $user = $this->Db_model->row_id('users', $user_id);
        $following = $this->Db_model->row_id('users_meta', $meta_id);

        //Usuarios relacionados
        $data['user'] = $user;
        $data['follower'] = $this->Db_model->row_id('users', $following->related_1);
        
        $message = $this->load->view('notifications/email_new_follower_v', $data, TRUE);
        
        return $message;
    }

// Notificación new message
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail notificando a un usuario que tiene un nuevo mensaje
     * 2021-07-27
     */
    function email_new_message($user_id, $message_id)
    {
        if ( ENV == 'production' )
        {   
            //Variables
            $user = $this->Db_model->row_id('users', $user_id);
        
            //Enviar Email
            $this->load->library('email');
            $config['mailtype'] = 'html';

            $this->email->initialize($config);
            $this->email->subject('You have a new message in ' . APP_NAME);
            $this->email->from('info@' . APP_DOMAIN, APP_NAME);
            $this->email->to($user->email);
            $this->email->message($this->new_message_message($user_id, $message_id));
            
            $this->email->send();   //Enviar
        }
    }

    /**
     * Devuelve la vista con el mensaje de email para notificar nuevo mensaje interno
     * 2021-07-27
     */
    function new_message_message($user_id, $message_id)
    {
        $user = $this->Db_model->row_id('users', $user_id);
        $row_message = $this->Db_model->row_id('messages', $message_id);

        //Variables para vista
        $data['user'] = $user;
        $data['sender'] = $this->Db_model->row_id('users', $row_message->user_id);
        $data['row_message'] = $row_message;
        
        $message = $this->load->view('notifications/email_new_message_v', $data, TRUE);
        
        return $message;
    }
}