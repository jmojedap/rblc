<?php
class Picture_model extends CI_Model{

    function basic($file_id)
    {
        $data['picture_id'] = $file_id;
        $data['row'] = $this->Db_model->row_id('file', $file_id);
        $data['head_title'] = substr($data['row']->title,0,50);
        $data['view_a'] = 'pictures/user_v';
        //$data['nav_2'] = 'pictures/menus/_v';

        return $data;
    }

// EXPLORE FUNCTIONS - pictures/explore
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {   
        //Elemento de exploración
            $data['controller'] = 'pictures';                      //Nombre del controlador
            $data['cf'] = 'pictures/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'pictures/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Projects';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
        return $data;
    }
    
// METADATOS
//-----------------------------------------------------------------------------

    /**
     * Query, tags de un file
     */
    function tags($file_id)
    {
        $this->db->select('id, name, slug');
        $this->db->where("id IN (SELECT related_1 FROM file_meta WHERE file_id = {$file_id} AND type_id = 27)");
        $tags = $this->db->get('tag');

        return $tags;
    }
}