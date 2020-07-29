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
            $data['list'] = $this->list($data['filters'], $per_page, $offset);    //Resultados para página
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
        
        //$role_filter = $this->role_filter($this->session->userdata('user_id'));

        //Construir consulta
            $this->db->select('user.id, username, display_name, email, image_id, url_image, url_thumbnail, user.type_id, country, state_province, city, about');
            //$this->db->join('place', 'place.id = user.city_id', 'left');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('first_name', 'last_name', 'display_name', 'email'));
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
            //$this->db->where($role_filter); //Filtro según el rol de user en sesión
            $this->db->where('role', 13);
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
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-06-19
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            /*$row->qty_students = $this->Db_model->num_rows('group_user', "group_id = {$row->id}");  //Cantidad de estudiantes*/
            if ( $row->image_id == 0 )
            {
                $first_image = $this->first_image($row->id);
                $row->url_image = $first_image['url'];
                $row->url_thumbnail = $first_image['url_thumbnail'];
            }

            $row->content = $this->row_content($row->id);
            

            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $query = $this->search($filters); //Para calcular el total de resultados
        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $condition = 'id > 0';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        /*$role = $this->session->userdata('role');
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'user.id > 0';
        }*/
        
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
    
    function editable()
    {
        return TRUE;
    }

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

// Metadatos
//-----------------------------------------------------------------------------

    function row_content($user_id)
    {
        $this->db->select('id AS post_id, content');
        $this->db->where('type_id', 1020);
        $this->db->where('related_1', $user_id);
        $posts = $this->db->get('post');

        $row_content = array('post_id' => 0, 'content' => 'Under construction');
        if ( $posts->num_rows() > 0 )
        {
            $row_content = $posts->row();
        }

        return $row_content;   
    }
}