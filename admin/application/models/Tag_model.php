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
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
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
        if ( $filters['cat'] != '' ) { $condition .= "category_id = {$filters['cat']} AND "; }
        
        if ( strlen($condition) > 0 )
        {
            $condition = substr($condition, 0, -5);
        }
        
        return $condition;
    }
    
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $role_filter = $this->role_filter($this->session->userdata('tag_id'));

        //Construir consulta
            //$this->db->select('id, post_name, except, ');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('name', 'slug'));
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
                $this->db->order_by('name', 'ASC');
            }
            
        //Filtros
            $this->db->where($role_filter); //Filtro según el rol de usuario en sesión
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
        if ( is_null($per_page) )
        {
            $query = $this->db->get('tag'); //Resultados totales
        } else {
            $query = $this->db->get('tag', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
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
     * Devuelve segmento SQL, con filtro según el rol
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero tag.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de tag en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Tag',
            'post_name' => 'Nombre'
        );
        
        return $order_options;
    }
    
    function editable()
    {
        return TRUE;
    }
}