<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="en">
    <head>
        <?php $this->load->view('templates/bssocial/parts/head_v'); ?>
        <?php $this->load->view('templates/bssocial/parts/routes_script_v'); ?>
    </head>
    <body>
        <?php $this->load->view('templates/bssocial/parts/navbar_v'); ?>
        <div class="container" style="margin-top: 50px; min-height: 500px;">
            <div id="view_a">
                <?php $this->load->view($view_a); ?>
            </div>
        </div>
        <?php $this->load->view('templates/bssocial/parts/footer_v') ?>
    </body>
</html>