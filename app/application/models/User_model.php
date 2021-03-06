<?php
class User_model extends CI_Model{

    function basic($user_id)
    {
        $data['user_id'] = $user_id;
        $data['row'] = $this->Db_model->row_id('users', $user_id);
        $data['head_title'] = substr($data['row']->display_name,0,50);
        $data['view_a'] = 'users/user_v';
        //$data['nav_2'] = 'users/menus/user_v';

        //if ( $data['row']->role == 13  ) { $data['nav_2'] = 'users/menus/provider_v'; }

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
        
        $role_filter = $this->role_filter($this->session->userdata('user_id'));

        //Construir consulta
            $this->db->select('user.id, username, display_name, first_name, last_name, email, role, image_id, url_image, url_thumbnail, status, user.type_id');
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
            
        $query = $this->db->get('users', $limit); //Resultados por página
        
        return $query;
    }

// CRUD
//-----------------------------------------------------------------------------
    
    /**
     * Insertar un registro en la tabla user.
     */
    function insert($arr_row = NULL)
    {
        if ( is_null($arr_row) ) { $arr_row = $this->arr_row('insert'); }
        
        //Insert in table
            $this->db->insert('users', $arr_row);
            $user_id = $this->db->insert_id();

        //Set result
            $data = array('status' => 1, 'saved_id' => $user_id);
        
        return $data;
    }

    /**
     * Actualiza un registro en la tabla.
     */
    function update($user_id, $arr_row)
    {
        $this->load->model('Account_model');
        $data_validation = $this->validate($user_id, $arr_row);  //Validar datos
        
        $data = $data_validation;
        
        if ( $data['status'] )
        {
            //Actualizar
                $this->db->where('id', $user_id);
                $this->db->update('users', $arr_row);
            
            //Preparar resultado
                $data['message'] = 'Los datos fueron guardados';
        }
        
        return $data;
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
    function delete($user_id)
    {
        $quan_deleted = 0;

        if ( $this->deletable($user_id) ) 
        {
            $this->db->where('id', $user_id);
            $this->db->delete('users');
            $quan_deleted = $this->db->affected_rows();
        }

        return $quan_deleted;
    }

    /**
     * Array con los datos para insertar, o actualizar un registro en la tabla user
     * 2020-05-18
     */
    function arr_row($process = 'update')
    {
        $this->load->model('Account_model');
        
        $arr_row = $this->input->post();
        
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $arr_row['status'] = 0; //Inactive, for auto register

        //Si se le estableció contraseña
        if ( isset($arr_row['password']) )
        {
            $arr_row['password'] = $this->Account_model->crypt_pw($arr_row['password']);
        }

        //if ( ! isset($arr_row['display_name']) ) { $arr_row['display_name'] = $arr_row['first_name'] . ' ' . $arr_row['last_name']; }
        if ( ! isset($arr_row['username']) ) { $arr_row['username'] = explode('@', $arr_row['email'])[0] . rand(100,999); }
        
        if ( $process == 'insert' )
        {
            $arr_row['creator_id'] = $this->session->userdata('user_id');
            $arr_row['status'] = 1; //Inserted by admin users
        }
        
        return $arr_row;
    }

// VALIDACIÓN
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un user nuevo o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     */
    function validate($user_id = NULL)
    {
        $data = array('status' => 1, 'message' => 'Los datos de usuario son válidos');
        $this->load->model('Validation_model');
        
        $username_validation = $this->Validation_model->username($user_id);
        $email_validation = $this->Validation_model->email($user_id);
        $id_number_validation = $this->Validation_model->id_number($user_id);

        $validation = array_merge($username_validation, $email_validation, $id_number_validation);
        $data['validation'] = $validation;

        foreach ( $validation as $value )
        {
            if ( $value == FALSE ) 
            {
                $data['status'] = 0;
                $data['message'] = 'Los datos de usuario NO son válidos';
            }
        }

        return $data;
    }

//IMAGEN DE PERFIL DE USUARIO
//---------------------------------------------------------------------------------------------------
    
    /**
     * Asigna una imagen registrada en la tabla archivo como imagen de perfil del usuario
     * 
     * @param type $user_id
     * @param type $file_id
     */
    function set_image($user_id, $file_id)
    {
        $data = array('status' => 0, 'message' => 'La imagen no fue asignada'); //Resultado inicial
        $row_file = $this->Db_model->row_id('files', $file_id);
        
        $arr_row['image_id'] = $row_file->id;
        $arr_row['url_image'] = $row_file->folder . $row_file->file_name;
        $arr_row['url_thumbnail'] = $row_file->folder . 'sm_' . $row_file->file_name;
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $arr_row);
        
        if ( $this->db->affected_rows() )
        {
            $data = array('status' => 1, 'message' => 'La imagen de perfil fue asignada');
            $data['src'] = URL_UPLOADS . $row_file->folder . $row_file->file_name;  //URL de la imagen cargada
        }

        return $data;
    }
    
    /**
     * Le quita la imagen de perfil asignada a un usuario, eliminado el archivo
     * correspondiente
     * 
     * @param type $user_id
     * @return int
     */
    function remove_image($user_id)
    {
        $data['status'] = 0;
        $row = $this->Db_model->row_id('users', $user_id);
        
        if ( ! is_null($row->image_id) )
        {
            $this->load->model('File_model');
            $this->File_model->delete($row->image_id);
            $data['status'] = 1;
        }
        
        return $data;
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

        if ( $type == 'clients' )
        {
            $data['help_note'] = 'Se importarán clientes a la BD';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f03_clientes.xlsx';
            $data['sheet_name'] = 'clientes';
            $data['head_subtitle'] = 'Importar clientes';
            $data['destination_form'] = "users/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa usuarios a la base de datos
     * 2019-11-21
     * 
     * @param type $array_sheet    Array con los datos de usuarios
     * @return type
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_client($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla user
     * 2020-01-22
     */
    function import_client($row_data)
    {
        //Validar
            $error_text = '';
            $row_place = $this->Db_model->row_id('places', $row_data[8]);
                            
            if ( strlen($row_data[3]) == 0 ) { $error_text = 'La casilla `Mostrar como` está vacía. '; }
            if ( strlen($row_data[5]) == 0 ) { $error_text = 'La casilla `No documento` está vacía. '; }
            if ( ! $this->Db_model->is_unique('users', 'id_number', $row_data[2]) ) { $error_text .= 'El No Documento (' . $row_data[2] . ') ya está registrado. '; }
            if ( ! $this->Db_model->is_unique('users', 'email', $row_data[14]) && strlen($row_data[14]) >= 5 ) { $error_text .= 'El correo electrónico (' . $row_data[14] . ') ya está registrado. '; }
            if ( is_null($row_place) ) { $error_text = "El ID de ciudad '{$row_data[8]}' no existe. "; }


        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_display_name = explode(' ', $row_data[3]);  //Para generar partes

                $arr_row['first_name'] = ( is_null($row_data[0]) ) ? $arr_display_name[0] : $row_data[0] ;
                $arr_row['last_name'] = ( is_null($row_data[1]) ) ? $arr_display_name[1] : $row_data[1] ;
                $arr_row['display_name'] = $row_data[3];
                $arr_row['short_note'] = ( is_null($row_data[4]) ) ? '' : $row_data[4] ;
                $arr_row['id_number'] = $row_data[5];
                $arr_row['id_number_type'] = $row_data[6];
                $arr_row['username'] = $this->generate_username($arr_row['first_name'], $arr_row['last_name']);
                $arr_row['gender'] = (is_null($row_data[7])) ? 0 : $row_data[7] ;
                $arr_row['city_id'] = $row_place->id;
                $arr_row['town_name'] = ( is_null($row_data[9]) ) ? '' : $row_data[9];
                $arr_row['shipping_system'] = $row_data[10];
                $arr_row['payment_channel'] = $row_data[11];
                $arr_row['address'] = ( is_null($row_data[12]) ) ? '' : $row_data[12];
                $arr_row['phone_number'] = ( is_null($row_data[13]) ) ? '' : $row_data[13];
                $arr_row['email'] = $row_data[14];
                $arr_row['keywords'] = $row_data[15];
                $arr_row['admin_notes'] = ( is_null($row_data[16]) ) ? '' : $row_data[16];

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

// GENERAL
//-----------------------------------------------------------------------------

    function generate_username($first_name, $last_name)
    {
        //Sin espacios iniciales o finales
        $first_name = trim($first_name);
        $last_name = trim($last_name);
        
        //Sin acentos
        $this->load->helper('text');
        $first_name = convert_accented_characters($first_name);
        $last_name = convert_accented_characters($last_name);
        
        //Arrays con partes
        $arr_last_name = explode(" ", $last_name);
        $arr_first_name = explode(" ", $first_name);
        
        //Construyendo por partes
            $username = $arr_first_name[0];
            //if ( isset($arr_first_name[1]) ){ $username .= substr($arr_first_name[1], 0, 2);}
            
            //Apellidos
            $username .= '_' . $arr_last_name[0];
            //if ( isset($arr_last_name[1]) ){ $username .= substr($arr_last_name[1], 0, 2); }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un suffix numérico para hacerlo único
            $suffix = $this->username_suffix($username);
            $username .= $suffix;
        
        return $username;
    }

    /**
     * Devuelve un entero aleatorio de tres cifras cuando el username generado inicialmente (generate_username)
     * ya exista dentro de la plataforma.
     * 2019-11-05
     */
    function username_suffix($username)
    {
        $suffix = '';
        
        $condition = "username = '{$username}'";
        $qty_users = $this->Db_model->num_rows('users', $condition);

        if ( $qty_users > 0 )
        {
            $this->load->helper('string');
            $suffix = random_string('numeric', 4);
        }
        
        return $suffix;
    }

// METADATA
//-----------------------------------------------------------------------------

    /**
     * Guarda un registro en la tabla meta
     * 2019-11-26
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
     * Elimina un registro de la tabla users_meta
     * 2020-05-27
     */
    function delete_meta($user_id, $meta_id)
    {
        $this->db->where('id', $meta_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('users_meta');
        
        $data['qty_deleted'] = $this->db->affected_rows();

        return $data;
    }

// ESPECIAL METADATA
//-----------------------------------------------------------------------------

    /**
     * Tags of User
     * 2020-05-14
     */
    function tags($user_id, $condition = 'tags.id > 0')
    {
        $this->db->select('name');
        $this->db->where($condition);
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->join('users_meta', 'tags.id = users_meta.related_1');
        $tags = $this->db->get('tags');

        return $tags;
    }

    /**
     * Professional services of User
     * 2020-05-14
     */
    function professional_services($user_id)
    {
        $this->db->select('items.cod AS id, item_name AS name, slug');
        $this->db->where('users_meta.type_id', 716);
        $this->db->where('items.category_id', 716);
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->join('users_meta', 'items.cod = users_meta.related_1');
        $elements = $this->db->get('items');

        return $elements;
    }

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
     * Imágenes asociadas al usuario, mediante la tabla users_meta, tipo 1
     * 2020-05-15
     */
    function images($user_id)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail');
        $this->db->where('users_meta.type_id', 1);
        $this->db->where('users_meta.user_id', $user_id);
        $this->db->join('users_meta', 'files.id = users_meta.related_1');
        $images = $this->db->get('files');

        return $images;
    }

    function first_image($user_id, $format = 'thumbnail')
    {
        $first_image['url'] = URL_IMG . 'users/user.png';
        $first_image['url_thumbnail'] = URL_IMG . 'users/sm_user.png';

        return $first_image;
    }

// ANOTACIONES
//-----------------------------------------------------------------------------

    function notes($user_id, $num_page)
    {
        $this->load->model('Note_model');

        
        
        return $data;
    }

// PÁGINA WEB Y REDES SOCIALES
//-----------------------------------------------------------------------------

    /**
     * Query con links de usuario
     * 2020-05-26
     */
    function social_links($user_id)
    {
        $this->db->select('users_meta.id, related_1, text_2 AS type, text_1 as url');
        $this->db->where('type_id', 1050);
        $this->db->where('user_id', $user_id);
        $social_links = $this->db->get('users_meta');

        return $social_links;
    }

    /**
     * Guardar registro de social link para un usuario
     * 2020-05-26
     */
    function save_social_link($user_id, $meta_id = 0)
    {
        $arr_row = $this->Db_model->arr_row($meta_id);
        $arr_row['type_id'] = 1050; //Link
        $arr_row['user_id'] = $user_id;
        $arr_row['text_2'] = $this->Db_model->field('items', "category_id = 44 AND cod = '{$this->input->post('related_1')}'", 'slug');

        $data['saved_id'] = $this->save_meta($arr_row, array('related_1'));

        return $data;
    }

// DATOS Y FUNCIONES ESPECIALES
//-----------------------------------------------------------------------------

    /**
     * Ideabooks creados por un usuario
     */
    function ideabooks($user_id)
    {
        $this->db->select('id, post_name AS title');
        $this->db->where('creator_id', $user_id);
        $this->db->where('type_id', 7120);
        $ideabooks = $this->db->get('posts', 50);

        return $ideabooks;
    }

// GESTIÓN DE SEGUIDORES
//-----------------------------------------------------------------------------

    /**
     * Proceso alternado, seguir o dejar de seguir un usuario de la plataforma
     * 2020-06-01
     */
    function alt_follow($user_id)
    {
        //Condición
        $condition = "user_id = {$user_id} AND type_id = 1011 AND related_1 = {$this->session->userdata('user_id')}";

        $row_meta = $this->Db_model->row('users_meta', $condition);

        $data = array('status' => 0);

        if ( is_null($row_meta) )
        {
            //No existe, crear (Empezar a seguir)
            $arr_row['user_id'] = $user_id;
            $arr_row['type_id'] = 1011; //Follower
            $arr_row['related_1'] = $this->session->userdata('user_id');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');

            $this->db->insert('users_meta', $arr_row);
            
            $data['saved_id'] = $this->db->insert_id();
            $data['status'] = 1;
        } else {
            //Existe, eliminar (Dejar de seguir)
            $this->db->where('id', $row_meta->id);
            $this->db->delete('users_meta');
            
            $data['qty_deleted'] = $this->db->affected_rows();
            $data['status'] = 2;
        }

        return $data;
    }

}