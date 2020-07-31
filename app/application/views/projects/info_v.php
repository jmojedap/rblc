

<?php
    $options_ideabook = $this->session->userdata('ideabooks'); 
    //$options_ideabook = $this->pml->query_to_array($ideabooks, 'title', 'id');

    $editable = false;

    if ( $this->session->userdata('user_id') == $row->related_1 ) { $editable = true; }
    if ( $this->session->userdata('role') <= 2 ) { $editable = true; }
?>

<style>
    .main_image{
        width: 100%;
    }
</style>

<div id="proyect_profile">
    <div class="row">
        <div class="col-md-8">
            <img class="main_image" v-bind:src="current_image.url" alt="Main image of project" onerror="this.src='<?php echo URL_IMG ?>front/sm_coming_soon.png'">
            <br>

            <div class="d-flex flex-row bd-highlight my-3">
                <div class="p-2">
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon like" v-on:click="alt_like" v-show="like_status == 0">
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_liked.png" alt="Icon liked" v-on:click="alt_like" v-show="like_status > 0">
                    <?php } else { ?>
                        <img class="" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon liked">
                    <?php } ?>

                    <span class="counter">{{ project.qty_likes }}</span>
                </div>

                <?php if ( $this->session->userdata('logged') ) { ?>
                    
                    <div class="p-2 bd-highlight mt-2">Save in an ideabook</div>
                    <div class="p-2 bd-highlight">
                        <div class="dropdown">
                            <a class="btn btn-main dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                My ideabooks
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <button class="dropdown-item" type="button"
                                    v-for="(ideabook, ideabook_id) in ideabooks"
                                    v-on:click="add_to_ideabook(ideabook_id)"
                                    >
                                    {{ ideabook }}
                                </button>
                                <div class="dropdown-divider"></div>
                                <form accept-charset="utf-8" method="POST" id="new_ideabook_form" @submit.prevent="add_to_ideabook(0)" class="px-2">
                                    <input type="text" required name="post_name" class="form-control" placeholder="New..." v-model="new_ideabook_name">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div>
                <p>
                <i class="fas fa-folder-open"></i>
                    categories: <span class="badge-colibri" v-for="(descriptor, descriptor_key) in descriptors">{{ descriptor.title }}</span>
                </p>
            </div>
            

        </div>
        <div class="col-md-4">
            <div>
                <h2>{{ user.display_name }}</h2>
                <p>
                    <?= $row_user->address ?>, <?= $row_user->address_line_2 ?>
                    <br>
                    <?= $row_user->zip_code ?> <?= $row_user->city ?>, <?= $row_user->state_province ?>
                </p>
                <p>
                    Phone number: <?= $row_user->phone_number ?>
                </p>
            </div>
            <div>
                <h1><?= $row->post_name ?></h1>
                <p>
                    Price US: <?= $this->pml->money($row->integer_1) ?>
                </p>
                <div class="mb-2">
                    <button class="btn btn-white btn-block">MESSAGE</button>
                </div>
                <?php if ( $editable ) { ?>
                    <a href="<?= base_url("projects/edit/{$row->id}") ?>" class="btn btn-white btn-block mb-2"> EDIT </a>
                <?php } ?>
            </div>
            <div class="gallery-2 mb-2">
                <div class="image-container" v-for="(image, image_key) in images" v-on:click="set_current_image(image_key)">
                    <img class="picture" v-bind:src="image.url_thumbnail" alt="Project image" onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#proyect_profile',
        created: function(){
            this.set_current_image(0);
        },
        data: {
            project: <?= json_encode($row) ?>,
            user: <?= json_encode($row_user) ?>,
            images: <?= json_encode($images->result()) ?>,
            current_image: {
                url: '<?= URL_IMG ?>front/md_coming_soon.png'
            },
            descriptors: <?= json_encode($descriptors->result()) ?>,
            ideabooks: <?= json_encode($this->session->userdata('ideabooks')) ?>,
            new_ideabook_name: '',
            like_status: <?= $like_status ?>,
            su_id: su_id,

        },
        methods: {
            set_current_image: function(image_key){
                if ( this.images.length )
                {
                    this.current_image = this.images[image_key];
                }
            },
            add_to_ideabook: function(ideabook_id){
                axios.post(url_api + 'ideabooks/add_project/' + ideabook_id + '/' + this.project.id, $('#new_ideabook_form').serialize())
                .then(response => {
                    //console.log(response.data);
                    if ( response.data.saved_id > 1 )
                    {
                        this.ideabooks = response.data.ideabooks;
                        toastr['success']('Added to ideabook');
                        this.new_ideabook_name = '';
                    }
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            alt_like: function(){
                axios.get(url_api + 'posts/alt_like/' + this.project.id)
                .then(response => {
                    this.like_status = response.data.like_status;
                    if ( this.like_status == 1 ) {
                        this.project.qty_likes++;
                    } else {
                        this.project.qty_likes--;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>

<?php $this->load->view('projects/comments/comments_v') ?>