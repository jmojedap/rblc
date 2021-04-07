<?php
class App_model extends CI_Model{
    
    /* Application model,
     * Functions to Legalink Admin Application
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//SYSTEM
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Carga la view solicitada, si por get se solicita una view específica
     * se devuelve por secciones el html de la view, por JSON.
     * 
     * @param type $view
     * @param type $data
     */
    function view($view, $data)
    {
        if ( $this->input->get('json') )
        {
            //Sende sections JSON
            $result['head_title'] = $data['head_title'];
            $result['head_subtitle'] = '';
            $result['nav_2'] = '';
            $result['nav_3'] = '';
            $result['view_a'] = '';
            
            if ( isset($data['head_subtitle']) ) { $result['head_subtitle'] = $data['head_subtitle']; }
            if ( isset($data['view_a']) ) { $result['view_a'] = $this->load->view($data['view_a'], $data, TRUE); }
            if ( isset($data['nav_2']) ) { $result['nav_2'] = $this->load->view($data['nav_2'], $data, TRUE); }
            if ( isset($data['nav_3']) ) { $result['nav_3'] = $this->load->view($data['nav_3'], $data, TRUE); }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
            //echo trim(json_encode($result));
        } else {
            //Cargar view completa de forma normal
            $this->load->view($view, $data);
        }
    }
    
    /**
     * Devuelve el valor del campo sis_option.valor
     * @param type $option_id
     * @return type
     */
    function option_value($option_id)
    {
        $option_value = $this->Db_model->field_id('sis_option', $option_id, 'value');
        return $option_value;
    }

    /**
     * Array con datos de sesión adicionales específicos para la aplicación actual.
     * 2020-01-22
     */
    function app_session_data($row_user)
    {
        $data = array();

        return $data;
    }

    /**
     * Validación de Google Recaptcha V3, la validación se realiza considerando el valor de
     * $recaptcha->score, que va de 0 a 1.
     * 2019-10-31
     */
    function recaptcha()
    {
        $secret = K_RCSC;   //Ver config/constants.php
        $response = $this->input->post('g-recaptcha-response');
        $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $recaptcha = json_decode($json_recaptcha);
        
        return $recaptcha;
    }

// NOMBRES
//-----------------------------------------------------------------------------

    /**
     * Devuelve el nombre de un user ($user_id) en un format específico ($format)
     */
    function name_user($user_id, $format = 'd')
    {
        $name_user = 'ND';
        $row = $this->Db_model->row_id('users', $user_id);

        if ( ! is_null($row) ) 
        {
            $name_user = $row->username;

            if ($format == 'u') {
                $name_user = $row->username;
            } elseif ($format == 'fl') {
                $name_user = "{$row->first_name} {$row->last_name}";
            } elseif ($format == 'lf') {
                $name_user = "{$row->last_name} {$row->first_name}";
            } elseif ($format == 'flu') {
                $name_user = "{$row->first_name} {$row->last_name} | {$row->username}";
            } elseif ($format == 'du') {
                $name_user = $row->display_name . ' (' . $row->username . ')';
            } elseif ($format == 'd') {
                $name_user = $row->display_name;
            }
        }

        return $name_user;
    }

    /**
     * Devuelve el nombre de una registro ($place_id) en un format específico ($format)
     */
    function place_name($place_id, $format = 1)
    {
        $place_name = 'ND';
        
        if ( strlen($place_id) > 0 )
        {
            $this->db->select("places.id, places.place_name, region, country"); 
            $this->db->where('places.id', $place_id);
            $row = $this->db->get('places')->row();

            if ( $format == 1 ){
                $place_name = $row->place_name;
            } elseif ( $format == 'CR' ) {
                $place_name = $row->place_name . ', ' . $row->region;
            } elseif ( $format == 'CRP' ) {
                $place_name = $row->place_name . ' - ' . $row->region . ' - ' . $row->country;
            }
            
        }
        
        return $place_name;
    }
    
    /**
     * Nombre de una tabla a partir del ID
     * 2020-06-08
     */
    function table_name($table_id)
    {
        $table_name = $this->Db_model->field_id('sis_table', $table_id, 'table_name');

        return $table_name;
    }

// OPCIONES
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con las opciones de la tabla place, limitadas por una condición definida en 
     * un formato ($value_field) definido
     * 2019-08-20
     */
    function options_place($condition, $value_field = 'cr', $empty_text = 'Lugar')
    {
        $this->db->select("CONCAT('0', places.id) AS place_id, place_name, CONCAT((place_name), ', ', (region)) AS cr", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('places.place_name', 'ASC');
        $query = $this->db->get('places');

        $options_place = array_merge(array('00' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'place_id'));
        //$options_place = $this->pml->query_to_array($query, $value_field, 'place_id');
        
        return $options_place;
    }

    /**
     * Devuelve un array con las opciones de la tabla place, limitadas por una condición definida en 
     * un formato ($value_field) definido
     * 2019-08-20
     */
    function options_country($condition = 'id > 0')
    {
        $this->db->select("cod, full_name", FALSE); 
        $this->db->where('type_id = 2');
        $this->db->where($condition);
        $this->db->order_by('places.full_name', 'ASC');
        $query = $this->db->get('places');

        $options_place = array_merge(array('00' => '[ Select Country ]'), $this->pml->query_to_array($query, 'full_name', 'cod'));
        
        return $options_place;
    }

    /* Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un format ($format) definido
    */
    function options_user($condition, $empty_text = 'Usuario', $value_field = 'display_name')
    {
        
        $this->db->select("CONCAT('0', users.id) AS user_id, display_name, username, CONCAT(display_name, (' | '), username) as du", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('user.display_name', 'ASC');
        $query = $this->db->get('users');
        
        $options_user = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'user_id'));
        
        return $options_user;
    }

    /**  Devuelve un array con las opciones de la tabla tag, limitadas por una condición definida
    * en un format ($format) definido
    * 2020-08-03
    */
    function options_tag($condition, $empty_text = 'tag', $value_field = 'name')
    {
        
        $this->db->select("CONCAT('0', tags.id) AS tag_id, name", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('tags.name', 'ASC');
        $query = $this->db->get('tags');
        
        $options_tag = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'tag_id'));
        
        return $options_tag;
    }

// IMÁGENES
//-----------------------------------------------------------------------------

    /**
     * String src atributo html para imagen, imagen de usuario
     * 2019-11-07
     */
    function src_img_user($row_user, $prefix = '')
    {
        $src = URL_IMG . 'users/'. $prefix . 'user.png';
            
        if ( $row_user->image_id > 0 )
        {
            $src = URL_UPLOADS . $row_user->url_image;
            if ( $prefix == 'sm_' )
            {
                $src = URL_UPLOADS . $row_user->url_thumbnail;
            }
        }
        
        return $src;
    }

    function att_img_user($row_user, $prefix = '')
    {
        $att_img = array(
            'src' => $this->src_img_user($row_user, $prefix),
            'alt' => 'User prorile image ' . $row_user->username,
            'width' => '100%',
            'onerror' => "this.src='" . URL_IMG . 'users/user.png' . "'"
        );
        
        return $att_img;
    }
}