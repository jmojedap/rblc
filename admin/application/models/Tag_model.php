<?php
class Tag_model extends CI_Model{

    function basic($tag_id)
    {
        $row = $this->Db_model->row_id('tag', $tag_id);

        $data['tag_id'] = $tag_id;
        $data['row'] = $row;
        $data['head_title'] = $data['row']->name;
        $data['nav_2'] = 'tags/menu_v';

        return $data;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla tag.
     * 2020-02-22
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }

        $data = array('status' => 0);
        
        //Insert in table
            $this->db->insert('tag', $arr_row);
            $data['saved_id'] = $this->db->insert_id();

        if ( $data['saved_id'] > 0 ) { $data['status'] = 1; }
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla tag
     * 2020-02-22
     */
    function update($tag_id)
    {
        $data = array('status' => 0);

        //Guardar
            $arr_row = $this->Db_model->arr_row($tag_id);
            $saved_id = $this->Db_model->save('tag', "id = {$tag_id}", $arr_row);

        //Actualizar resultado
            if ( $saved_id > 0 ){ $data = array('status' => 1); }
        
        return $data;
    }

    /**
     * Array para insertar o actualizar un registro en la tabla tag
     * 2020-07-29
     */
    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['name'], 'tag');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// ELIMINACIÓN
//-----------------------------------------------------------------------------
    
    function deleteable()
    {
        $deleteable = 0;
        if ( $this->session->userdata('role') <= 1 ) { $deleteable = 1; }

        return $deleteable;
    }

    /**
     * Eliminar un usuario de la base de datos, se elimina también de
     * las tablas relacionadas
     */
    function delete($tag_id)
    {
        $qty_affected = 0;

        if ( $this->deleteable($tag_id) ) 
        {
            //Tablas relacionadas
                $this->db->query("DELETE FROM file_meta WHERE type_id = 27 AND related_1 = {$tag_id}");
            
            //Tabla principal
                $this->db->where('id', $tag_id);
                $this->db->delete('tag');

            $qty_affected = $this->db->affected_rows();
        }

        return $qty_affected;
    }
    
// EXPLORE FUNCTIONS - tags/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page);
        
        //Elemento de exploración
            $data['controller'] = 'tags';                      //Nombre del controlador
            $data['cf'] = 'tags/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'tags/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Tags';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    /**
     * Array con listado de users, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-01
     */
    function get($filters, $num_page, $per_page = 8)
    {
        //Referencia
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            //$data['list'] = $this->list($filters, $per_page, $offset);    //Resultados para página
            $data['list'] = $elements->result();
            $data['str_filters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['search_num_rows'] = $this->search_num_rows($data['filters']);
            $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }
    
    /**
     * Query de users, filtrados según búsqueda, limitados por página
     * 2020-08-01
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Construir consulta
            $this->db->select('tag.id, name, slug');
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('name', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('tag', $per_page, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar users
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition($filters['q'], array('name', 'slug'));
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['cat'] != '' ) { $condition .= "category_id = {$filters['cat']} AND "; }
        
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
        $query = $this->db->get('tag'); //Para calcular el total de resultados

        return $query->num_rows();
    }
    
    /**
     * Devuelve segmento SQL, para filtrar listado de usuarios según el rol del usuario en sesión
     * 2020-08-01
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún user, se obtendrían cero tag.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'tag.id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de user en la vista de
     * exploración
     * 
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Order by ]',
            'id' => 'ID Tag',
            'name' => 'Name'
        );
        
        return $order_options;
    }

    /**
     * Opciones de tag en campos de autollenado
     * 2020-08-01
     */
    function autocomplete($filters, $limit = 50)
    {
        //Construir condición de búsqueda
            $search_condition = $this->search_condition($filters);
        
        //Especificaciones de consulta
            $this->db->select('id, name AS value');
            if ( $search_condition ) { $this->db->where($search_condition);}
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get('tag', $limit); //Resultados por página
        
        return $query;
    }
}