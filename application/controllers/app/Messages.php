<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('Message_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    /**
     * Aplicación de mensajería
     */
    function conversation($conversation_id = 0)
    {
        $data['conversation_id'] = $conversation_id;
        $data['head_title'] = 'Messages';
        $data['view_a'] = 'app/messages/conversation/conversation_v';
        $this->App_model->view(TPL_FRONT, $data);
    }
}