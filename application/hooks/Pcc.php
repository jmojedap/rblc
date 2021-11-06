<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    /**
     * 2021-10-30
     */
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();
        
        //Identificar seccion/controlador/función (scf), y allow
            $scf =  $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2) . '/' . $this->CI->uri->segment(3);
            $allow_scf = $this->allow_scf($scf);    //Permisos de acceso al recurso controlador/función
            
            //Verificar allow
            if ( $allow_scf )
            {
                //$this->no_leidos();     //Actualizar variable de sesión, cant mensajes no leídos
            } else {
                //No tiene autorización
                if ( $this->CI->uri->segment(1) == 'api' ) {
                    redirect("api/app/denied/{$scf}");
                } else {
                    redirect("app/app/denied/{$scf}");
                }
            }
    }
    
    /**
     * Control de acceso de usuarios basado en el archivo config/acl.php
     * CF > Ruta Controller/Function
     * 2021-10-16
     */
    function allow_scf($scf)
    {
        //Cargando lista de control de acceso, application/config/acl.php
        $this->CI->config->load('acl', TRUE);
        $acl = $this->CI->config->item('acl');

        //Variables
        $allow_scf = FALSE;
        
        //Verificar en funciones públicas
        if ( in_array($scf, $acl['public_functions']) ) $allow_scf = TRUE;
        
        //Si inició sesión
        if ( $this->CI->session->userdata('logged') == TRUE )
        {
            //Identificar role
            $role = $this->CI->session->userdata('role');

            //Es administrador y editor, todos los permisos
            if ( in_array($role, array(1,2,3)) ) $allow_scf = TRUE;

            //Funciones para todos los usuarios con sesión iniciada
            if ( in_array($scf, $acl['logged_functions']) ) $allow_scf = TRUE;

            //Funciones para el rol actual
            if ( array_key_exists($scf, $acl['function_roles']) )
            {
                $roles = $acl['function_roles'][$scf];
                if ( in_array($role, $roles) ) $allow_scf = TRUE;
            }
        }


        //Funciones de API
        /*if ( $this->CI->uri->segment(1) == 'api' ) {
            $allow_scf = FALSE;
            //Está en las funciones públicas
            if ( in_array($scf, $acl['api_public_functions']) ) $allow_scf = TRUE;

            //Autorizado por userkey
            $user_request = $this->user_request();
            if ( ! is_null($user_request) ) $allow_scf = TRUE;
        }*/

        return $allow_scf;
    }
    
    /**
     * Antes de cada acceso, actualiza la variable de sesión de cantidad de mensajes sin leer
     */
    function qty_unread()
    {
        $this->CI = &get_instance();
        
        //Consulta
            $this->CI->db->where('status', 0);  //No leído
            $this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));  //No leído
            $messages = $this->CI->db->get('message_user');
            
        //Establecer valor
            $qty_unread = 0;
            if ( $messages->num_rows() > 0 ) { $qty_unread = $messages->num_rows(); }
        
        //Actualizar variable de sesión
            $this->CI->session->set_userdata('qty_unread', $qty_unread);
    }

    /**
     * Row usuario que hace el request por la API
     * 2021-10-16
     */
    function user_request()
    {
        $this->CI = &get_instance();

        $user = null;   //Valor por defecto
        
        $arr_ik = explode('-', $this->CI->input->get('ik'));
        if ( count($arr_ik) == 2 ) {
            $user_id = $arr_ik[0];
            $userkey = $arr_ik[1];

            $condition = "id = '{$user_id}' AND userkey = '{$userkey}'";
            $this->CI->db->where($condition);
            $users = $this->CI->db->get('users', 1);
            
            if ( $users->num_rows() > 0 ) $user = $users->row();            
        }

        return $user;
    }
    
}