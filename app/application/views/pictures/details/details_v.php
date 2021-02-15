<div id="details_app" class="my-3">
    <div class="row">
        <div class="col-md-9">
            <img
                class="w100pc"
                v-bind:src="row.url"
                alt="Colibri Picture"
                onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.jpg'"
            >
            
        </div>
        <div class="col-md-3">
            <div class="media mt-2">
                <a v-bind:href="`<?= base_url("professionals/profile/") ?>` + user.id" class="small-title">
                    <img
                        v-bind:src="user.url_thumbnail"
                        class="rounded-circle mr-1 w40p"
                        alt=""
                        onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                    >
                </a>
                <div class="media-body">
                    <a v-bind:href="`<?= base_url("professionals/profile/") ?>` + user.id" class="small-title">
                        {{ user.display_name }}
                    </a>
                </div>
            </div>
            <p class="mt-2">
                {{ row.description }}
            </p>
            <p>
                <a v-bind:href="`<?= base_url("pictures/explore/1/?q=") ?>` + tag.name" v-for="tag in tags">
                    #{{ tag.name }}
                </a>
            </p>
                <hr>
                <p>
                    <?php if ( $this->session->userdata('role') <= 1 && $this->session->userdata('logged') ) : ?>
                        <a v-bind:href="`<?= base_url("pictures/edit/") ?>` + row.id" class="btn btn-sm btn-light">
                            Editar
                        </a>
                    <?php endif; ?>
                </p>
        </div>
    </div>

    <div class="p-2">
        <?php if ( $this->session->userdata('logged') ) { ?>
            <img class="action_icon" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon like" v-on:click="alt_like" v-show="like_status == 0">
            <img class="action_icon" src="<?= URL_IMG ?>front/icon_liked.png" alt="Icon liked" v-on:click="alt_like" v-show="like_status > 0">
        <?php } else { ?>
            <img class="" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon liked">
        <?php } ?>

        <span class="counter">{{ row.qty_likes }}</span>
    </div>
</div>

<script>
    new Vue({
        el: '#details_app',
        created: function(){
            //this.get_list();
        },
        data: {
            row: <?= json_encode($row) ?>,
            user: {
                id: <?= $user->id ?>,
                display_name: '<?= $user->display_name ?>',
                url_thumbnail: '<?= $user->url_thumbnail ?>'
            },
            tags: <?= json_encode($tags->result) ?>,
            like_status: <?= $like_status ?>
        },
        methods: {
            alt_like: function(){
                axios.get(url_api + 'files/alt_like/' + this.row.id)
                .then(response => {
                    this.like_status = response.data.like_status;
                    if ( this.like_status == 1 ) {
                        this.row.qty_likes++;
                    } else {
                        this.row.qty_likes--;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>

<?php $this->load->view('pictures/comments/comments_v') ?>