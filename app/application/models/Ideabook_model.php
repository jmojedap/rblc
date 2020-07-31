<?php
class Ideabook_model extends CI_Model{

    function basic($ideabook_id)
    {
        $data['ideabook_id'] = $ideabook_id;
        $data['row'] = $this->Db_model->row_id('post', $ideabook_id);
        $data['head_title'] = substr($data['row']->post_name,0,50);
        $data['view_a'] = 'ideabooks/ideabook_v';
        //$data['nav_2'] = 'users/menus/user_v';

        //if ( $data['row']->role == 13  ) { $data['nav_2'] = 'users/menus/provider_v'; }

        return $data;
    }

    function my_ideabooks()
    {
        $this->db->select('id, post_name AS name');
        $this->db->where('creator_id', $this->session->userdata('user_id'));
        $ideabooks = $this->db->get('post');

        return $ideabooks;
    }



}