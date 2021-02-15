<div id="user_profile" class="user_profile">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1 class="mt-3"><?= $row->display_name ?></h1>
            <p class="text-muted">
                <i class="fas fa-map-marker-alt"></i>
                <?= $row->city ?>, <?= $row->state_province ?>
            </p>
            <div class="d-flex mb-2">
                <div>
                    <img src="<?= URL_IMG ?>front/icon_colibri.png" alt="Icon professional followers">
                    <span class="counter"><?= $qty_followers ?></span>
                </div>
                <div class="ml-3">
                    <img src="<?= URL_IMG ?>front/icon_like.png" alt="Icon professional likes">
                    <span class="counter"><?= $qty_likes ?></span>
                </div>
            </div>
            <div><?= $row->about ?></div>
        </div>
        <div class="col-md-4">
            <img src="<?= $row->url_thumbnail ?>" alt="User profile image" onerror="this.src='<?php echo URL_IMG ?>users/user.png'" class="w100pc mb-2">

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

            <div class="mb-2">
                <a v-for="social_link in social_links" v-bind:href="social_link.url" class="text-muted mr-3" target="_blank">
                    <i class="fa-2x" v-bind:class="social_link.icon_class"></i>
                </a>
            </div>

            <!-- No ser el mismo usuario en sesión -->
            <?php if ( $this->session->userdata('user_id') != $row->id ) { ?>
                <?php if ( $this->session->userdata('logged') ) { ?>
                    <!-- Debe tener sesión activa -->
                    <button class="btn btn-block btn-white" v-on:click="alt_follow">
                        <span v-show="follow_status == 2">Follow</span>
                        <span v-show="follow_status == 1"><i class="fa fa-check"></i> Following</span>
                    </button>
                    <button class="btn btn-white btn-block" v-on:click="create_conversation">Message</button>
                <?php } else { ?>
                    <!-- Sin sesión activa -->
                    <a href="<?= base_url("accounts/login") ?>" class="btn btn-white btn-block">Follow</a>
                    <a href="<?= base_url("accounts/login") ?>" class="btn btn-white btn-block">Message</a>
                <?php } ?>
            <?php } ?>


        </div>
    </div>
    <div class="gallery-3">
        <div class="image-container" v-for="(image, image_key) in images">
            <a v-bind:href="`<?php echo base_url("pictures/details/") ?>` + image.id">
                <img class="picture" v-bind:alt="image.title" v-bind:src="image.url_thumbnail" data-toggle="modal_no" data-target="#picture_modal" v-on:click="set_key(image_key)">
            </a>
        </div>
    </div>
    <?php $this->load->view('professionals/profile/picture_modal_v') ?>
</div>
<?php $this->load->view('professionals/profile/vue_v') ?>