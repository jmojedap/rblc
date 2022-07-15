<?php
class Post_model extends CI_Model{

    function basic($post_id)
    {
        $row = $this->Db_model->row_id('posts', $post_id);

        $data['row'] = $row;
        $data['type_folder'] = $this->type_folder($row->type_id);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = $this->views_folder . 'post_v';
        $data['nav_2'] = $data['type_folder'] . 'menu_v';

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

// ELIMINACIÓN DE UN POST
//-----------------------------------------------------------------------------
    
    /**
     * Determina si el usuario en sesión tiene permiso para eliminar un registro en la tabla post
     * 2020-08-05
     */
    function deleteable($post_id)
    {
        $deleteable = 0;    //Valor por defecto
        $row = $this->Db_model->row_id('posts', $post_id);

        //Es administrador
        if ( $this->session->userdata('role') <= 2 ) $deleteable = 1;

        //Es el creador del post
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;

        return $deleteable;
    }

    /**
     * Eliminar un post de la base de datos, se eliminan registros en tablas relacionadas
     * 2020-08-04
     */
    function delete($post_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($post_id) ) 
        {
            //Tablas y relacionadas principal
                $this->db->query("DELETE FROM posts_meta WHERE post_id = {$post_id}");
                $this->db->query("DELETE FROM posts WHERE id = {$post_id}");

            $qty_deleted = $this->db->affected_rows();  //De la última consulta
        }

        return $qty_deleted;
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
            $data['controller'] = 'posts';                      //Nombre del controlador
            $data['cf'] = 'posts/explore/';                      //Nombre del controlador
            $data['views_folder'] = $this->views_folder . 'explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Posts';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page)
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
     * String con condición WHERE SQL para filtrar post
     */
    function search_condition_org($filters)
    {
        $condition = NULL;
        
        //Tipo de post
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    /**
     * Query con resultados de posts filtrados, por página y offset
     * 2020-07-15
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('id, post_name, excerpt, type_id');
        
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
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('post_name', 'content', 'excerpt', 'keywords'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "type_id = {$filters['type']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
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
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero posts.
        
        if ( $role <= 3 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Post',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un post ID, con un formato específico
     * 2021-01-04
     */
    function row($post_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $post_id);
        $query = $this->db->get('posts', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    function save()
    {
        $data['saved_id'] = $this->Db_model->save_id('posts');
        return $data;
    }

    /**
     * Nombre de la vista con el formulario para la edición del post. Puede cambiar dependiendo
     * del tipo (type_id).
     * 2021-06-09
     */
    function type_folder($type_id)
    {
        $special_types = array(7110);
        $type_folder = $this->views_folder;

        if ( in_array($type_id, $special_types) ) { $type_folder = "{$this->views_folder}/types/{$type_id}/"; }

        return $type_folder;
    }

// VALIDATION
//-----------------------------------------------------------------------------

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['post_name'], 'posts');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// IMAGEN PRINCIPAL DEL POST
//-----------------------------------------------------------------------------

    function att_img($row)
    {
        $att_img = array(
            'src' => URL_IMG . 'app/nd.png',
            'alt' => 'Imagen del Post ' . $row->id,
            'onerror' => "this.src='" . URL_IMG . "app/nd.png'"
        );

        $row_file = $this->Db_model->row_id('files', $row->image_id);
        if ( ! is_null($row_file) )
        {
            $att_img['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;
            $att_img['alt'] = $row_file->title;
        }

        return $att_img;
    }
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen del post
     */
    function set_image($post_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial
        $row_file = $this->Db_model->row_id('files', $file_id);
        
        $arr_row['image_id'] = $row_file->id;
        
        $this->db->where('id', $post_id);
        $this->db->update('posts', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen del post fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }

        return $data;
    }

    /**
     * Le quita la imagen asignada a un post, eliminado el archivo correspondiente
     * 2020-08-08
     */
    function remove_image($post_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('posts', $post_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;

            //Modificar Row en tabla Post
            $arr_row['image_id'] = 0;
            $this->db->where('image_id', $row->image_id);
            $this->db->update('posts', $arr_row);
        }
        
        return $data;
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al post, mediante la tabla posts_meta, tipo 1
     * 2022-03-19
     */
    function images($post_id)
    {
        $this->db->select('files.id, files.title, files.subtitle, description, 
            external_link, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '2000');       //Tabla post
        $this->db->where('related_1', $post_id);   //Relacionado con el post
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a un post como la imagen principal (tabla file)
     * 2022-03-19
     */
    function set_main_image($post_id, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 2000 AND related_1 = {$post_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$post_id}");

            //Actualizar registro en tabla post
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $post_id);
            $this->db->update('posts', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }

// METADATA
//-----------------------------------------------------------------------------

    /**
     * Insertar o actualizar registro en la tabla meta
     * 2020-07-02
     */
    function save_meta($post_id, $meta_id)
    {
        $arr_row = $this->arr_row_meta($post_id);
        $condition = "id = {$meta_id} AND post_id = {$post_id}";

        $data['saved_id'] = $this->Db_model->save('posts_meta', $condition, $arr_row);
    
        return $data;
    }

    /**
     * Elimina registro de la tabla posts_meta, requiere post y meta id, para confirmar
     * 2020-08-13
     */
    function delete_meta($post_id, $meta_id)
    {
        //Valores iniciales
        $data = array('status' => 0, 'qty_deleted' => 0);
        $conditions = 0;
        $row = $this->Db_model->row_id('posts', $post_id);

        //Comprobar permiso para eliminar
        if ( $this->session->userdata('role') <= 2 ) $conditions++;                      //Es administrador
        if ( $row->creator_id == $this->session->userdata('user_id') ) $conditions++;    //Es el creador del post

        //Si cumple al menos una de las condiciones
        if ( $conditions >= 1 )
        {
            $this->db->where('id', $meta_id);
            $this->db->where('post_id', $post_id);
            $this->db->delete('posts_meta');
            
            $data['qty_deleted'] = $this->db->affected_rows();
            $data['status'] = 1;
        }

        return $data;
    }

    /**
     * Construye array con registro para insertar o actualizar posts_meta
     * 2020-07-03
     */
    function arr_row_meta($post_id, $meta_id = 0)
    {
        $arr_row = $this->input->post();
        $arr_row['post_id'] = $post_id;
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $meta_id == 0 )
        {
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación según el tipo de usuario
     * que se va a importar.
     * 2019-11-20
     */
    function import_config($type)
    {
        $data = array();

        if ( $type == 'general' )
        {
            $data['help_note'] = 'Se importarán posts a la base de datos.';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f50_posts.xlsx';
            $data['sheet_name'] = 'posts';
            $data['head_subtitle'] = 'Importar';
            $data['destination_form'] = "posts/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa posts a la base de datos
     * 2020-02-22
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_post($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla post, y agrega al grupo asignado.
     * 2020-02-22
     */
    function import_post($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla Nombre está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla Cod Tipo está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla Resumen está vacía. '; }
            if ( strlen($row_data[14]) == 0 ) { $error_text .= 'La casilla Fecha Publicación está vacía. '; }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['post_name'] = $row_data[0];
                $arr_row['type_id'] = $row_data[1];
                $arr_row['excerpt'] = $row_data[2];
                $arr_row['content'] = $row_data[3];
                $arr_row['content_json'] = $row_data[4];
                $arr_row['keywords'] = $row_data[5];
                $arr_row['code'] = $row_data[6];
                $arr_row['place_id'] = $this->pml->if_strlen($row_data[7], 0);
                $arr_row['related_1'] = $this->pml->if_strlen($row_data[8], 0);
                $arr_row['related_2'] = $this->pml->if_strlen($row_data[9], 0);
                $arr_row['image_id'] = $this->pml->if_strlen($row_data[10], 0);
                $arr_row['text_1'] = $this->pml->if_strlen($row_data[11], '');
                $arr_row['text_2'] = $this->pml->if_strlen($row_data[12], '');
                $arr_row['status'] = $this->pml->if_strlen($row_data[13], 2);
                $arr_row['published_at'] = $this->pml->dexcel_dmysql($row_data[14]);
                $arr_row['slug'] = $this->Db_model->unique_slug($row_data[0], 'posts');
                
                $arr_row['creator_id'] = $this->session->userdata('user_id');
                $arr_row['updater_id'] = $this->session->userdata('user_id');

                //Guardar en tabla user
                $data_insert = $this->insert($arr_row);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// GESTIÓN DE LIKES
//-----------------------------------------------------------------------------

    /**
     * Proceso alternado, like o unlike de un post
     * 2020-07-17
     */
    function alt_like($post_id)
    {
        //Condición
        $condition = "post_id = {$post_id} AND type_id = 10 AND related_1 = {$this->session->userdata('user_id')}";

        $row_meta = $this->Db_model->row('posts_meta', $condition);

        $data = array();

        if ( is_null($row_meta) )
        {
            //No existe, crear (Empezar a seguir)
            $arr_row['post_id'] = $post_id;
            $arr_row['type_id'] = 10; //Like
            $arr_row['related_1'] = $this->session->userdata('user_id');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');

            $this->db->insert('posts_meta', $arr_row);
            
            $data['saved_id'] = $this->db->insert_id();
            $data['qty_sum'] = 1;
            $data['like_status'] = 1;
        } else {
            //Existe, eliminar (Dejar de seguir)
            $this->db->where('id', $row_meta->id);
            $this->db->delete('posts_meta');
            
            $data['qty_sum'] = -1;
            $data['like_status'] = 0;
        }

        //Actualizar contador en registro tabla post
        $this->db->query("UPDATE posts SET qty_likes = (qty_likes + ({$data['qty_sum']})) WHERE id = {$post_id}");

        return $data;
    }

    /**
     * Posts, liked por un usuario
     * 2020-07-15
     */
    function liked($user_id, $condition = NULL)
    {
        $this->db->select('posts.id, post_name AS name, posts.slug, url_image, url_thumbnail, excerpt, posts_meta.id AS meta_id');
        $this->db->join('posts_meta', 'posts.id = posts_meta.post_id');
        $this->db->where('posts_meta.related_1', $user_id);
        $this->db->where('posts_meta.type_id', 10);    //Follower
        if ( ! is_null($condition) ) { $this->db->where($condition); }
        $this->db->order_by('posts_meta.created_at', 'DESC');
        $posts = $this->db->get('posts');

        return $posts;
    }

    /**
     * Devuelve 0 o 1, dependiendo si el usuario en sesión like o no un post
     * 2020-07-27
     */
    function like_status($post_id)
    {
        $like_status = 0;
        if ( $this->session->userdata('user_id') )
        {
            $like_status = $this->Db_model->num_rows('posts_meta', "post_id = {$post_id} AND type_id = 10 AND related_1 = {$this->session->userdata('user_id')}");
        }

        return $like_status;
    }
}