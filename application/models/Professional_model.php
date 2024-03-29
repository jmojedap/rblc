<?php
class Professional_model extends CI_Model{

    function basic($user_id)
    {
        $data['user_id'] = $user_id;
        $data['row'] = $this->Db_model->row_id('users', $user_id);
        $data['head_title'] = substr($data['row']->display_name,0,50);
        $data['view_a'] = 'app/professionals/profile_v';
        $data['qty_followers'] = $this->qty_followers($user_id);
        $data['qty_likes'] = $this->qty_likes($user_id);

        return $data;
    }

// EXPLORE FUNCTIONS - professionals/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'users';                      //Nombre del controlador
            $data['cf'] = 'professionals/explore/';                      //Nombre del controlador
            $data['views_folder'] = $this->views_folder . 'explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Professionals';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
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
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
    {
        $condition = 'role = 13 AND ';   //Es professional
        
        //Rol de user
        if ( $filters['role'] != '' ) { $condition .= "role = {$filters['role']} AND "; }
        if ( $filters['cat_1'] != '' ) { $condition .= "cat_1 = {$filters['cat_1']} AND "; }
        
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
            //$this->db->select('users.id, username, display_name, first_name, last_name, email, role, image_id, url_image, url_thumbnail, status, users.type_id');
            $this->db->select('users.id, username, display_name, email, image_id, url_image, url_thumbnail, users.type_id, country, state_province, city, about');
            //$this->db->join('places', 'places.id = user.city_id', 'left');
        
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
            $query = $this->db->get('users'); //Resultados totales
        } else {
            $query = $this->db->get('users', $per_page, $offset); //Resultados por página
        }
        
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
        $condition = 'role = 13';  //Valor por defecto, ningún user, se obtendrían cero user.
        
        /*if ( $role <= 3 ) 
        {   //Desarrollador, todos los user
            $condition = 'role = 13users.id > 0';
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

// METADATA
//-----------------------------------------------------------------------------
    
    function row_content($user_id)
    {
        $this->db->select('id AS post_id, content');
        $this->db->where('type_id', 1020);
        $this->db->where('related_1', $user_id);
        $posts = $this->db->get('posts');

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
     * Imágenes asociadas al usuario, tabla file
     * 2021-04-14
     */
    function images($user_id)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail');
        $this->db->where('table_id', 1000); //Tabla usuario
        $this->db->where('related_1', $user_id);
        $this->db->where('album_id', 10);   //Colección general de imágenes de un usuario
        $this->db->order_by('updated_at', 'desc');

        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Primera imagen que tiene asignada un professional o una por defecto si
     * no tiene ninguna
     * 2022-06-29
     */
    function first_image($user_id, $format = 'thumbnail')
    {
        $first_image['url'] = URL_IMG . 'users/user.png';
        $first_image['url_thumbnail'] = URL_IMG . 'users/md_user.png';

        $images = $this->images($user_id);

        if ( $images->num_rows() > 0 )
        {
            $first_image['url'] = $images->row(0)->url;
            $first_image['url_thumbnail'] = $images->row(0)->url_thumbnail;
        }

        return $first_image;
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
            $row_meta = $this->Db_model->row('users_meta', $condition);
    
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
        $qty_followers = $this->Db_model->num_rows('users_meta', "type_id = 1011 AND user_id = {$user_id}");
        return $qty_followers;
    }

    /**
     * Int, cantidad de likes que tienen las fotos asocidas a un usuario
     * 2020-08-24
     */
    function qty_likes($user_id)
    {
        $condition  = "type_id = 10 AND file_id IN (SELECT id FROM files WHERE album_id = 10 AND table_id = 1000 AND related_1 = {$user_id})";
        $qty_likes = $this->Db_model->num_rows('files_meta', $condition);

        return $qty_likes;
    }

// METADATA
//-----------------------------------------------------------------------------

    function metadata($user_id, $type_id)
    {
        $this->db->select('users_meta.id AS meta_id, item_name AS title, users_meta.related_1');
        $this->db->where('users_meta.type_id', $type_id);
        $this->db->where('items.category_id', $type_id);
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->join('users_meta', 'items.cod = users_meta.related_1');
        $elements = $this->db->get('items');

        return $elements;
    }

    /**
     * Query, tags asociados a un usuario
     * 2020-08-03
     */
    function tags($user_id, $category_id = NULL)
    {
        $this->db->select('users_meta.id AS meta_id, tags.name, users_meta.related_1');
        $this->db->where('users_meta.type_id', 27);  //Metadato, tag
        if ( ! is_null($category_id) ) { $this->db->where('tags.category_id', $type_id); }
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->join('users_meta', 'tags.id = users_meta.related_1');
        $tags = $this->db->get('tags');

        return $tags;
    }

    /**
     * Guarda un registro en la tabla users_meta
     * 2020-07-16
     */
    function save_meta($arr_row, $fields = array('related_1'))
    {
        $condition = "user_id = {$arr_row['user_id']} AND type_id = {$arr_row['type_id']}";

        foreach ($fields as $field)
        {
            $condition .= " AND {$field} = '{$arr_row[$field]}'";
        }

        $meta_id = $this->Db_model->save('users_meta', $condition, $arr_row);
        
        return $meta_id;
    }

    /**
     * Guarda múltiples registros en la tabla users_meta, con un array,
     * y elimina los que no estén en el array enviado por post ($new_metas)
     * 2020-08-01
     */
    function save_meta_array($user_id, $type_id, $new_metas)
    {        
        $saved = array();                   //Resultado por defecto

        //Eliminar los que ya no están en $new_metas
            $this->load->model('User_model');   //Para utilizar funcion delete_meta
            $old_meta = $this->metadata($user_id, $type_id);

            foreach ( $old_meta->result() as $row_meta ) 
            {
                if ( ! in_array($row_meta->related_1, $new_metas) )
                {
                    $this->User_model->delete_meta($user_id, $row_meta->meta_id);
                    $saved[] = 'Deleted related_1: ' . $row_meta->related_1;
                }
            }

        //Guardar nuevos
            //Array general
                $arr_row['user_id'] = $user_id;
                $arr_row['type_id'] = $type_id;
                $arr_row['creator_id'] = $this->session->userdata('user_id');
                $arr_row['updater_id'] = $this->session->userdata('user_id');
        
            //Recorrer array nuevo y guardar
                if ( ! is_null($new_metas) )
                {
                    foreach ($new_metas as $related_1)
                    {
                        $arr_row['related_1'] = $related_1;
                        $meta_id = $this->save_meta($arr_row);
                        $saved[] = 'Saved related_1: ' . $related_1;
                    }
                }

        return $saved;
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
            
        $query = $this->db->get('users', $limit); //Resultados por página
        
        return $query;
    }
}