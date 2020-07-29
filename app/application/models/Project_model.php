<?php
class Project_model extends CI_Model{

    function basic($project_id)
    {
        $row = $this->Db_model->row_id('post', $project_id);

        $data['project_id'] = $project_id;
        $data['row'] = $row;
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'projects/post_v';
        $data['row_user'] = $this->Db_model->row_id('user', $row->related_1);

        return $data;
    }
    
// EXPLORE FUNCTIONS - projects/explore
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Elemento de exploración
            $data['controller'] = 'projects';                      //Nombre del controlador
            $data['cf'] = 'projects/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'projects/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Products and Projects';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
        return $data;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Project',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al project, mediante la tabla post_meta, tipo 1
     * 2020-05-08
     */
    function images($project_id)
    {
        $this->db->select('post_meta.id AS meta_id, file.id, file.title, url, url_thumbnail, post_meta.integer_1 AS main');
        $this->db->where('post_meta.type_id', 1);
        $this->db->where('post_meta.post_id', $project_id);
        $this->db->join('post_meta', 'file.id = post_meta.related_1');
        $images = $this->db->get('file');

        return $images;
    }

// METADATA
//-----------------------------------------------------------------------------

function metadata($project_id, $type_id)
{
    $this->db->select('post_meta.id AS meta_id, item_name AS title, post_meta.related_1');
    $this->db->where('post_meta.type_id', $type_id);
    $this->db->where('item.category_id', $type_id);
    $this->db->where('post_meta.post_id', $project_id);
    $this->db->join('post_meta', 'item.cod = post_meta.related_1');
    $elements = $this->db->get('item');

    return $elements;
}
}