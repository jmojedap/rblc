<?php
    $cf = $this->uri->segment(2) . '/' .  $this->uri->segment(3);   //Controller / Function

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

    //Íconos según tema
    $url_icons['user'] = URL_IMG . 'app/user-blue.svg';
    $url_icons['bell'] = URL_IMG . 'app/bell-blue.svg';

    if ( $style_navbar == 'dark' ) {
        $url_icons['user'] = URL_IMG . 'app/user-white.png';
        $url_icons['bell'] = URL_IMG . 'app/bell-white.png';
    }
?>

<div class="collapse navbar-collapse" id="navbarSupportedContent">

    <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
            <a class="<?= $cl_menu['pictures'] ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Inspiration
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/' ?>'>All</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=1' ?>'>Kitchen & Dining</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=2' ?>'>Bathrooms</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=3' ?>'>Closets</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=4' ?>'>Bedrooms</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=5' ?>'>Home Office</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=6' ?>'>Living</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=7' ?>'>Bar & Wine</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=8' ?>'>Outdoor</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=9' ?>'>Walkways</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'pictures/explore/?cat_1=10' ?>'>Other Rooms</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="<?= $cl_menu['professionals'] ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Find a Pro
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/' ?>'>All</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/?cat_1=1' ?>'>Design</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/?cat_1=2' ?>'>Remodeling & Renovation</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/?cat_1=3' ?>'>Outdoor</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/?cat_1=4' ?>'>Apliances & Systems</a>
                <a class='dropdown-item' href='<?= URL_FRONT . 'professionals/explore/?cat_1=5' ?>'>Services</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="<?= $cl_menu['projects'] ?>" href="<?= URL_FRONT . 'projects/explore' ?>">Projects</a>
        </li>
    </ul>

    <?php $this->load->view('templates/colibri_blue/parts/search_form_v') ?>

    <?php if ( $this->session->userdata('logged') ) { ?>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown ml-1">
            <a class="nav-link" href="#" id="notification-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-on:click="get_notifications">
                <img class="navbar_icon" src="<?= $url_icons['bell'] ?>" alt="Notifications">
                <span class="qty_notifications" v-show="qty_unread_notifications > 0">{{ qty_unread_notifications }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item disable" v-show="notifications.length == 0">No notifications</a>
                <!-- LISTADO DE NOTIFICACIONES -->
                <a class="dropdown-item" href="#"
                    v-for="(notification, notification_key) in notifications"
                    v-bind:class="{'unread_notification': notification.status == 2 }"
                    >
                    <div class="d-flex" v-on:click="open_notification(notification.id)">
                        <div class="mr-3 pt-2">
                            <i v-bind:class="alert_icon_class(notification)"></i>
                        </div>
                        <div>
                            {{ notification.content }}
                            <br>
                            <small class="text-primary">{{ notification.created_at | ago }}</small>
                        </div>
                    </div>
                </a>
            </div>
        </li>
        <li class="nav-item dropdown ml-1">
            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?= $this->session->userdata('picture') ?>" alt="user image" class="navbar_icon" onerror="this.src='<?= $url_icons['user'] ?>'">
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <?php if ( $this->session->userdata('role') == 13 ) : ?>
                    <a class="dropdown-item" href="<?= URL_FRONT . "professionals/profile/{$this->session->userdata('user_id')}" ?>">My profile</a>
                <?php endif; ?>
                <a class="dropdown-item" href="<?= URL_FRONT . 'accounts/edit' ?>">Edit profile</a>

                <?php if ( $this->session->userdata('role') == 13 ) { ?>
                    <a class="dropdown-item" href="<?= URL_FRONT . 'projects/my_projects' ?>">My projects</a>
                <?php } ?>

                <?php if ( $this->session->userdata('role') == 23 ) { ?>
                    <a class="dropdown-item" href="<?= URL_FRONT . 'projects/favorites' ?>">Favorite projects</a>
                    <a class="dropdown-item" href="<?= URL_FRONT . 'accounts/following' ?>">Following</a>
                <?php } ?>

                <a class="dropdown-item" href="<?= URL_FRONT . 'ideabooks/my_ideabooks' ?>">Ideabooks</a>
                <a class="dropdown-item" href="<?= URL_FRONT . 'messages/conversation' ?>">
                    Messages
                    <?php if ( $this->session->userdata('qty_unread') > 0 ) { ?>
                        <span class="ml-2 badge badge-warning"><?= $this->session->userdata('qty_unread') ?></span>
                    <?php } ?>
                </a>
                <a class="dropdown-item" href="#<?php //echo URL_FRONT . 'info/help' ?>">Help</a>
                <a class="dropdown-item" href="#<?php //echo URL_FRONT . 'info/politics' ?>">Politics</a>

                <div class="dropdown-divider"></div>
                
                <?php if ( $this->session->userdata('role') <= 3 ) { ?>
                    <a class="dropdown-item" href="<?= URL_API . 'users/explore' ?>" target="_blank">Administration</a>
                <?php } ?>

                <a class="dropdown-item" href="<?= URL_FRONT . 'accounts/logout' ?>">Log out</a>
            </div>
        </li>
    </ul>
    <?php } else { ?>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="<?= URL_FRONT . 'accounts/login' ?>">
                <span class="mr-2">Log in</span>
                <img class="only-lg navbar_icon" src="<?= $url_icons['user'] ?>" alt="Usuario">
            </a>
        </li>
    </ul>
    <?php } ?>

</div>