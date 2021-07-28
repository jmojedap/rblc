<?php
class Comment_model extends CI_Model{

    function basic($comment_id)
    {
        $row = $this->Db_model->row_id('comments', $comment_id);

        $data['comment_id'] = $comment_id;
        $data['row'] = $row;
        $data['head_title'] = substr($data['row']->comment_text,0,50);
        $data['view_a'] = 'comments/post_v';
        $data['nav_2'] = 'comments/menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - comments/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'comments';                      //Nombre del controlador
            $data['cf'] = 'comments/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'comments/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Posts';
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
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * String con condición WHERE SQL para filtrar post
     * 
     * @param type $filters
     * @return type
     */
    function search_condition($filters)
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
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('comment_id'));

        //Construir consulta
            //$this->db->select('id, post_name, except, ');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('post_name', 'content', 'excerpt', 'keywords'));
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
            $this->db->where($role_filter); //Filtro según el rol de post en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('comments'); //Resultados totales
        } else {
            $query = $this->db->get('comment', $per_page, $offset); //Resultados por página
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
     * Devuelve posto SQL
     * 
     * @param type $comment_id
     * @return type 
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
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     * 
     * @return string
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Comment',
            'created_at' => 'Fecha creación'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }

// CRUD FUNCTIONS
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla comments.
     * 2020-06-08
     */
    function save($table_id, $element_id)
    {
        $data = array('saved_id' => 0);

        if ( $this->insertable($element_id) )
        {
            $arr_row = $this->input->post();
            $arr_row['table_id'] = $table_id;       //Tabla del elemento comentado
            $arr_row['element_id'] = $element_id;   //ID del elemento comentado
            $arr_row['creator_id'] = $this->session->userdata('user_id');

            //Insertar en la tabla
                $this->db->insert('comments', $arr_row);
                $data['saved_id'] = $this->db->insert_id();

                //Actualizar los contadores
                $this->update_qty_comments($table_id, $element_id, 1);

                //Si es una respuesta, tiene padre, actualizar contadores
                if ( $arr_row['parent_id'] > 0 ) { $this->update_qty_answers($arr_row['parent_id'], 1); }
        }
        
        return $data;
    }

    /**
     * Verificar si los datos enviados por POST cumplen las condiciones para insertar
     * un comentario (comment)
     */
    function insertable($element_id)
    {
        $insertable = FALSE;
        $conditions = 0;

        if ( strlen($this->input->post('comment_text')) > 0 ) { $conditions++; }
        if ( strlen($element_id) > 0 ) { $conditions++; }

        if ( $conditions == 2 ) { $insertable = TRUE; }

        return $insertable;
    }
    


// INFO
//-----------------------------------------------------------------------------

    /**
     * Query con listado de comentarios, si se agrega $parent_id se filtran los subcomentarios
     * hechos al comentario con ID = $parent_id.
     */
    function element_comments($table_id, $element_id, $parent_id, $num_page)
    {
        $per_page = 25;
        $offset = $per_page * ($num_page - 1);

        $this->db->select('comments.id, comment_text, parent_id, score, qty_comments, comments.created_at, comments.creator_id, users.username, users.display_name');
        $this->db->where('element_id', $element_id);
        $this->db->where('table_id', $table_id);
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->join('users', 'users.id = comments.creator_id');
        $comments = $this->db->get('comments', $per_page, $offset);

        return $comments;
    }

// ELIMINACIÓN DE COMENTARIOS
//-----------------------------------------------------------------------------

    //Establece si un comentario puede ser eliminado o no por el usuario en sesión.
    function deleteable($comment_id, $element_id)
    {
        $deleteable = FALSE;
        $row = $this->Db_model->row('comments', "id = {$comment_id} AND element_id = {$element_id}");

        if ( ! is_null($row) )  //Existe
        {
            if ( $this->session->userdata('role') <= 2 ) { $deleteable = TRUE; }    //Tiene Rol Editor o superior
            if ( $this->session->userdata('user_id') == $row->creator_id ) { $deleteable = TRUE; }  //Es quien creó el comentario
        }

        return $deleteable;
    }

    /**
     * Delete a row in comment table
     */
    function delete($comment_id, $element_id)
    {
        $data['qty_deleted'] = 0;

        if ( $this->deleteable($comment_id, $element_id) )
        {
            //Tener registro para actualizaciones posteriores
            $row = $this->Db_model->row('comments', "id = {$comment_id} AND element_id = {$element_id}");

            //Eliminar comentario y sus descendientes
            $this->db->where("id = {$comment_id} OR parent_id = {$comment_id}");
            $this->db->delete('comments');

            $data['qty_deleted'] = $this->db->affected_rows();

            if ( $data['qty_deleted'] > 0 )
            {
                $this->update_qty_comments($row->table_id, $row->element_id, -1 * $data['qty_deleted']);
            }

            if ( $row->parent_id > 0 )
            {
                $this->update_qty_answers($row->parent_id, -1 * $data['qty_deleted']);
            }
        }
        
        return $data;
    }

// CÁLCULO DE CANTIDAD DE COMENTARIOS
//-----------------------------------------------------------------------------

    /**
     * Después de agregar o eliminar un comentario, se actualiza el campo posts.qty_comments.
     */
    function update_qty_comments($table_id, $element_id, $qty_sum = 1)
    {
        $table_name = $this->App_model->table_name($table_id);

        if ( ! is_null($qty_sum) ) {
            $sql = "UPDATE {$table_name} SET qty_comments = qty_comments + ({$qty_sum}) WHERE id = {$element_id}";
            $this->db->query($sql);
        } else {
            //Si $qty_sum es NULL, Se calcula el valor total desde la tabla comments
            $arr_row['qty_comments'] = $this->Db_model->num_rows('comment', "table_id = {$table_id} AND element_id = {$element_id}");
    
            $this->db->where('id', $element_id);
            $this->db->update($table_name, $arr_row);
        }
    }

    /**
     * Después de agregar o eliminar un comentario, se actualiza el campo comments.qty_comments.
     */
    function update_qty_answers($comment_id, $qty_sum = 1)
    {
        if ( ! is_null($qty_sum) ) {
            $sql = "UPDATE comments SET qty_comments = qty_comments + ({$qty_sum}) WHERE id = {$comment_id}";
            $this->db->query($sql);
        } else {
            //Si $qty_sum es NULL, Se calcula el valor total desde la tabla comments
            $arr_row['qty_comments'] = $this->Db_model->num_rows('comments', "parent_id = {$comment_id}");
    
            $this->db->where('id', $comment_id);
            $this->db->update('comments', $arr_row);
        }
    }
}