<div id="user_profile">
    <div class="row mb-3">
        <div class="col-md-8">
            <h1><?= $row->display_name ?></h1>
            <p>
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
            <img class="picture" v-bind:alt="image.title" v-bind:src="image.url_thumbnail">
        </div>
    </div>
</div>

<script>
// Vue App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#user_profile',
        created: function(){
            this.get_images();
            this.get_social_links();
        },
        data: {
            user_id: '<?= $row->id ?>',
            follow_status: '<?= $follow_status ?>',
            images: [],
            social_links: {}
        },
        methods: {
            get_images: function(){
                axios.get(url_api + 'professionals/get_images/' + this.user_id)
                .then(response => {
                    this.images = response.data.images;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            get_social_links: function(){
                axios.get(url_api + 'users/get_social_links/' + this.user_id)
                .then(response => {
                    this.social_links = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            alt_follow: function(){
                axios.get(url_api + 'users/alt_follow/' + this.user_id)
                .then(response => {
                    this.follow_status = response.data.status;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            create_conversation: function(){
                axios.get(url_api + 'messages/create_conversation/' + this.user_id)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.conversation_id > 0 ) {
                        window.location = url_app + 'messages/conversation/' + response.data.conversation_id;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>