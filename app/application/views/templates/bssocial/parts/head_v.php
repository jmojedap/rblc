<title>Colibri.House <?php echo $head_title ?></title>
        
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/favicon.png"> 
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $this->load->view('assets/bootstrap'); ?>
        
        <link rel="stylesheet" href="<?php echo base_url('resources/templates/colibri_pre/css/main_20200909.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('resources/css/ideabook_colors.css') ?>">

        <!-- Google Analytics -->
        <?php //$this->load->view('assets/google_analytics') ?>

        <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
        
        <!--Icons font-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        
        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

        <!-- Animate CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>

        <!-- PML Tools -->
        <link rel="stylesheet" href="<?php echo base_url('resources/css/style_pml.css') ?>">
        <script src="<?php echo base_url('resources/js/pcrn.js') ?>"></script>
        <script>
                const url_app = '<?php echo URL_APP ?>'; const url_api = '<?php echo URL_API ?>'; const app_url = '<?php echo URL_APP ?>';
        </script>

        <!-- Alertas y notificaciones -->
        <?php $this->load->view('assets/toastr') ?>