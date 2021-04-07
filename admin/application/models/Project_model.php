<?php
class Project_model extends CI_Model{

    function basic($project_id)
    {
        $row = $this->Db_model->row_id('posts', $project_id);

        $data['project_id'] = $project_id;
        $data['row'] = $row;
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = 'projects/post_v';
        $data['nav_2'] = 'projects/menu_v';

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
    function update($project_id)
    {
        $data = array('status' => 0);

        //Guardar
            $arr_row = $this->Db_model->arr_row($project_id);
            $saved_id = $this->Db_model->save('posts', "id = {$project_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){ $data = array('status' => 1); }
        
        return $data;
    }

    /**
     * Prepara Array para guardar registro
     * 2020-07-16
     */
    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['type_id'] = 7110;    //Post type project
        $arr_row['related_1'] = $this->session->userdata('user_id');    //Propietario
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
    function delete($project_id)
    {
        $qty_deleted = 0;

        if ( $this->deletable($project_id) ) 
        {
            //Tablas relacionadas
            
            //Tabla principal
                $this->db->where('id', $project_id);
                $this->db->delete('posts');

            $qty_deleted = $this->db->affected_rows();
        }

        return $qty_deleted;
    }
    
// EXPLORE FUNCTIONS - projects/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'projects';                      //Nombre del controlador
            $data['cf'] = 'projects/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'projects/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Projects';
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
     * Query con resultados de projects filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('id, post_name AS name, related_1 AS professional_id, integer_1 AS price, related_2, slug, url_image, url_image, url_thumbnail');
            
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
     * 2021-02-24 (project type)
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= 'type_id = 7110 AND ';   //Post tipo project

        //$condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->words_condition($filters['q']);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['u'] != '' ) { $condition .= "related_1 = {$filters['u']} AND "; }
        if ( $filters['type'] != '' ) { $condition .= "related_2 = {$filters['type']} AND "; }
        if ( $filters['descriptor'] != '' ) { $condition .= "id IN (SELECT post_id FROM posts_meta WHERE type_id = 710 AND related_1 = '{$filters['descriptor']}') AND "; }
        if ( $filters['style'] != '' ) { $condition .= "id IN (SELECT post_id FROM posts_meta WHERE type_id = 712 AND related_1 = '{$filters['style']}') AND "; }
        if ( $filters['feeling'] != '' ) { $condition .= "id IN (SELECT post_id FROM posts_meta WHERE type_id = 714 AND related_1 = '{$filters['feeling']}') AND "; }
        if ( strlen($filters['cat']) > 0 )
        {
            $sql_categories = "SELECT cod FROM items WHERE category_id = 710 AND item_name LIKE '%{$filters['cat']}%'";
            $condition .= "posts.id IN (SELECT post_id FROM posts_meta WHERE type_id = 710 AND related_1 IN ({$sql_categories})) AND ";
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
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero posts.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }
        
        return $condition;
    }

    /**
     * Condición WHERE según lo buscado en el filtro q
     */
    function words_condition($q)
    {
        $words_condition = $this->Search_model->words_condition($q, array('post_name', 'content', 'excerpt', 'keywords'));
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
            'id' => 'ID Project',
            'post_name' => 'Name'
        );
        
        return $order_options;
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al project, mediante la tabla posts_meta, tipo 1
     * 2020-05-08
     */
    function images($project_id)
    {
        $this->db->select('posts_meta.id AS meta_id, files.id, files.title, url, url_thumbnail, posts_meta.integer_1 AS main');
        $this->db->where('posts_meta.type_id', 1);
        $this->db->where('posts_meta.post_id', $project_id);
        $this->db->join('posts_meta', 'files.id = posts_meta.related_1');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Asocia un archivo imagen a un projecto en la tabla posts_meta
     * 2020-07-06
     */
    function add_image($project_id, $file_id)
    {
        $arr_row['type_id'] = 1;
        $arr_row['post_id'] = $project_id;
        $arr_row['related_1'] = $file_id;

        $condition = "type_id = {$arr_row['type_id']} AND post_id = {$arr_row['post_id']} AND related_1 = {$arr_row['related_1']}";

        $saved_id = $this->Db_model->save('posts_meta', $condition, $arr_row);

        return $saved_id;
    }

    /**
     * Establecer una imagen asociada a un project (posts_meta) como la imagen principal (tabla post)
     * 2020-07-09
     */
    function set_main_image($project_id, $meta_id)
    {
        $data = array('status' => 0);

        $row_meta = $this->Db_model->row('posts_meta', "id = {$meta_id} AND post_id = {$project_id}");
        $row_file = $this->Db_model->row_id('files', $row_meta->related_1);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE posts_meta SET integer_1 = 0 WHERE type_id = 1 AND post_id = {$project_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE posts_meta SET integer_1 = 1 WHERE id = {$meta_id} AND post_id = {$project_id}");

            //Actualizar registro en tabla projecto
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $project_id);
            $this->db->update('posts', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }

    /**
     * Elimina una imagen de un project, elimina el registro de la tabla file
     * y sus archivos relacionados
     * 2020-07-08
     */
    function delete_image($project_id, $meta_id)
    {
        $qty_deleted = 0;

        $row_meta = $this->Db_model->row('posts_meta', "id = {$meta_id} AND post_id = {$project_id}");

        if ( ! is_null($row_meta) )
        {
            //Eliminar archivos y registro en tabla file
            $row_file = $this->Db_model->row_id('files', $row_meta->related_1);
            $this->load->model('File_model');
            $this->File_model->delete($row_file->id);

            //Eliminar de tabla posts_meta
            $this->db->where('id', $meta_id);
            $this->db->delete('posts_meta');
            
            $qty_deleted = $this->db->affected_rows();
        }

        return $qty_deleted;
    }

// METADATA
//-----------------------------------------------------------------------------

    function metadata($project_id, $type_id)
    {
        $this->db->select('posts_meta.id AS meta_id, item_name AS title, posts_meta.related_1');
        $this->db->where('posts_meta.type_id', $type_id);
        $this->db->where('items.category_id', $type_id);
        $this->db->where('posts_meta.post_id', $project_id);
        $this->db->join('posts_meta', 'items.cod = posts_meta.related_1');
        $elements = $this->db->get('items');

        return $elements;
    }

    /**
     * Guarda un registro en la tabla posts_meta
     * 2020-07-16
     */
    function save_meta($arr_row, $fields = array('related_1'))
    {
        $condition = "post_id = {$arr_row['post_id']} AND type_id = {$arr_row['type_id']}";

        foreach ($fields as $field)
        {
            $condition .= " AND {$field} = '{$arr_row[$field]}'";
        }

        $meta_id = $this->Db_model->save('posts_meta', $condition, $arr_row);
        
        return $meta_id;
    }

    /**
     * Guarda múltiples registros en la tabla posts_meta, con un array,
     * y elimina los que no estén en el array enviado por post ($new_metas)
     * 2020-07-16
     */
    function save_meta_array($project_id, $type_id, $new_metas)
    {        
        $saved = array();                   //Resultado por defecto

        //Eliminar los que ya no están en $new_metas
            $this->load->model('Post_model');   //Para utilizar funcion delete_meta
            $old_meta = $this->metadata($project_id, $type_id);

            foreach ( $old_meta->result() as $row_style ) 
            {
                if ( ! in_array($row_style->related_1, $new_metas) )
                {
                    $this->Post_model->delete_meta($project_id, $row_style->meta_id);
                    $saved[] = 'Deleted related_1: ' . $row_style->related_1;
                }
            }

        //Guardar nuevos
            //Array general
                $arr_row['post_id'] = $project_id;
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
}