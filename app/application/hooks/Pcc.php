<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();
        
        //Identificar controlador/función, y allow
            $cf = $this->CI->uri->segment(1) . '/' . $this->CI->uri->segment(2);
            $allow_cf = $this->allow_cf($cf);    //Permisos de acceso al recurso controlador/función
        
        //Verificar allow
            if ( $allow_cf )
            {
                //$this->no_leidos();     //Actualizar variable de sesión, cant mensajes no leídos
            } else {
                //No tiene allow
                //header('HTTP/1.0 403 Forbidden');
                redirect("app/denied/{$cf}");
                //exit;
            }
    }
    
    /**
     * Control de acceso de usuarios basado en el archivo config/acl.php
     * CF > Ruta Controller/Function
     * 2020-12-26
     */
    function allow_cf($cf)
    {
        //Cargando lista de control de acceso, application/config/acl.php
        $this->CI->config->load('acl', TRUE);
        $acl = $this->CI->config->item('acl');

        //Variables
        $role = $this->CI->session->userdata('role');
        $allow_cf = FALSE;
        
        //Verificar en funciones públicas
        if ( in_array($cf, $acl['public_functions']) ) $allow_cf = TRUE;
        
        //Si inició sesión
        if ( $this->CI->session->userdata('logged') == TRUE )
        {
            //Es administrador, todos los permisos
            if ( $role <= 1 ) $allow_cf = TRUE;
            //Funciones para todos los usuarios con sesión iniciada
            if ( in_array($cf, $acl['logged_functions']) ) $allow_cf = TRUE;
        }

        //Funciones para el rol actual
        if ( array_key_exists($cf, $acl['function_roles']) )
        {
            $roles = $acl['function_roles'][$cf];
            if ( in_array($role, $roles) ) $allow_cf = TRUE;
        }

        return $allow_cf;
    }
    
    /**
     * Antes de cada acceso, actualiza la variable de sesión de cantidad de mensajes 
     * sin leer
     */
    function no_leidos()
    {
        $this->CI = &get_instance();
        
        //Consulta
            $this->CI->db->where('estado', 0);  //No leído
            $this->CI->db->where('usuario_id', $this->CI->session->userdata('usuario_id'));  //No leído
            $mensajes = $this->CI->db->get('mensaje_usuario');
            
        //Establecer valor
            $no_leidos = 0;
            if ( $mensajes->num_rows() > 0 ) { $no_leidos = $mensajes->num_rows(); }
        
        //Actualizar variable de sesión
            $this->CI->session->set_userdata('no_leidos', $no_leidos);
    }
    
}