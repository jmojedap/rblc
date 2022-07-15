<style>
    #app_q {
        background-image: url("<?= URL_IMG . 'front/icon_search_blue.png' ?>");
    }

    .navbar_icon:hover {
        background-color: #EEE;
    }
</style>

<?php
    $data_navbar_content['style_navbar'] = 'light';
?>

<div id="navbar_app">
    <nav class="navbar main-navbar navbar-expand-lg navbar-light fixed-top" style="background-color: #FFF;">
        <div class="container py-2">
            <a class="navbar-brand" href="<?= URL_APP ?>home/">
                <img class="d-block" src="<?= URL_BRAND ?>logo-front-blue.png" alt="Main logo" srcset="" title="Go to home" style="height: 60px;">
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <?php $this->load->view('templates/colibri_blue/navbar/content_v', $data_navbar_content) ?>
        </div>
    </nav>  
</div>

<?php $this->load->view('templates/colibri_blue/navbar/vue_v') ?>