<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/admin_pml/main/head') ?>
    </head>

    <body class="start_body">
        <div class="start_container">
            <a href="<?= base_url() ?>"><img src="<?= URL_BRAND ?>logo-start.png" alt="Logo aplicación"></a>
            <?php $this->load->view($view_a); ?>
            <a href="<?= base_url() ?>" class="btn btn-light w120p">
                <i class="fa fa-home"></i> Inicio
            </a>
        </div>
        <footer class="fixed-bottom start_footer">
            © 2021 &middot; Mi Internet Comunicaciones &middot; Colombia
        </footer>    
    </body>
</html>
