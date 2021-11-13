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
?>

<div id="navbar_app">
    <nav class="navbar main-navbar navbar-expand-lg navbar-dark" style="background-color: #FF6529;">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_APP ?>">
                <img class="d-block" src="<?= URL_IMG ?>app/logo.png" alt="Main logo" srcset="">
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="<?= $cl_menu['home'] ?>" href="<?= URL_FRONT . 'home' ?>">HOME <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="<?= $cl_menu['pictures'] ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            INSPIRATION
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
                            FIND A PRO
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
                        <a class="<?= $cl_menu['projects'] ?>" href="<?= URL_FRONT . 'projects/explore' ?>">PROJECTS</a>
                    </li>
                </ul>

                <?php $this->load->view('templates/colibri/parts/search_form_v') ?>

                <?php if ( $this->session->userdata('logged') ) { ?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown ml-1">
                        <a class="nav-link" href="#" id="notification-dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-on:click="get_notifications">
                            <img src="<?= URL_IMG ?>front/bell.png" alt="Notifications" class="rounded rounded-circle">
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
                            <img src="<?= $this->session->userdata('picture') ?>" alt="user image" class="rounded-circle" style="max-width: 40px;" onerror="this.src='<?= URL_IMG ?>users/sm_user.png'">
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
                            <i class="far fa-user-circle"></i>
                            Log in
                        </a>
                    </li>
                </ul>
                <?php } ?>

            </div>
        </div>
    </nav>  
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

// VueApp
//-----------------------------------------------------------------------------
var navbar_app = new Vue({
    el: '#navbar_app',
    created: function(){
        this.get_qty_unread_notifications()
    },
    data: {
        qty_unread_notifications: 0,
        notifications: [],
        loading: false,
    },
    methods: {
        get_qty_unread_notifications: function(){
            axios.get(url_api + 'app/qty_unread_notifications/')
            .then(response => {
                this.qty_unread_notifications = response.data.qty_unread_notifications
            })
            .catch(function(error) { console.log(error) })
        },
        get_notifications: function(){
            axios.get(url_api + 'app/get_notifications/')
            .then(response => {
                this.notifications = response.data.notifications
            })
            .catch(function(error) { console.log(error) })
        },
        open_notification: function(notification_id){
            axios.get(url_api + 'app/open_notification/' + notification_id)
            .then(response => {
                console.log(response.data)
                if ( response.data.url_destination ) {
                    window.location = response.data.url_destination
                }
            })
            .catch(function(error) { console.log(error) })
        },
        //String, link al que debe dirigirse al hacer clic en la alerta de notificación
        /*alert_link: function(notification){
            var alert_link = '#'
            if ( notification.alert_type == 10 ) {
                alert_link = url_app + 'professionals/profile/' + notification.element_id;
            } else if ( notification.alert_type == 20 ) {
                alert_link = url_app + 'messages/conversation/';
            } else if ( notification.alert_type == 30 ) {
                //events.related_2 => ID elemento comentado
                alert_link = url_app + 'pictures/details/' + notification.related_2;
                //Verificar tabla_id (events.related_1), tabla posts (2000)
                if ( notification.related_1 == 2000 ) {
                    alert_link = url_app + 'projects/info/' + notification.related_2;
                }
            }
            return alert_link;
        },*/
        //String, clase de FowtAwesome para icono de notificación
        alert_icon_class: function(notification){
            var alert_icon_class = 'far fa-user'
            if ( notification.alert_type == 10 ) {
                alert_icon_class = 'far fa-user'
            } else if ( notification.alert_type == 20) {
                alert_icon_class = 'far fa-envelope'
            } else if ( notification.alert_type == 30) {
                alert_icon_class = 'far fa-comment'
            }
            return alert_icon_class;
        },
    }
})
</script>