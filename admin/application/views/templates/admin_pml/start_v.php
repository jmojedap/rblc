<?php
    //Evitar errores de definición de variables e índices de arrays, 2013-12-07
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ERROR);
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/admin_pml/parts/head') ?>
        <link rel="stylesheet" href="<?php echo URL_RESOURCES ?>css/start.css">
    </head>

    <body>
        <div style="height: 6%" class="d-none d-lg-block"></div>
        <div class="start_container text-center">
            <img src="<?php echo URL_IMG . 'app/start_logo.png' ?>" alt="Logo App" class="mb-2">
            <?php $this->load->view($view_a); ?>
        </div>
        <div class="fixed-bottom text-center pb-2">
            <span style="color: #FFFFFF">
                © 2020 &middot; Pacarina Media Lab &middot; Colombia
            </span>
        </div>
        
        <?php //$this->load->view('templates/remark/parts/footer_scripts_v') ?>
    </body>
</html>
