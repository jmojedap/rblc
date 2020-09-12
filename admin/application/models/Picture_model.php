<?php
class Picture_model extends CI_Model{

    function basic($file_id)
    {
        $data['row'] = $this->row("id = {$file_id}");
        $data['head_title'] = substr($data['row']->title,0,50);
        $data['view_a'] = 'pictures/user_v';

        return $data;
    }

    /**
     * Registro de picture, tabla file
     */
    function row($condition)
    {
        $row = NULL;
        //$this->db->select('id, file_name, title, description, creator_id, updater_id');
        $this->db->select('id, file_name, title, description, related_1, album_id, url, url_thumbnail');
        $query = $this->db->get_where('file', $condition);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

// EXPLORE FUNCTIONS - pictures/explore
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'pictures';                      //Nombre del controlador
            $data['cf'] = 'pictures/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'pictures/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Projects';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            //$data['head_subtitle'] = $data['search_num_rows'];
            //$data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Conjunto de variables de una búsqueda, incluido el listado de resultados
     */
    function get($filters, $num_page, $per_page = 6)
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
     * Query con resultados de pictures filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('file.id, title, file.url, file.url_thumbnail, user.display_name AS user_display_name, user.id AS user_id, user.url_thumbnail AS user_url_thumbnail');
            $this->db->join('user_meta', 'user_meta.related_1 = file.id', 'left');  
            $this->db->join('user', 'user.id = user_meta.user_id', 'left');
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('file.priority', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('file', $per_page, $offset); //Resultados por página
        
        return $query;
    }
    
    /**
     * String con condición WHERE SQL para filtrar file
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= 'album_id = 10 AND ';   //Colección general de imágenes públicas de un usuario

        //$condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('file_name', 'title', 'description', 'keywords', 'searcher'));
        //$words_condition = $this->Search_model->words_condition($filters['q'], array('searcher'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Filtros
        if ( strlen($filters['tag']) > 0 )
        {
            $sql_tags = "SELECT id FROM tag WHERE slug LIKE '%{$filters['tag']}%'";
            $condition .= "file.id IN (SELECT file_id FROM file_meta WHERE related_1 IN ({$sql_tags})) AND ";
        }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Devuelve segmento SQL, con filtro según el rol
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún file, se obtendrían cero file.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los file
            $condition = 'id > 0';
        }
        
        return $condition;
    }

    /**
     * Condición WHERE según lo buscado en el filtro q
     */
    /*function words_condition($q)
    {
        $words_condition = $this->Search_model->words_condition($q, array('file_name', 'title', 'description', 'keywords'));
        if ( $words_condition )
        {
            $this->db->where($words_condition);
        }

        return $words_condition;
    }*/
    
    /**
     * Cantidad total registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('file'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Array con options para ordenar el listado de file en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID File',
            'file_name' => 'Title'
        );
        
        return $order_options;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-01-21
     */
    function list($filters, $per_page = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $per_page, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            //Liked por el usuario en sesión?
            $row->liked = $this->Db_model->num_rows('file_meta', "file_id = {$row->id} AND type_id = 10 AND related_1 = {$this->session->userdata('user_id')}");
            $list[] = $row;
        }

        return $list;
    }

// Otras
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

    /**
     * Listado de imágenes aleatorias, utilizadas en el home
     * 2020-08-22
     */
    function get_random($qty)
    {
        $qty_rows = $this->Db_model->num_rows('file', "is_image = 1 AND album_id = 10");

        $offset = rand(0, $qty_rows - $qty);

        //Consulta
        $this->db->select('file.id, title, file.url, file.url_thumbnail, user.display_name AS user_display_name, user.id AS user_id, user.url_thumbnail AS user_url_thumbnail');
        $this->db->join('user_meta', 'user_meta.related_1 = file.id', 'left');  
        $this->db->join('user', 'user.id = user_meta.user_id', 'left');
        $this->db->order_by('file.priority', 'ASC');
        $query = $this->db->get('file', $qty, $offset);

        $data['offset'] = $offset;
        $data['qty_rows'] = $qty_rows;
        $data['list'] = $query->result();

        return $data;
    }
}