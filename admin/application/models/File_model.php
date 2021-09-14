<?php
class File_model extends CI_Model{

// INFO FUNCTIONS
//-----------------------------------------------------------------------------

    function basic($file_id)
    {
        $row = $this->Db_model->row_id('files', $file_id);

        $data['file_id'] = $file_id;
        $data['row'] = $row;
        $data['src'] = URL_UPLOADS . $row->folder . $row->file_name;
        $data['url_thumbnail'] = URL_UPLOADS . $row->folder . 'sm_' . $row->file_name;
        $data['path_file'] = PATH_UPLOADS . $row->folder . $row->file_name;
        $data['head_title'] = substr($data['row']->title, 0, 50);

        return $data;
    }

// EXPLORE FUNCTIONS - FILES/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($num_page)
    {
        //Data inicial, de la tabla
            $data = $this->get($num_page);
        
        //Elemento de exploración
            $data['controller'] = 'files';                      //Nombre del controlador
            $data['cf'] = 'files/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'files/explore/';           //Carpeta donde están las vistas de exploración
            
        //Vistas
            $data['head_title'] = 'Archivos';
            $data['head_subtitle'] = $data['search_num_rows'];
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($num_page)
    {
        //Referencia
            $per_page = 50;                             //Cantidad de registros por página
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
     * String con condición WHERE SQL para filtrar user
     */
    function search_condition($filters)
    {
        $condition = NULL;
        
        //Rol de user
        //if ( $filters['role'] != '' ) { $condition .= "role = {$filters['role']} AND "; }
        
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
            $this->db->select('*');
        
        //Crear array con términos de búsqueda
            $words_condition = $this->Search_model->words_condition($filters['q'], array('file_name', 'title', 'folder', 'description', 'keywords'));
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
            $query = $this->db->get('files'); //Resultados totales
        } else {
            $query = $this->db->get('files', $per_page, $offset); //Resultados por página
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
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Valor por defecto, ningún user, se obtendrían cero users.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los user
            $condition = 'files.id > 0';
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
            'id' => 'ID File',
            'file_name' => 'File name'
        );
        
        return $order_options;
    }
    
//PROCESO UPLOAD
//---------------------------------------------------------------------------------------------------
    
    /**
     * Realiza el upload de un file al servidor, crea el registro asociado en
     * la tabla "file".
     */
    function upload($file_id = NULL)
    {
        $config_upload = $this->config_upload();
        $this->load->library('upload', $config_upload);

        if ( $this->upload->do_upload('file_field') )  //Campo "file_field" del formulario
        {
            $upload_data = $this->upload->data();
            $this->mod_original($upload_data['full_path']);          //Modificar imagen original

            //Guardar registro en la tabla "file"
                $row = $this->save($file_id, $upload_data);
                
            //Si es imagen, se generan miniaturas y edita imagen original
                if ( $row->is_image ) { $this->create_thumbnails($row); }
            
            //Array resultado
                $data = array('status' => 1);
                //$data['upload_data'] = $this->upload->data();
                $data['row'] = $row;
        }
        else    //No se cargó
        {
            $data = array('status' => 0);
            $data['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-warning"></i> ', '</div>');
        }
        
        return $data;
    }
    
    /**
     * Configuración para cargue de files, algunas propiedades solo se aplican
     * para files de imagen.
     */
    function config_upload()
    {
        $this->load->helper('string');  //Para activar función random_string
        
        $config['upload_path'] = PATH_UPLOADS . date('Y/m');    //Carpeta año y mes
        $config['allowed_types'] = 'zip|gif|jpg|png|jpeg|pdf|json';
        $config['max_size']	= '5000';       //Tamaño máximo en Kilobytes
        $config['max_width']  = '10000';     //Ancho máxima en pixeles
        $config['max_height']  = '10000';    //Altura máxima en pixeles
        $config['file_name']  = $this->session->userdata('user_id') . '_' . date('YmdHis') . '_' . random_string('numeric', 2);
        
        return $config;
    }
    
// GESTIÓN DE REGISTROS EN LA TABLA file
//-----------------------------------------------------------------------------

    /**
     * Determina si un archivo puede ser editado o no por parte de un usuario en sesión
     * 2019-05-21
     */
    function editable($file_id)
    {
        $row = $this->Db_model->row_id('files', $file_id);

        $editable = FALSE;

        //Administradores y editores
        if ( $this->session->userdata('role') <= 2 ) { $editable = TRUE; }   

        //Es el creador, puede editarlo
        if ( $row->creator_id == $this->session->userdata('user_id') )
        {
            $editable = TRUE;
        }

        return $editable;
    }

    /**
     * Guardar registro del archivo en la tabla file
     */
    function save($file_id, $upload_data)
    {
        if ( is_null($file_id) ) {
            $file_id = $this->insert($upload_data);  //Crear nuevo registro
        } else {
            $this->change($file_id, $upload_data);  //Cambiar el archivo y modificar el registro
        }

        $row = $this->Db_model->row_id('files', $file_id);

        return $row;
    }
    
    /**
     * Crea el registro del file en la tabla file
     * 2020-03-03, agregar cat_1
     */
    function insert($upload_data)
    {
        //Construir registro
            $arr_row['file_name'] = $upload_data['file_name'];
            $arr_row['ext'] = $upload_data['file_ext'];
            $arr_row['keywords'] = $this->pml->if_strlen($this->input->post('keywords'), '');
            $arr_row['title'] = str_replace($upload_data['file_ext'], '', $upload_data['client_name']);  //Para quitar la extensión y punto
            $arr_row['folder'] = date('Y/m/');
            $arr_row['url'] = URL_UPLOADS . $arr_row['folder'] . $upload_data['file_name'];
            $arr_row['url_thumbnail'] = URL_UPLOADS . $arr_row['folder'] . 'sm_' . $upload_data['file_name'];
            $arr_row['type'] = $upload_data['file_type'];
            $arr_row['is_image'] = $upload_data['is_image'];    //Definir si es imagen o no
            $arr_row['meta'] = json_encode($upload_data);
            $arr_row['table_id'] = ( ! is_null($this->input->post('table_id')) ) ? $this->input->post('table_id') : 0;
            $arr_row['related_1'] = ( ! is_null($this->input->post('related_1')) ) ? $this->input->post('related_1') : 0;
            $arr_row['album_id'] = ( ! is_null($this->input->post('album_id')) ) ? $this->input->post('album_id') : 0;
            $arr_row['cat_1'] = ( ! is_null($this->input->post('cat_1')) ) ? $this->input->post('cat_1') : 0;
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['created_at'] = date('Y-m-d H:i:s');
            $arr_row['creator_id'] = $this->session->userdata('user_id');

        //Obtener dimensiones
            $arr_dimensions = $this->arr_dimensions($upload_data['full_path']);
            $arr_row = array_merge($arr_row, $arr_dimensions);
            
        //Insertar
            $this->db->insert('files', $arr_row);

        return $this->db->insert_id();
    }

    /**
     * Array con dimensiones de ancho, alto y tamaño de archivo
     * 2020-07-06
     */
    function arr_dimensions($file_path)
    {
        $image_size = getimagesize($file_path);

        $dimensions['width'] = $image_size[0];
        $dimensions['height'] = $image_size[1];
        $dimensions['size'] = intval(filesize($file_path)/1028);    //Tamaño en KB

        return $dimensions;
    }

    /**
     * Actualiza campos de dimensiones de registro en la tabla file
     * 2020-08-08
     */
    function update_dimensions($file_id)
    {
        $row = $this->Db_model->row_id('files', $file_id);

        $arr_row = $this->arr_dimensions(PATH_UPLOADS . $row->folder . $row->file_name);

        $this->db->where('id', $file_id);
        $this->db->update('files', $arr_row);
        
        return $this->db->affected_rows();
    }

    /**
     * Modificar la imagen original con un tamaño específico máximo, tomando el 
     * row_file
     */
    function modify_image($row_file)
    {
        $modified = $this->mod_original($row_file);
        
        return $modified;
    }
    
    /**
     * Modifica la imagen original con un tamaño específico máximo
     * 2020-07-06
     */
    function mod_original($file_path)
    {
        $modified = 0;
        $config['source_image'] = $file_path;
        $image_size = getimagesize($config['source_image']);
        
        $pixels = 800;   //Tamaño máximo 800px
        
        //Verificar si se modifica
        $qty_conditions = 0;
        if ( $image_size[0] > $pixels ) { $qty_conditions++; }
        if ( $image_size[1] > $pixels ) { $qty_conditions++; }
        
        if ( $qty_conditions > 0 )
        {
            //Resize
            $this->load->library('image_lib');
            $config['image_library'] = 'gd2';
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = 90;
            //Dimensiones
            if ( $image_size[0] > $image_size[1] )
            {
                $config['width'] = $pixels;
            } else {
                $config['height'] = $pixels;
            }

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();

            $modified = 1;
        }
        
        return $modified;
    }

// MINIATURAS
//-----------------------------------------------------------------------------
    
    /**
     * Crea los files miniaturas de una imagen y la recorta cuadrada
     */
    function create_thumbnails($row_file)
    {
        $this->create_thumbnail($row_file, 'sm_', 300);
        $this->square_image($row_file, 'sm_');
    }
    
    /**
     * Crea la miniatura de una imagen
     * 2020-07-06
     */
    function create_thumbnail($row_file, $prefix, $pixels)
    {
        $this->load->library('image_lib');
        
        //Config
            $config['image_library'] = 'gd2';
            $config['source_image'] = PATH_UPLOADS . $row_file->folder . $row_file->file_name;
            $config['new_image'] = PATH_UPLOADS . $row_file->folder . $prefix . $row_file->file_name;
            $config['maintain_ratio'] = TRUE;
            $config['quality'] = 90;
            if ( $row_file->width > $row_file->height )
            {
                $config['height'] = $pixels;
            } else {
                $config['width'] = $pixels;
            }

            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->image_lib->clear();
    }

    /**
     * Recorta una imagen existente de forma cuadrada
     */
    function square_image($row_file, $prefix = 'sm_')
    {
        $this->load->library('image_lib');
        $config['maintain_ratio'] = FALSE;
        $config['image_library'] = 'gd2';
        $config['library_path'] = '/usr/X11R6/bin/';
        $config['source_image'] = PATH_UPLOADS . $row_file->folder . $prefix . $row_file->file_name;
     
        //Calcular dimensiones
        $image_size = getimagesize($config['source_image']);
        if ( $image_size[0] > $image_size[1] )
        {
            //Horizontal
            $config['y_axis'] = 0;
            $config['x_axis'] = intval(($image_size[0] - $image_size[1]) * 0.5);
            $config['width'] = $image_size[1];
            $config['height'] = $image_size[1];
        } else {
            //Vertical
            $config['y_axis'] = intval(($image_size[1] - $image_size[0]) * 0.5);
            $config['x_axis'] = 0;
            $config['width'] = $image_size[0];
            $config['height'] = $image_size[0];
        }

        $this->image_lib->initialize($config);
        $this->image_lib->crop();
        $this->image_lib->clear();
    }

// EDICIÓN Y CAMBIO
//-----------------------------------------------------------------------------
    
    /**
     * Actualizar registro en la tabla file
     * 2019-09-14
     */
    function update($file_id, $arr_row)
    {
        $this->db->where('id', $file_id);
        $this->db->update('files', $arr_row);
        
        $data = array('status' => 1);

        return $data;
    }

    /**
     * Edita el registro del file, tabla files. El file en el servidor
     * es cambiado, y el registro en la tabla registro es actualizado.
     */
    function change($file_id, $upload_data)
    {
        //Construir registro
            $arr_row['file_name'] = $upload_data['file_name'];
            $arr_row['folder'] = date('Y/m/');
            $arr_row['ext'] = $upload_data['file_ext'];
            $arr_row['type'] = $upload_data['file_type'];
            $arr_row['is_image'] = $upload_data['is_image'];    //Definir si es imagen o no
            $arr_row['meta'] = json_encode($upload_data);
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
            
        //Actualizar
            $this->db->where('id', $file_id);
            $this->db->update('files', $arr_row);

        $row_file = $this->Db_model->row_id('files', $file_id);
            
        return $row_file;
    }
    
// ELIMINACIÓN
//-----------------------------------------------------------------------------

    function deleteable($file_id)
    {
        $deleteable = false;

        //Administradores pueden eliminar
        if ( $this->session->userdata('role') <= 2 ){
            $deleteable = true; 
        } else {
            $row = $this->Db_model->row_id('files', $file_id);
            if ( ! is_null($row) )
            {
                //Si es el usuario creador
                if ( $row->creator_id == $this->session->userdata('user_id') ) { $deleteable = true; }
                //Si es el usuario asociado
                if ( $row->table_id == 1000 && $row->related_1 == $this->session->userdata('user_id') ) { $deleteable = true; }
            }
        }
        return $deleteable;
    }
    
    /**
     * Elimina file del servidor y sus miniaturas y el el registro en la 
     * tabla files.
     */
    function delete($file_id)
    {   
        $data = array('status' => 403, 'qty_deleted' => 0);

        if ( $this->deleteable($file_id) )
        {
            //Eliminar files del servidor
                $row_file = $this->Db_model->row_id('files', $file_id);
                if ( ! is_null($row_file) ) 
                {
                    $this->unlink($row_file->folder, $row_file->file_name);
                }
            
            //Eliminar registros de la base de datos
                $data['status'] = 1;
                $data['qty_deleted'] = $this->delete_rows($file_id);
        }

        return $data;
    }
    
    /**
     * Elimina de la BD los registros asociados al file
     */
    function delete_rows($file_id)
    {
        $qty_deleted = 0;

        //Desvincular registro de files con otros elementos
            $this->delete_related_rows($file_id);
        
        //Tabla file
            if ( $file_id > 0 )
            {
                $this->db->where('id', $file_id);
                $this->db->delete('files');

                $qty_deleted = $this->db->affected_rows();
            }

        return $qty_deleted;
    }
    
    /**
     * Elimina los registros que relacionan al file con otros elementos de la
     * base de datos. Tambien edita los fields de registros referentes al 
     * file_id
     */
    function delete_related_rows($file_id)
    {
        //Imagen de perfil de usuario
            $arr_row['image_id'] = 0;
            $arr_row['url_image'] = '';
            $arr_row['url_thumbnail'] = '';
            $this->db->where('image_id', $file_id);
            $this->db->update('users', $arr_row);

        //Imagen de post
            $arr_row_post['image_id'] = 0;
            $arr_row_post['url_image'] = '';
            $arr_row_post['url_thumbnail'] = '';
            $this->db->where('image_id', $file_id);
            $this->db->update('posts', $arr_row_post);

        //Otras Aplicación
            $this->db->query("DELETE FROM posts_meta WHERE type_id = 1 AND related_1 = {$file_id}"); //Imágen de post
            $this->db->query("DELETE FROM users_meta WHERE type_id = 1 AND related_1 = {$file_id}"); //Imágen de usuario
    }

    /**
     * Elimina un archivo y sus miniaturas del servidor
     */
    function unlink($folder, $file_name)
    {
        $qty_unlinked = 0;

        $files[] = PATH_UPLOADS . "{$folder}{$file_name}";
        $files[] = PATH_UPLOADS . "{$folder}sm_{$file_name}";
        $files[] = PATH_UPLOADS . "{$folder}md_{$file_name}";

        foreach ( $files as $file_path )
        {
            if ( file_exists($file_path) ) 
            {
                unlink($file_path);
                $qty_unlinked++;
            }
        }
        
        return $qty_unlinked;
    }
    
// DATOS
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con los atributos de una imagen, para ser usado con la funcion img();
     * 2019-09-19
     */
    function att_img($file_id, $prefix = '')
    {
        $att_img = array(
            'src' => URL_IMG . 'app/sm_nd_square.png',
            'alt' => 'Imagen no disponible',
            'onerror' => "this.src='" . URL_IMG . 'app/' . $prefix . 'nd_square.png' . "'"
        );
        
        $row_file = $this->Db_model->row_id('files', $file_id);

        if ( ! is_null($row_file) )
        {
            $att_img = array(
                'src' => URL_UPLOADS . $row_file->folder . $prefix . $row_file->file_name,
                'alt' => $row_file->file_name,
                'style' => 'width: 100%',
                'onerror' => "this.src='" . URL_IMG . 'app/' . $prefix . 'nd_square.png' . "'"
            );
        }
        
        return $att_img;
    }
    
    /**
     * Array con atributos de la miniatura de un archivo imagen
     * 2019-09-14
     */
    function att_thumbnail($file_id)
    {
        $src = URL_IMG . 'app/sm_nd_square.png';

        $row_file = $this->Db_model->row_id('files', $file_id);

        if ( ! is_null($row_file))
        {
            $src = URL_UPLOADS . $row_file->folder . 'sm_' . $row_file->file_name;
            if ( ! $row_file->is_image ) { $src = URL_IMG . 'app/file.png'; }
        }
        
        $att_img = array(
            'src' => $src,
            'alt' => 'Miniatura',
            'style' => 'width: 100%',
            'onerror' => "this.src='" . URL_IMG . 'app/sm_nd_square.png' . "'"
        );
        
        return $att_img;
    }
    
    function row_img($file_id, $prefix = '')
    {
        $row_img = NULL;
        
        $select = '*, CONCAT("' . URL_UPLOADS . '", (folder), "' . $prefix . '", (file_name)) AS src';
        
        $this->db->select($select);
        $this->db->where('id', $file_id);
        $query = $this->db->get('files');
        
        if ( $query->num_rows() > 0 ) { $row_img = $query->row(); }
        
        return $row_img;
    }
    
    /**
     * Le quita el prefijo a un nombre de file
     */
    function remove_prefix($file_name)
    {
        $prefixs = $this->prefixes();
        $without_prefix = $file_name;
        
        foreach ( $prefixs as $prefix ) 
        {
            $prefix = $prefix . '_';
            $without_prefix = str_replace($prefix, '', $without_prefix);
        }
        
        return $without_prefix;
    }
    
    /**
     * Recorta una imagen con unos datos específicos, actualiza las miniaturas
     * según el recorte.
     */
    function crop($file_id)
    {
        
        //Valores iniciales
            $row = $this->Db_model->row_id('files', $file_id);
            $data = array('status' => 0, 'message' => 'Imagen NO recortada');
        
        //Configuración de recorte
            $this->load->library('image_lib');
            
            $config['image_library'] = 'gd2';
            $config['library_path'] = '/usr/X11R6/bin/';
            $config['source_image'] = PATH_UPLOADS . $row->folder . $row->file_name;
            $config['width'] = $this->input->post('width');
            $config['height'] = $this->input->post('height');
            $config['x_axis'] = $this->input->post('x_axis');
            $config['y_axis'] = $this->input->post('y_axis');
            $config['maintain_ratio'] = FALSE;
        
            $this->image_lib->initialize($config);
            
        //Ejecutar recorte
            if ( $this->image_lib->crop() )
            {
                $this->update_dimensions($file_id);
                $this->create_thumbnails($row);
                $data = array('status' => 1, 'message' => 'Imagen recortada');
            } else {
                $data['html'] = $this->image_lib->display_errors();
            }
        
        return $data;
    }
    
//GESTIÓN DE ARCHIVOS EN CARPETAS
//---------------------------------------------------------------------------------------------------
    
    /**
     * Listado de files en una folder
     */
    function files($year, $month)
    {
        $this->load->helper('file');
        $files = get_filenames(PATH_UPLOADS . $year . '/' . $month);
        
        return $files;
    }
    
    /**
     * Elimina los files que no están siendo utilizados en la herramienta
     * Se considera no usado si no tiene registro asociado en la tabla "file"
     */
    function unlink_unused($year, $month)
    {
        $quan_deleted = 0;
        $this->load->helper('file');
        $files = get_filenames(PATH_UPLOADS . $year . '/' . $month);
        
        $folder = "{$year}/{$month}/";
        
        foreach( $files as $file_name )
        {    
            $without_prefix = $this->remove_prefix($file_name);
            $has_row = $this->has_row($folder, $without_prefix);
            
            if ( ! $has_row ) { 
                $quan_deleted += $this->unlink($folder, $without_prefix);
            }
        }
        
        return $quan_deleted;
    }
    
    /**
     * Devuelve 1/0, verifica si un file tiene registro relacionado
     * en la tabla "file"
     */
    function has_row($folder, $file_name)
    {
        $has_row = 0;
        
        $this->db->where('folder', $folder);
        $this->db->where('file_name', $file_name);
        $query = $this->db->get('files');
        
        if ( $query->num_rows() > 0 ) { $has_row = 1; }
        
        return $has_row;
    }

// METADATOS tabla files_meta
//-----------------------------------------------------------------------------

    /**
     * Metadatos general, asociados a cualquier tabla
     * 2020-08-11
     */
    function metadata_flat($file_id, $type_id)
    {
        $this->db->select('files_meta.id AS meta_id, files_meta.related_1');
        $this->db->where('type_id', $type_id);
        $this->db->where('file_id', $file_id);
        $elements = $this->db->get('files_meta');

        return $elements;
    }

    /**
     * Metadatos de file, asociados con la tabla item
     */
    function metadata($file_id, $type_id)
    {
        $this->db->select('files_meta.id AS meta_id, item_name AS title, files_meta.related_1');
        $this->db->where('files_meta.type_id', $type_id);
        $this->db->where('items.category_id', $type_id);
        $this->db->where('files_meta.file_id', $file_id);
        $this->db->join('files_meta', 'items.cod = posts_meta.related_1');
        $elements = $this->db->get('items');

        return $elements;
    }

    /**
     * Query, tags de un file, tomados de la tabla files_meta
     */
    function tags($file_id)
    {
        $this->db->select('id, name, slug');
        $this->db->where("id IN (SELECT related_1 FROM files_meta WHERE file_id = {$file_id} AND type_id = 27)");
        $tags = $this->db->get('tags');

        return $tags;
    }

    /**
     * Guarda un registro en la tabla files_meta
     * 2020-07-16
     */
    function save_meta($arr_row, $fields = array('related_1'))
    {
        $condition = "file_id = {$arr_row['file_id']} AND type_id = {$arr_row['type_id']}";

        foreach ($fields as $field)
        {
            $condition .= " AND {$field} = '{$arr_row[$field]}'";
        }

        $meta_id = $this->Db_model->save('files_meta', $condition, $arr_row);
        
        return $meta_id;
    }

    /**
     * Elimina registro de la tabla files_meta, requiere post y meta id, para confirmar
     * 2020-07-03
     */
    function delete_meta($file_id, $meta_id)
    {
        $this->db->where('id', $meta_id);
        $this->db->where('file_id', $file_id);
        $this->db->delete('files_meta');
        
        $data['qty_deleted'] = $this->db->affected_rows();

        return $data;
    }

    /**
     * Guarda múltiples registros en la tabla files_meta, con un array,
     * y elimina los que no estén en el array enviado por post ($new_metas)
     * 2020-08-10
     */
    function save_meta_array($file_id, $type_id, $new_metas)
    {        
        $saved = array();                   //Resultado por defecto

        //Eliminar los que ya no están en $new_metas
            $old_meta = $this->metadata_flat($file_id, $type_id);

            foreach ( $old_meta->result() as $row_meta ) 
            {
                if ( ! in_array($row_meta->related_1, $new_metas) )
                {
                    $this->delete_meta($file_id, $row_meta->meta_id);
                    $saved[] = 'Deleted related_1: ' . $row_meta->related_1;
                }
            }

        //Guardar nuevos
            //Array general
                $arr_row['file_id'] = $file_id;
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

// INTERACCIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Proceso alternado, like or unlike una imagen, registro type 10 en la tabla files_meta
     * 2021-09-13
     */
    function alt_like($file_id)
    {
        //Condición
        $condition = "file_id = {$file_id} AND type_id = 10 AND related_1 = {$this->session->userdata('user_id')}";

        $row_meta = $this->Db_model->row('files_meta', $condition);

        $data = array('status' => 0);

        if ( is_null($row_meta) )
        {
            //No existe: like
            $arr_row['file_id'] = $file_id;
            $arr_row['type_id'] = 10; //Like
            $arr_row['related_1'] = $this->session->userdata('user_id');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');

            $this->db->insert('files_meta', $arr_row);
            
            $data['saved_id'] = $this->db->insert_id();
            $data['qty_sum'] = 1;
            $data['like_status'] = 1;
        } else {
            //Existe, eliminar (Unlike)
            $this->db->where('id', $row_meta->id);
            $this->db->delete('files_meta');
            
            $data['qty_deleted'] = $this->db->affected_rows();
            $data['qty_sum'] = -1;
            $data['like_status'] = 0;
        }

        //Actualizar contador en registro tabla files
        $this->db->query("UPDATE files SET qty_likes = (qty_likes + ({$data['qty_sum']})) WHERE id = {$file_id}");

        return $data;
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

// CAMPO files.searcher
//-----------------------------------------------------------------------------

    /**
     * Actualiza el campo files.searcher de cada registro con ID mayor o igual a $min_id
     * 2020-07-28
     */
    function update_searcher_multi($min_id)
    {
        //Resultado inicial
        $data = array('qty_affected' => 0);

        //Seleccionar archivos
        $this->db->where('id >=', $min_id);
        $files = $this->db->get('files');

        foreach ($files->result() as $row)
        {
            $data['qty_affected'] += $this->update_searcher($row->id);
        }

        return $data;
    }

    /**
     * Actualiza el campo files.searcher, para un registro específico
     * 2020-07-28
     */
    function update_searcher($file_id)
    {
        $arr_row['searcher'] = $this->searcher_value($file_id);

        //Actualizar
        $this->db->where('id', $file_id)->update('files', $arr_row);

        return $this->db->affected_rows();
    }

    /**
     * String para llenar el campo files.searcher
     * 2020-07-28
     */
    function searcher_value($file_id)
    {
        $searcher = '';
        
        //Cargando tags
        $tags = $this->tags($file_id);
        foreach ($tags->result() as $row_tag) { $searcher .= $row_tag->name . ', '; }

        //Quitar la última coma y espacio
        if ( strlen($searcher) > 0 ) { $searcher = substr($searcher, 0, -2); }

        return $searcher;
    }

// CAMPO files.priority
//-----------------------------------------------------------------------------

    /**
     * Actualiza el campo files.priority de cada registro con ID mayor o igual a $min_id
     * 2020-07-28
     */
    function update_priority_multi($min_id)
    {
        //Resultado inicial
        $data = array('qty_affected' => 0);

        //Seleccionar archivos
        $this->db->where('id >=', $min_id);
        $files = $this->db->get('files');

        foreach ($files->result() as $row)
        {
            $data['qty_affected'] += $this->update_priority($row->id);
        }

        return $data;
    }

    /**
     * Actualiza el campo files.searcher, para un registro específico
     * 2020-07-28
     */
    function update_priority($file_id)
    {
        $arr_row['priority'] = $this->priority_value($file_id);

        //Actualizar
        $this->db->where('id', $file_id)->update('files', $arr_row);

        return $this->db->affected_rows();
    }

    /**
     * Valor calculado para actualizar el campo files.priority
     * 2020-07-28
     */
    function priority_value($file_id)
    {
        $priority = rand(100000,999999);
        return $priority;
    }
}