<?php
class Ideabook_model extends CI_Model{

    function basic($post_id)
    {
        $row = $this->Db_model->row_id('posts', $post_id);

        $data['post_id'] = $post_id;
        $data['row'] = $row;
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'ideabooks/ideabook_v';
        $data['nav_2'] = 'ideabooks/menu_v';

        return $data;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla posts.
     * 2020-02-22
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }

        $data = array('status' => 0);
        
        //Insert in table
            $this->db->insert('posts', $arr_row);
            $data['saved_id'] = $this->db->insert_id();

        if ( $data['saved_id'] > 0 ) { $data['status'] = 1; }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla post
     * 2020-02-22
     */
    function update($post_id)
    {
        $data = array('status' => 0);

        //Guardar
            $arr_row = $this->Db_model->arr_row($post_id);
            $saved_id = $this->Db_model->save('posts', "id = {$post_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){ $data = array('status' => 1); }
        
        return $data;
    }

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['type_id'] = 7120; //Post tipo ideabook
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['post_name'], 'posts');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }
    
    function deletable()
    {
        $deletable = 0;
        if ( $this->session->userdata('role') <= 1 ) { $deletable = 1; }

        return $deletable;
    }

    /**
     * Eliminar un usuario de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($post_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($post_id) ) 
        {
            //Tablas relacionadas
            
            //Tabla principal
                $this->db->where('id', $post_id);
                $this->db->delete('posts');

            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }
    
// EXPLORE FUNCTIONS - posts/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'ideabooks';                      //Nombre del controlador
            $data['cf'] = 'ideabooks/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'ideabooks/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Ideabooks';
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
            $query = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $query->result();
            $data['str_filters'] = $this->Search_model->str_filters($filters);      //String de filtros tipo GET
            $data['search_num_rows'] = $this->search_num_rows($filters);            //Total resultados
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Query con resultados de ideabooks filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('id, post_name AS name, excerpt AS description, url_image, url_image, url_thumbnail');
            
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
            $query = $this->db->get('posts', $per_page, $offset); //Resultados por página
        
        return $query;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= 'type_id = 7120 AND ';   //Post tipo ideabook

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->words_condition($filters['q']);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['u'] != '' ) { $condition .= "creator_id = {$filters['u']} AND "; }
        //if ( $filters['like'] == 1 ) { $condition .= "posts.id IN (SELECT post_id FROM posts_meta WHERE type_id = 10 AND related_1 = '{$this->session->userdata('user_id')}') AND "; }

        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Devuelve segmento WHERE SQL, con filtro según el rol del usuario en sesión
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero posts.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        } elseif( $role >= 10 ){
            $condition = 'creator_id = ' . $this->session->userdata('user_id');
        }
        
        return $condition;
    }

    /**
     * Condición WHERE según lo buscado en el filtro q
     */
    function words_condition($q)
    {
        $words_condition = $this->Search_model->words_condition($q, array('post_name', 'excerpt', 'keywords'));
        if ( $words_condition )
        {
            $this->db->where($words_condition);
        }

        return $words_condition;
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
        $query = $this->db->get('posts'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Ideabooks',
            'post_name' => 'Name'
        );
        
        return $order_options;
    }

// IMÁGENES
//-----------------------------------------------------------------------------

    /**
     * Agrega registro a posts_meta, asociando post con una imagen de la tabla file
     * 2020-07-03
     */
    function add_image($ideabook_id, $file_id)
    {
        $arr_row = $this->Post_model->arr_row_meta($post_id);
        $arr_row['type_id'] = 1;    //Imagen
        $arr_row['related_1'] = $file_id;

        $condition = "post_id = {$post_id} AND type_id = 1 AND related_1 = {$file_id}";
        $data['saved_id'] = $this->Db_model->save('posts_meta', $condition, $arr_row);

        return $data;
    }

    /**
     * Query con listado de imagebook
     * 2020-07-18
     */
    function images($ideabook_id)
    {
        $projects = $this->projects($ideabook_id);
        $str_projects = $this->pml->query_to_str($projects, 'id');

        $this->db->select('id AS file_id, url, url_thumbnail');
        $this->db->where("id IN (SELECT related_1 FROM posts_meta WHERE type_id = 1 AND post_id IN ({$str_projects}))");
        $files = $this->db->get('files');

        return $files;
    }

// PROJECTS
//-----------------------------------------------------------------------------

    /**
     * Contenidos digitales asignados a un producto
     */
    function projects($ideabook_id)
    {
        $this->db->select('posts.id, post_name AS title, posts_meta.id AS meta_id, posts.url_image, posts.url_thumbnail');
        $this->db->where('posts.type_id', 7110); //Post tipo project
        $this->db->join('posts_meta', 'posts.id = posts_meta.related_1');
        $this->db->where('posts_meta.type_id', 722);   //Asignación de project
        $this->db->where('posts_meta.post_id', $ideabook_id);

        $projects = $this->db->get('posts');
        
        return $projects;
    }

    /**
     * Agrega registro a posts_meta, asociando project a un ideabook
     * 2020-07-03
     */
    function add_project($ideabook_id, $project_id)
    {
        $arr_row['post_id'] = $ideabook_id;        
        $arr_row['type_id'] = 722;    //Project
        $arr_row['related_1'] = $project_id;
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $arr_row['creator_id'] = $this->session->userdata('user_id');

        $condition = "post_id = {$arr_row['post_id']} AND type_id = {$arr_row['type_id']} AND related_1 = {$project_id}";
        $data['saved_id'] = $this->Db_model->insert_if('posts_meta', $condition, $arr_row);

        return $data;
    }
}