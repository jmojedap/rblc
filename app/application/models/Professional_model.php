<?php
class Professional_model extends CI_Model{

    function basic($user_id)
    {
        $data['user_id'] = $user_id;
        $data['row'] = $this->Db_model->row_id('user', $user_id);
        $data['head_title'] = substr($data['row']->display_name,0,50);
        $data['view_a'] = 'professionals/profile_v';
        $data['qty_followers'] = $this->qty_followers($user_id);
        $data['qty_likes'] = $this->qty_likes($user_id);

        return $data;
    }

// EXPLORE FUNCTIONS - professionals/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'users';                      //Nombre del controlador
            $data['cf'] = 'professionals/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'professionals/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Usuarios';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 10;                             //Cantidad de registros por página
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $this->load->model('Search_model');
            $data['filters'] = $this->Search_model->filters();
            $elements = $this->search($data['filters'], $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar user
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Rol de user
        if ( $filters['role'] != '' ) { $condition .= "role = {$filters['role']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('user_id'));

        //Construir consulta
            $this->db->select('user.id, username, display_name, first_name, last_name, email, role, image_id, url_image, url_thumbnail, status, user.type_id');
            //$this->db->join('place', 'place.id = user.city_id', 'left');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('first_name', 'last_name', 'display_name', 'email', 'id_number'));
            if ( $words_condition )
            {
                $this->db->where($words_condition);
            }
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('updated_at', 'DESC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de user en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('user'); //Resultados totales
        } else {
            $query = $this->db->get('user', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     * 
     * @param type $filters
     * @return type
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     * 
     * @param type $user_id
     * @return type 
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'user.id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Usuario',
            'last_name' => 'Apellidos',
            'id_number' => 'No. documento',
        );
        
        return $order_options;
    }

// METADATA
//-----------------------------------------------------------------------------
    
    function row_content($user_id)
    {
        $this->db->select('id AS post_id, content');
        $this->db->where('type_id', 1020);
        $this->db->where('related_1', $user_id);
        $posts = $this->db->get('post');

        $row_content = NULL;
        if ( $posts->num_rows() > 0 )
        {
            $row_content = $posts->row();
        }

        return $row_content;   
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
        $this->db->where('creator_id', $user_id);
        $images = $this->db->get('file');

        return $images;
    }

// SEGUIDORES
//-----------------------------------------------------------------------------

    /**
     * Establece si el usuario (user_id) es seguido o no por el usuario en sesión
     * 2020-06-16
     */
    function follow_status($user_id)
    {
        $follow_status = 2; //No seguido

        if ( $this->session->userdata('logged') )
        {
            $condition = "user_id = {$user_id} AND type_id = 1011 AND related_1 = {$this->session->userdata('user_id')}";
            $row_meta = $this->Db_model->row('user_meta', $condition);
    
            if ( ! is_null($row_meta) )
            {
                $follow_status = 1;
            }
        }

        return $follow_status;
    }

    /**
     * Int, cantidad de seguidores que tiene un usuario
     * 2020-08-24
     */
    function qty_followers($user_id)
    {
        $qty_followers = $this->Db_model->num_rows('user_meta', "type_id = 1011 AND user_id = {$user_id}");
        return $qty_followers;
    }

    /**
     * Int, cantidad de likes que tienen las fotos asocidas a un usuario
     * 2020-08-24
     */
    function qty_likes($user_id)
    {
        $condition  = "type_id = 10 AND file_id IN (SELECT id FROM file WHERE album_id = 10 AND table_id = 1000 AND related_1 = {$user_id})";
        $qty_likes = $this->Db_model->num_rows('file_meta', $condition);

        return $qty_likes;
    }

// METADATA
//-----------------------------------------------------------------------------

    function metadata($user_id, $type_id)
    {
        $this->db->select('user_meta.id AS meta_id, item_name AS title, user_meta.related_1');
        $this->db->where('user_meta.type_id', $type_id);
        $this->db->where('item.category_id', $type_id);
        $this->db->where('user_meta.user_id', $user_id);
        $this->db->join('user_meta', 'item.cod = user_meta.related_1');
        $elements = $this->db->get('item');

        return $elements;
    }

    /**
     * Query, tags asociados a un usuario
     * 2020-08-03
     */
    function tags($user_id, $category_id = NULL)
    {
        $this->db->select('user_meta.id AS meta_id, tag.name, user_meta.related_1');
        $this->db->where('user_meta.type_id', 27);  //Metadato, tag
        if ( ! is_null($category_id) ) { $this->db->where('tag.category_id', $type_id); }
        $this->db->where('user_meta.user_id', $user_id);
        $this->db->join('user_meta', 'tag.id = user_meta.related_1');
        $tags = $this->db->get('tag');

        return $tags;
    }

// OTROS
//-----------------------------------------------------------------------------

    /**
     * Opciones de usuario en campos de autollenado, como agregar usuarios a una conversación
     * 2019-11-13
     */
    function autocomplete($filters, $limit = 15)
    {
        $role_filter = $this->role_filter();

        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($filters['q']) > 2 )
            {
                $words = $this->Search_model->words($filters['q']);

                foreach ($words as $word) {
                    $this->db->like('CONCAT(first_name, last_name, username, code)', $word);
                }
            }
        
        //Especificaciones de consulta
            //$this->db->select('id, CONCAT((display_name), " (",(username), ") Cod: ", IFNULL(code, 0)) AS value');
            $this->db->select('id, CONCAT((display_name), " (",(username), ")") AS value');
            $this->db->where($role_filter); //Filtro según el rol de usuario que se tenga
            $this->db->order_by('last_name', 'ASC');
            
        //Otros filtros
            if ( $filters['condition'] != '' ) { $this->db->where($filters['condition']); }    //Condición adicional
            
        $query = $this->db->get('user', $limit); //Resultados por página
        
        return $query;
    }
}