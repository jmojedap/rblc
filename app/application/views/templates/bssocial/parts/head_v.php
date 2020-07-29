<title><?php echo $head_title ?></title>
        
        <link rel="shortcut icon" href="<?php echo URL_IMG ?>app/favicon.png"> 
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <script src="<?php echo base_url('resources/js/pcrn.js') ?>"></script>

        <?php $this->load->view('assets/bootstrap'); ?>
        
        <link rel="stylesheet" href="<?php echo base_url('resources/templates/colibri_pre/css/main_20200728.css') ?>">
        <link rel="stylesheet" href="<?php echo base_url('resources/css/style_pml.css') ?>">

        <!-- Google Analytics -->
        <?php //$this->load->view('assets/google_analytics') ?>

        <!-- Moment.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script> -->
        
        <!--Icons font-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
        
        <!-- Vue.js -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>

        <!-- Alertas y notificaciones -->
        <?php $this->load->view('assets/toastr') ?>