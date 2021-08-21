<title>Colibri.House <?= $head_title ?></title>
        
        <link rel="shortcut icon" href="<?= URL_IMG ?>app/favicon.png"> 
        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $this->load->view('assets/bootstrap'); ?>
        

        <!-- Google Analytics -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <?php if ( ENV == 'production' ) : ?>
                <script async src="https://www.googletagmanager.com/gtag/js?id=UA-177846858-1"></script>
                <script>
                        window.dataLayer = window.dataLayer || [];
                        function gtag(){dataLayer.push(arguments);}
                        gtag('js', new Date());

                        gtag('config', 'UA-177846858-1');
                </script>
        <?php endif; ?>


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
        <link rel="stylesheet" href="<?= base_url('resources/css/style_pml.css') ?>">
        <script src="<?= base_url('resources/js/pcrn.js') ?>"></script>
        <script>
                const url_app = '<?= URL_APP ?>'; const url_api = '<?= URL_API ?>'; const app_url = '<?= URL_APP ?>';
        </script>

        <!-- Alertas y notificaciones -->
        <?php $this->load->view('assets/toastr') ?>

        <link rel="stylesheet" href="<?= base_url('resources/templates/colibri/main_15.css') ?>">
        <link rel="stylesheet" href="<?= base_url('resources/templates/colibri/mobile_15.css') ?>">
        <link rel="stylesheet" href="<?= base_url('resources/templates/colibri/ideabook_colors.css') ?>">