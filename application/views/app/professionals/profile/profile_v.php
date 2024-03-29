<div id="user_profile" class="user_profile">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="page-title"><?= $row->display_name ?></h1>
            <p class="text-main lead">
                <i class="fas fa-map-marker-alt"></i>
                <b><?= $row->city ?>, <?= $row->state_province ?></b>
            </p>
            <div class="d-flex mb-2">
                <div>
                    <img src="<?= URL_IMG ?>front/icon_colibri.png" alt="Icon professional followers">
                    <span class="counter"><?= $qty_followers ?></span>
                </div>
                <div class="mx-3">
                    <img src="<?= URL_IMG ?>front/icon_liked.png" alt="Icon professional likes">
                    <span class="counter"><?= $qty_likes ?></span>
                </div>
                <!-- No ser el mismo usuario en sesión -->
                <?php if ( $this->session->userdata('user_id') != $row->id ) { ?>
                    <div class="ml-3 pt-2">
                        <?php if ( $this->session->userdata('logged') ) { ?>
                            <!-- Debe tener sesión activa -->
                            <button class="w120p btn btn-warning btn-rounded" v-on:click="alt_follow">
                                <span v-show="follow_status == 2">Follow</span>
                                <span v-show="follow_status == 1"><i class="fa fa-check"></i> Following</span>
                            </button>
                        <?php } else { ?>
                            <!-- Sin sesión activa -->
                            <a href="<?= URL_FRONT . "accounts/login" ?>" class="w120p btn btn-primary btn-rounded">Follow</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div><?= $row->about ?></div>
        </div>
        <div class="col-md-4">
            <img src="<?= $row->url_thumbnail ?>" alt="User profile image" onerror="this.src='<?= URL_IMG ?>users/user.png'" class="w100pc mb-2">

            <div class="mb-4">
                <div class="d-flex">
                    <i class="fa fa-phone text-muted mr-2 w30p"></i>
                    <span><?= $row->phone_number ?></span>
                </div>
                <div class="d-flex">
                    <i class="fa fa-envelope text-muted mr-2 w30p"></i>
                    <span><?= $row->email ?></span>
                </div>
                <div class="d-flex">
                    <i class="fa fa-map-marker text-muted mr-2 w30p"></i>
                    <span>
                        <?= $row->address ?> <?= $row->address_line_2 ?> <?= $row->city ?>, <?= $row->state_province ?> <?= $row->zip_code ?> <?= $row->country ?>
                    </span>
                </div>
            </div>

            <div class="mb-2 d-flex">
                <div v-for="social_link in social_links">
                    <a v-bind:href="social_link.url" class="text-blue mr-3" target="_blank">
                        <i class="fa-2x" v-bind:class="social_link.icon_class"></i>
                    </a>
                </div>
                <!-- No ser el mismo usuario en sesión -->
                <?php if ( $this->session->userdata('user_id') != $row->id ) { ?>
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <!-- Debe tener sesión activa -->
                        <button class="w120p btn btn-primary btn-rounded" v-on:click="create_conversation">Message</button>
                    <?php } else { ?>
                        <!-- Sin sesión activa -->
                        <a href="<?= URL_FRONT . "accounts/login" ?>" class="w120p btn btn-primary btn-rounded">Message</a>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>
    </div>

    <hr>

    <!-- MENÚ SECCIONES -->
    <ul class="nav_2 nav nav-pills nav-fill mb-4" id="profile_content">
        <li class="nav-item">
            <a class="nav-link" href="#profile_content" v-bind:class="{'active': section == 'images' }" v-on:click="set_section('images')">Images</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#profile_content" v-bind:class="{'active': section == 'projects' }" v-on:click="set_section('projects')">Projects</a>
        </li>
    </ul>

    <!-- IMÁGENES DEL PROFESIONAL -->
    <div v-show="section == 'images'">
        <div class="gallery-profile" >
            <?php if ( $this->session->userdata('user_id') == $row->id ) : ?>
                <div class="image-container">
                    <img class="w100pc pointer" src="<?= URL_IMG ?>front/add.png" alt="Add image" title="Add image" data-toggle="modal" data-target="#modal_upload_file">
                </div>
            <?php endif; ?>
            <div class="image-container" v-for="(image, image_key) in images">
                <img class="picture" v-bind:alt="image.title" v-bind:src="image.url_thumbnail" data-toggle="modal" data-target="#picture_modal" v-on:click="set_key(image_key)">
                <!-- <a v-bind:href="`<?= URL_FRONT . "pictures/details/" ?>` + image.id"></a> -->
            </div>
        </div>
    </div>

    <!-- PROYECTOS DEL PROFESSIONAL -->
    <div v-show="section == 'projects'" class="users">
        <div class="mb-2 text-center" v-if="user_id == APP_UID">
            <a href="<?= URL_FRONT . "projects/my_projects/1/?u={$this->session->userdata('user_id')}" ?>">
                Edit my projects
            </a>
        </div>
        <div class="user" v-for="(project, project_key) in projects">
            <div class="row">
                <div class="col-md-9 pt-2">
                    <a v-bind:href="`<?= URL_FRONT . "projects/info/" ?>` + project.id" class="title_list">
                        <h2>{{ project.name }}</h2>
                        <p>{{ project.description }}</p>
                    </a>
                </div>
                <div class="col-md-3">
                    <a v-bind:href="`<?= URL_FRONT . "projects/info/" ?>` + project.id" class="title_list">
                        <img class="w100pc" v-bind:src="project.url_thumbnail" onerror="this.src='<?= URL_IMG ?>app/nd.png'">
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php //$this->load->view('app/professionals/profile/picture_modal_v') ?>
</div>
<?php $this->load->view('app/professionals/profile/vue_v') ?>

<?php $this->load->view('app/professionals/profile/picture_modal_v') ?>

<?php if ( $row->id == $this->session->userdata('user_id') ) : ?>
    <?php $this->load->view('app/professionals/profile/modal_upload_file_v') ?>
<?php endif; ?>