<div id="post_comments">
    <div class="center_box_750 mb-2">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modal_form">
            Agregar comentario
        </button>
    </div>
    <div class="card center_box_750 mb-2">
        <div class="card-body">
            <div v-for="(comment, key) in comments">
                <div class="float-right">
                    <button class="a4" v-on:click="set_parent(key)" title="Responder">
                        <i class="fas fa-reply"></i>
                    </button>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <b>{{ comment.display_name }}</b> &middot; {{ comment.username }} &middot; {{ comment.created_at | ago }}
                <br>
                <p>
                    {{ comment.comment_text }}
                </p>
                <p>
                <button class="btn btn-sm btn-light" v-on:click="get_subcomments(key)">
                    <i class="far fa-comment"></i> {{ comment.qty_comments }}
                </button>
                </p>
                <hr>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div v-for="(subcomment, subcomment_key) in subcomments">
                <div class="float-right">
                    <button class="a4" v-on:click="set_parent(subcomment_key)" title="Responder">
                        <i class="fas fa-reply"></i>
                    </button>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(subcomment_key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <b>{{ subcomment.display_name }}</b> &middot; {{ subcomment.username }} &middot; {{ subcomment.created_at | ago }}
                <br>
                <p>
                    {{ subcomment.comment_text }}
                </p>
                <p>
                </p>
                <hr>
            </div>
        </div>
    </div>

    <?php $this->load->view('posts/comments/modal_form_v') ?>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

// VueApp
//-----------------------------------------------------------------------------


    new Vue({
        el: '#post_comments',
        created: function(){
            this.get_list();
        },
        data: {
            post_id: '<?= $row->id ?>',
            comments: [],
            subcomments: [],
            current: {},
            form_values: {
                comment_text: '',
                parent_id: 0
            }
        },
        methods: {
            get_list: function(){
                axios.get(app_url + 'comments/element_comments/2000/' + this.post_id)
                .then(response => {
                    this.comments = response.data.comments;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            get_subcomments: function(key){
                var parent_id = this.comments[key].id;
                console.log(this.comments[key]);
                axios.get(app_url + 'comments/element_comments/2000/' + this.post_id + '/' + parent_id )
                .then(response => {
                    this.subcomments = response.data.comments;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function(){
                axios.post(app_url + 'comments/save/2000/' + this.post_id, $('#comment_form').serialize())
                .then(response => {
                    if ( response.data.saved_id > 0) {
                        this.get_list();
                        this.clean_form();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            set_current: function(key){
                this.current = this.comments[key];
            },
            delete_element: function(){
                axios.get(app_url + 'comments/delete/' + this.current.id + '/' + this.post_id)
                .then(response => {
                    if ( response.data.qty_deleted > 0 ) {
                        this.get_list();
                    }
                    toastr['info']('Comentarios eliminados: ' + response.data.qty_deleted);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            clean_form: function(){
                this.form_values.comment_text = '';
            },
            set_parent: function(key){
                this.form_values.parent_id = this.comments[key].id;
            },
        }
    });
</script>