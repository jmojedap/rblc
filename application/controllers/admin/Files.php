<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Files extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/files/';
    public $url_controller = URL_ADMIN . 'files/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() {
        parent::__construct();
        
        $this->load->model('File_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

//EXPLORE
//---------------------------------------------------------------------------------------------------
                
    /**
     * Vista exploración de archivos
     * 2021-11-12
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->File_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            //$data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
            $data['options_cat_1'] = $this->Item_model->options('category_id = 718', 'Todos');
            
        //Arrays con valores para contenido en lista
            //$data['arrGroup1'] = $this->Item_model->arr_options('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->File_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $session_data = $this->session->userdata();
            $data['qty_deleted'] += $this->File_model->delete($row_id, $session_data);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Elimina un registro de la tabla file, y los archivos asociados en el servidor
     * 2020-07-24
     */
    function delete($file_id)
    {
        $session_data = $this->session->userdata();
        $data['qty_deleted'] = $this->File_model->delete($file_id, $session_data);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Información detallada del file desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($post_id)
    {        
        //Datos básicos
        $data = $this->File_model->basic($post_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function info($file_id)
    {
        $data = $this->File_model->basic($file_id);
        $data['tags'] = $this->File_model->tags($file_id);
        $data['view_a'] = $this->views_folder . 'info_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explore/';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para cargar un archivo al servidor de la aplicación.
     */
    function add()
    {
        $data['head_title'] = 'Archivos';
        $data['view_a'] = $this->views_folder . 'add_v';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function edit($file_id)
    {
        $data = $this->File_model->basic($file_id);

        //Opciones para agregar
        $data['options_tag'] = $this->App_model->options_tag('category_id = 1');
        $data['options_cat_1'] = $this->Item_model->options('category_id = 718', 'Picture category');

        //Datos actuales
        $data['tags'] = $this->File_model->tags($file_id);

        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['view_a'] = $this->views_folder . 'edit_v';
        $data['back_link'] = $this->url_controller . 'explore/';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para recorte de archivo de imagen.
     */
    function cropping($file_id)
    {
        $data = $this->File_model->basic($file_id);

        $data['back_destination'] = "files/edit/{$file_id}";

        $data['view_a'] = $this->views_folder . 'cropping_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function update($file_id)
    {
        $arr_row = $this->input->post();
        $data = $this->File_model->update($file_id, $arr_row);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CAMBIAR ARCHIVO
//-----------------------------------------------------------------------------

    function change($file_id)
    {
        $data = $this->File_model->basic($file_id);
        
        //Variables
            $data['destino_form'] = "files/cambiar_e/{$file_id}";
            $data['att_img'] = $this->File_model->att_img($file_id, '500px_');
        
        //Variables generales
            $data['file_id'] = $file_id;
            $data['nav_2'] = $this->views_folder . 'menu_v';
            $data['view_a'] = $this->views_folder . 'change_v';
            //$data['vista_b'] = 'files/cambiar_v';       
            
        //Variables generales
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Cambia un archivo, conservando su registro y sus asignaciones en la DB.
     * 2019-09-19
     */
    function change_e($file_id)
    {
        $row_ant = $this->Db_model->row_id('files', $file_id);   //Registro antes del cambio

        $data = $this->File_model->upload($file_id);
        
        if ( $data['status'] )
        {
            //Eliminar archivo anterior
                $this->File_model->unlink($row_ant->folder, $row_ant->file_name);
            
            //Actualizar archivo, datos del nuevo archivo
                $data['row'] = $this->File_model->change($file_id, $data['upload_data']);
                $this->File_model->create_thumbnails($file_id);     //Crear miniaturas de la nueve imagen
                $this->File_model->mod_original($data['row']->folder, $data['row']->file_name);          //Mofificar imagen nueva después de crear miniaturas
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Cambio de posición del archivo file.position
//-----------------------------------------------------------------------------

    /**
     * Cambio de posición del archivo en el álbum
     * 2021-02-11
     */
    function update_position($file_id, $new_position)
    {
        $data = $this->File_model->update_position($file_id, $new_position);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// API
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * 
     * Carga un archivo en la ruta "content/uploads/{year}/}{month}/"
     * Crea registro de ese arhivo en la tabla file
     */
    function upload()
    {
        $data = $this->File_model->upload();

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Recorta una imagen según unos parámetros geométricos enviados por POST
     * 2019-05-21
     */
    function crop($file_id)
    {
        //Valor inicial por defecto
        $data = array('status' => 0, 'message' => 'No tiene permiso para modificar esta imagen');
        
        $editable = $this->File_model->editable($file_id);
        if ( $editable ) { $data = $this->File_model->crop($file_id);}
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function square_thumbnails()
    {
        set_time_limit(300);

        $this->db->where('processed', 0);
        $this->db->order_by('id', 'ASC');
        $files = $this->db->get('file', 2000);

        $data['qty_squared'] = 0;
        $data['max_id'] = 0;

        foreach ($files->result() as $row_file)
        {
            $this->File_model->square_image($row_file, 'sm_');
            ++$data['qty_squared'];
            $data['max_id'] = $row_file->id;

            $this->db->query("UPDATE file SET processed = 1 WHERE id = {$row_file->id}");

        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INTERACCIÓN DE USUARIO
//-----------------------------------------------------------------------------

    /**
     * Alternar like and unlike una imagen por parte del usuario en sesión
     * 2020-07-09
     */
    function alt_like($user_id)
    {
        $data = $this->File_model->alt_like($user_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// REVISIÓN DETALLADA DE ARCHIVOS Y ACTUALIZACIÓN - ESPECÍFICA COLIBRI
//-----------------------------------------------------------------------------

    /**
     * Herramienta para revisión y actualización secuencial de archivos uno a uno
     * 2021-08-04
     */
    function check($file_id = NULL)
    {
        if ( is_null($file_id) ) {
            $this->db->order_by('id', 'DESC');
            $this->db->where('checked_at IS NULL');
            $files = $this->db->get('files');

            if ( $files->num_rows() > 0 ) $file_id = $files->row()->id;
        }

        $data = $this->File_model->basic($file_id);

        //Opciones para agregar
        $data['options_tag'] = $this->App_model->options_tag('category_id = 1');
        $data['options_cat_1'] = $this->Item_model->options('category_id = 718');

        //Datos sobre revisión
        $data['qty_files'] = $this->Db_model->num_rows('files', 'id > 0');
        $data['qty_checked'] = $this->Db_model->num_rows('files', 'checked_at IS NOT NULL');

        //Datos actuales
        $data['tags'] = $this->File_model->tags($file_id);

        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = $this->views_folder . 'check/check_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Temporal
     * Redirect, ir a edición y revisión de imagen
     * 2021-08-04
     */
    function check_next()
    {
        $file_id = 0;

        $this->db->order_by('id', 'DESC');
        $this->db->where('checked_at IS NULL');
        $files = $this->db->get('files');

        if ( $files->num_rows() > 0 ) {
            $index = rand(0, $files->num_rows() - 1);
            $file_id = $files->row($index)->id;
        }

        redirect("admin/files/check/{$file_id}");
    }

    /**
     * Temporal Redirect
     * Ir a imagen anterior, revisión
     * 2021-08-04
     */
    function check_previous($current_file_id)
    {
        $file_id = 0;

        $this->db->order_by('checked_at', 'DESC');
        $this->db->where('checked_at IS NOT NULL');
        $this->db->where("id <> {$current_file_id}");
        $files = $this->db->get('files');

        if ( $files->num_rows() > 0 ) $file_id = $files->row()->id;

        redirect("admin/files/check/{$file_id}");
    }

// PROCESOS MASIVOS
//-----------------------------------------------------------------------------

    /*function update_url()
    {

    }*/

    /**
     * Actualiza el campo file.searcher considerando los datos asociados del registro en tabla file
     * 2020-07-28
     */
    function update_searcher($min_id = 0)
    {
        $data = $this->File_model->update_searcher_multi($min_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo file.searcher considerando los datos asociados del 
     * registro en tabla file
     * 2020-07-28
     */
    function update_priority($min_id = 0)
    {
        $data = $this->File_model->update_priority_multi($min_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// METADATOS
//-----------------------------------------------------------------------------

    /**
     * Actualiza datos descriptivos de la tabla file, y metadatos (tags) para files_meta
     * 2022-10-29 (group_1)
     */
    function update_full($file_id)
    {
        //Update row
            $arr_row['title'] = $this->input->post('title');
            $arr_row['file_name'] = $this->input->post('file_name');
            $arr_row['description'] = $this->input->post('description');
            $arr_row['cat_1'] = $this->input->post('cat_1');
            $arr_row['group_1'] = $this->input->post('group_1'); //Agregado 2022-10-29
            $arr_row['keywords'] = $this->input->post('keywords');
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['checked_at'] = date('Y-m-d H:i:s');   //Agregado para revisión 2021-08-04

            $data['saved_id'] = $this->Db_model->save('files', "id = {$file_id}", $arr_row);

        //Guardar Tags
            $tags = ( is_null($this->input->post('tags')) ) ? array() : $this->input->post('tags');
            $data['updated_tags'] = $this->File_model->save_meta_array($file_id, 27, $tags);

        //Actualizar campos dependientes
            $this->File_model->update_searcher($file_id);

        //Result
            $data['status'] = 1;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}