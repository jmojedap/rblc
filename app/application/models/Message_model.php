<?php
class Message_model extends CI_Model{

    function basic($conversation_id)
    {
        $data['row'] = $this->Db_model->row_id('conversation', $conversation_id);
        $data['head_title'] = $data['row']->subject;

        return $data;
    }

// Conversation Application
//-----------------------------------------------------------------------------

    
}