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
     * Contar número de mensajes recibidos por el usuario en esta misma conversación
     * 2021-07-29
     */
    function qty_recent_messages($user_id, $row_message)
    {   
        $mktime = strtotime(date('Y-m-d H:i:s') . ' -24 hours');    //Mensajes enviados en las últimas 24 horas
        $min_date = date('Y-m-d H:i:s', $mktime);           //Fecha y hora hace 24 horas
        $condition = "user_id = {$user_id} AND conversation_id = {$row_message->conversation_id} AND sent_at >= '{$min_date}'";

        $qty_recent_messages = $this->Db_model->num_rows('messages', $condition);

        return $qty_recent_messages;
    }

    /**
     * Envía e-mail notificando a un usuario que tiene un nuevo mensaje
     * 2021-07-27
     */
    function email_new_message($user_id, $message_id)
    {
        if ( ENV == 'production' )
        {   
            //Variables
            $row_user = $this->Db_model->row_id('users', $user_id);
            $row_message = $this->Db_model->row_id('messages', $message_id);

            //Verificar que no haya mensajes recientes
            $qty_recent_messages = $this->qty_recent_messages($user_id, $row_message);

            //Si no hay mensajes recientes se envía notificación
            if ( $qty_recent_messages == 0 ) {
                //Enviar Email
                $this->load->library('email');
                $config['mailtype'] = 'html';
    
                $this->email->initialize($config);
                $this->email->subject('You have a new message in ' . APP_NAME);
                $this->email->from('info@' . APP_DOMAIN, APP_NAME);
                $this->email->to($row_user->email);
                $this->email->message($this->new_message_message($row_user, $row_message));
                
                $this->email->send();   //Enviar
            }
        }
    }

    /**
     * Devuelve la vista con el mensaje de email para notificar nuevo mensaje interno
     * 2021-07-27
     */
    function new_message_message($row_user, $row_message)
    {
        //Variables para vista
        $data['user'] = $row_user;
        $data['sender'] = $this->Db_model->row_id('users', $row_message->user_id);
        $data['row_message'] = $row_message;
        
        $message = $this->load->view('notifications/email_new_message_v', $data, TRUE);
        
        return $message;
    }

// Notificación new comment
//-----------------------------------------------------------------------------

    /**
     * Envía e-mail notificando a un usuario que tiene un nuevo comentario
     * 2021-07-30
     */
    function email_new_comment($comment_id)
    {
        if ( ENV == 'production' )
        {   
            //Variables
            $row_comment = $this->Db_model->row_id('comments', $comment_id);
            $table_name = $this->Db_model->field_id('sis_table', $row_comment->table_id, 'table_name');
            $row_element = $this->Db_model->row_id($table_name, $row_comment->element_id);
            $row_user = $this->Db_model->row_id('users', $row_element->creator_id);

            if ( ! is_null($row_user) )
            {
                //Enviar Email
                $this->load->library('email');
                $config['mailtype'] = 'html';
    
                $this->email->initialize($config);
                $this->email->subject('You have a new comment in ' . APP_NAME);
                $this->email->from('info@' . APP_DOMAIN, APP_NAME);
                $this->email->to($row_user->email);
                $this->email->message($this->new_comment_message($row_comment));
                
                $this->email->send();   //Enviar
            }

        }
    }

    /**
     * Devuelve la vista con el mensaje de email para notificar nuevo comentario
     * 2021-07-30
     */
    function new_comment_message($row_comment)
    {
        //Variables para vista
        $data['comment'] = $row_comment;
        $data['creator'] = $this->Db_model->row_id('users', $row_comment->creator_id);
        
        $message = $this->load->view('notifications/email_new_comment_v', $data, TRUE);
        
        return $message;
    }

// Notificaciones alerta
//-----------------------------------------------------------------------------

    /**
     * Formato base de row, para crear alerta de notificación en tabla events
     * 2021-08-19
     */
    function arr_row_alert($user_id, $alert_type)
    {
        $arr_row['type_id'] = 111;  //Alerta de notificación
        $arr_row['start'] = date('Y-m-d H:i:s');
        $arr_row['status'] = 2;     //No leída
        $arr_row['user_id'] = $user_id;
        $arr_row['related_3'] = $alert_type;  //Tipo de alerta de notificación, new_follower

        return $arr_row;
    }

    /**
     * Guarda registro de alerta de notificación (tipo 111) en la tabla events asociada
     * al recibir un nuevo seguidor (alerta tipo 10)
     * 2021-08-18
     */
    function save_new_follower_alert($user_id, $meta_id)
    {
        $user = $this->Db_model->row_id('users', $user_id);
        $following = $this->Db_model->row_id('users_meta', $meta_id);
        $follower = $this->Db_model->row_id('users', $following->related_1);    //related_1 => follower_id

        //Preparar registro para events
        $arr_row = $this->arr_row_alert($user_id, 10);
        $arr_row['content'] = "{$follower->display_name} has started to follow you";
        $arr_row['element_id'] = $follower->id;

        $this->load->model('Event_model');
        $alert_id = $this->Event_model->save($arr_row, "start = '{$arr_row['start']}'");

        return $alert_id;
    }

    /**
     * Guarda registro de alerta de notificación (tipo 111) en la tabla events asociada
     * al recibir mensaje reciente (alert_type 20)
     * 2021-08-18
     */
    function save_recent_message_alert($user_id, $message_id)
    {
        //Resultado por defecto
        $alert_id = 0; 

        //Varialbes
        $row_user = $this->Db_model->row_id('users', $user_id);
        $row_message = $this->Db_model->row_id('messages', $message_id);
        $qty_recent_messages = $this->qty_recent_messages($user_id, $row_message);
        
        //Si no hay mensajes recientes, crear alerta de notificación
        if ( $qty_recent_messages == 0 ) {

            $sender = $this->Db_model->row_id('users', $row_message->user_id);

            //Preparar registro para events
            $arr_row = $this->arr_row_alert($user_id, 20);
            $arr_row['content'] = "{$sender->display_name} has sent you a message";
            $arr_row['element_id'] = $row_message->id;
    
            $this->load->model('Event_model');
            $alert_id = $this->Event_model->save($arr_row, "start = '{$arr_row['start']}'");
        }

        return $alert_id;
    }

    /**
     * Guarda registro de alerta de notificación (tipo 111) en la tabla events asociada
     * al recibir un nuevo comentario (alerta tipo 30)
     * 2021-08-19
     */
    function save_new_comment_alert($comment_id)
    {
        $comment = $this->Db_model->row_id('comments', $comment_id);
        $commenter = $this->Db_model->row_id('users', $comment->creator_id);
        $table_name = $this->Db_model->field_id('sis_table', $comment->table_id, 'table_name');
        $row_element = $this->Db_model->row_id($table_name, $comment->element_id);

        //Preparar registro para events
        $arr_row = $this->arr_row_alert($row_element->creator_id, 30);
        $arr_row['content'] = "You have a new comment from {$commenter->display_name}";
        $arr_row['element_id'] = $comment->id;          //ID Comentario
        $arr_row['related_1'] = $comment->table_id;     //Id Tabla donde está el elemento comentado
        $arr_row['related_2'] = $row_element->id;       //Id Elemento Comentado

        $this->load->model('Event_model');
        $alert_id = $this->Event_model->save($arr_row, "start = '{$arr_row['start']}'");

        return $alert_id;
    }

    /**
     * Cantidad de notificaciones que el usuario en sesión no ha leído
     * 2021-08-12
     */
    function qty_unread_notifications(){
        $notifications = $this->notifications();
        return $notifications->num_rows();
    }

    /**
     * Notificaciones para informar actividad reslacionada con el usuario
     * 2021-08-12
     */
    function notifications()
    {
        $this->db->select('id, title, content, created_at, related_3 AS alert_type, element_id, related_1, related_2');
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->where('type_id', 111);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(20);
        $events = $this->db->get('events');

        return $events;
    }

}