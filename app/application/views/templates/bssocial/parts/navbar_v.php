<?php
    $cf = $this->uri->segment(1) . '/' .  $this->uri->segment(2);   //Controller / Function

    $cl_menu['home'] = 'nav-link';
    $cl_menu['pictures'] = 'nav-link';
    $cl_menu['professionals'] = 'nav-link';
    $cl_menu['projects'] = 'nav-link';

    if ( $cf == 'home/' ) { $cl_menu['home'] .= ' active'; }
    if ( $cf == 'pictures/explore' ) { $cl_menu['pictures'] .= ' active'; }
    if ( $cf == 'professionals/explore' ) { $cl_menu['professionals'] .= ' active'; }
    if ( $cf == 'professionals/profile' ) { $cl_menu['professionals'] .= ' active'; }
    if ( $cf == 'projects/explore' ) { $cl_menu['projects'] .= ' active'; }
    if ( $cf == 'projects/info' ) { $cl_menu['projects'] .= ' active'; }
    if ( $cf == 'projects/edit' ) { $cl_menu['projects'] .= ' active'; }
    if ( $cf == 'projects/images' ) { $cl_menu['projects'] .= ' active'; }
?>

<nav class="navbar main-navbar navbar-expand-lg navbar-dark" style="background-color: #FF6529;">
    <div class="container">
        <a class="navbar-brand" href="<?php echo URL_APP ?>">
            <img class="d-block" src="<?php echo URL_IMG ?>app/logo.png" alt="Main logo" srcset="">
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="<?= $cl_menu['home'] ?>" href="<?= base_url('home') ?>">HOME <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="<?= $cl_menu['pictures'] ?>" href="<?= base_url('pictures/explore/') ?>">INSPIRATION</a>
                </li>
                <li class="nav-item">
                    <a class="<?= $cl_menu['professionals'] ?>" href="<?= base_url('professionals/explore') ?>">FIND A PRO</a>
                </li>
                <li class="nav-item">
                    <a class="<?= $cl_menu['projects'] ?>" href="<?= base_url('projects/explore') ?>">PROJECTS</a>
                </li>
            </ul>

            <?php $this->load->view('templates/bssocial/parts/search_form_v') ?>

            <?php if ( $this->session->userdata('logged') ) { ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="<?= base_url("accounts/profile") ?>">
                        <img src="<?= $this->session->userdata('src_img') ?>"    alt="user image" class="rounded-circle" style="max-width: 40px;">
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        More
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?= base_url('accounts/edit') ?>">Edit profile</a>

                        <?php if ( $this->session->userdata('role') == 13 ) { ?>
                            <a class="dropdown-item" href="<?php echo base_url('projects/my_projects') ?>">My projects</a>
                        <?php } ?>

                        <?php if ( $this->session->userdata('role') == 23 ) { ?>
                            <a class="dropdown-item" href="<?php echo base_url('projects/favorites') ?>">Favorite projects</a>
                            <a class="dropdown-item" href="<?php echo base_url('accounts/following') ?>">Following</a>
                        <?php } ?>

                        <a class="dropdown-item" href="<?php echo base_url('ideabooks/my_ideabooks') ?>">Ideabooks</a>
                        <a class="dropdown-item" href="<?php echo base_url('messages/conversation') ?>">
                            Messages
                            <?php if ( $this->session->userdata('qty_unread') > 0 ) { ?>
                                <span class="ml-2 badge badge-warning"><?= $this->session->userdata('qty_unread') ?></span>
                            <?php } ?>
                        </a>
                        <a class="dropdown-item" href="#<?php //echo base_url('info/help') ?>">Help</a>
                        <a class="dropdown-item" href="#<?php //echo base_url('info/politics') ?>">Politics</a>

                        <div class="dropdown-divider"></div>
                        
                        <?php if ( $this->session->userdata('role') <= 2 ) { ?>
                        <a class="dropdown-item" href="<?= URL_API . 'users/explore' ?>">Administration</a>
                        <?php } ?>

                        <a class="dropdown-item" href="<?php echo base_url('accounts/logout') ?>">Log out</a>
                    </div>
                </li>
            </ul>
            <?php } else { ?>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url('accounts/login') ?>">
                        <i class="far fa-user-circle"></i>
                        Log in
                    </a>
                </li>
            </ul>
            <?php } ?>

        </div>
    </div>
</nav>