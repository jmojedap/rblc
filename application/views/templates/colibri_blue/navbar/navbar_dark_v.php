<style>
    #app_q {
        background-image: url("<?= URL_IMG . 'front/icon_search_white.png' ?>");
    }

    .navbar_icon:hover {
        background-color: #1e3764;
    }
</style>

<?php
    $data_navbar_content['style_navbar'] = 'dark';
?>

<div id="navbar_app">
    <nav class="navbar main-navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #2e4b80;">
        <div class="container py-2">
            <a class="navbar-brand" href="<?= URL_APP ?>home/">
                <img class="d-block" src="<?= URL_BRAND ?>logo-front.png" alt="Main logo" srcset="" title="Go to home">
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