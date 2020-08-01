<?php
class Professional_model extends CI_Model{

    function basic($user_id)
    {
        $data['user_id'] = $user_id;
        $data['row'] = $this->Db_model->row_id('user', $user_id);
        $data['head_title'] = substr($data['row']->display_name,0,50);
        $data['view_a'] = 'users/user_v';
        $data['nav_2'] = 'users/menus/user_v';

        if ( $data['row']->role == 23  ) { $data['nav_2'] = 'users/menus/client_v'; }

        return $data;
    }

// EXPLORE FUNCTIONS - users/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     * 
     * @return string
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'users';                      //Nombre del controlador
            $data['cf'] = 'users/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'users/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Conjunto de variables de una búsqueda, incluido el listado de resultados
     */
    function get($filters, $num_page, $per_page = 8)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Query resultados página
            //$query = $this->search($filters, $per_page, $offset);    //Resultados para página
            $list = $this->list($filters, $per_page, $offset);
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $list;
            $data['str_filters'] = $this->Search_model->str_filters($filters);      //String de filtros tipo GET
            $data['search_num_rows'] = $this->search_num_rows($filters);            //Total resultados
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar user
     */
    function search_condition($filters)
    {
        $condition = 'role = 13 AND ';   //Es professional

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('first_name', 'last_name', 'display_name', 'email', 'about'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Filtros
        if ( strlen($filters['cat']) > 0 )
        {
            $sql_categories = "SELECT cod FROM item WHERE category_id = 716 AND ancestry LIKE '%-{$filters['cat']}-%'";
            $condition .= "user.id IN (SELECT user_id FROM user_meta WHERE type_id = 716 AND related_1 IN ({$sql_categories})) AND ";
        }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('user.id, username, display_name, email, image_id, url_image, url_thumbnail, user.type_id, country, state_province, city, about');
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('user', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-07-31
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            if ( $row->image_id == 0 )
            {
                $first_image = $this->first_image($row->id);
                $row->url_image = $first_image['url'];
                $row->url_thumbnail = $first_image['url_thumbnail'];
            }

            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Cantidad total registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('user'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'Display name'
        );
        
        return $order_options;
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al usuario, mediante la tabla user_meta, tipo 1
     * 2020-05-15
     */
    function images($user_id)
    {
        $this->db->select('file.id, file.title, url, url_thumbnail');
        $this->db->where('table_id', 1000); //Tabla usuario
        $this->db->where('related_1', $user_id);
        $this->db->where('album_id', 10);   //Colección general de imágenes de un usuario

        $images = $this->db->get('file');

        return $images;
    }

    function first_image($user_id, $format = 'thumbnail')
    {
        $first_image['url'] = URL_IMG . 'app/sm_coming_soon.jpg';
        $first_image['url_thumbnail'] = URL_IMG . 'app/sm_coming_soon.jpg';

        $images = $this->images($user_id);

        if ( $images->num_rows() > 0 )
        {
            $first_image['url'] = $images->row(0)->url;
            $first_image['url_thumbnail'] = $images->row(0)->url_thumbnail;
        }

        return $first_image;
    }
}