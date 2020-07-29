<div id="ideabook_app">
    <div class="ideabook ideabook-09">
        <div class="d-flex">
            <div class="mr-3">
                <img src="<?= URL_IMG ?>front/md_icon_ideabook_white.png" alt="Icon ideabook">
            </div>
            <div class="titles">
                <h1 class=""><?= $row->post_name ?><small> by <?= $this->App_model->name_user($row->creator_id); ?></small></h1>
                <h2 class="subtitle"><?= $row->excerpt ?></h2>
            </div>
            <div class="ml-auto p-2 bd-highlight">
                <img src="<?= URL_IMG ?>front/icon_liked_white.png" alt="icon liked" style="">
                <span class="counter-white mr-2">536</span>
                <?php if ( $this->session->userdata('user_id') == $row->creator_id   ) { ?>
                    <a href="<?= base_url("ideabooks/edit/{$row->id}") ?>" class="a-white mr-2">
                        <i class="fa fa-edit fa-2x"></i>
                    </a>
                <?php } ?>
                <a href="#" class="a-white">
                    <i class="fas fa-share-alt fa-2x"></i>
                </a>
            </div>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-md-4" v-for="(image, image_key) in images">
                    <div class="image-container">
                        <img
                            v-bind:src="image.url_thumbnail"
                            class="picture"
                            v-bind:alt="alt"
                            onerror="this.src='<?php echo URL_IMG ?>front/md_coming_soon.png'"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#ideabook_app',
        created: function(){
            this.get_images();
        },
        data: {
            ideabook_id: '<?= $row->id ?>',
            images: []
        },
        methods: {
            get_images: function(){
                axios.get(url_api + 'ideabooks/get_images/' + this.ideabook_id)
                .then(response => {
                    this.images = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>