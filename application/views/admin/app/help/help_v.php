<div id="help_app">
    <div class="row">
        <div class="col-md-4">
            <form accept-charset="utf-8" method="POST" id="help_form" @submit.prevent="get_list" class="mb-2">
            <div class="input-group mb-3">
                <input
                    name="q" type="text" class="form-control"
                    required
                    title="Buscar..." placeholder="Buscar..."
                    v-model="form_values.q"
                >
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
                </div>
                </div>

            </form>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action"
                    v-bind:class="{'active': post_key == key }"
                    v-for="(post, post_key) in posts"
                    v-on:click="set_key(post_key)">
                    {{ post.post_name }}
                </a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card mw750p">
                <div class="card-body">
                    <h3 class="text-center">{{ article.post_name }}</h3>
                    <div v-html="article.content"></div>
                    <div class="my-2">
                        <a v-bind:href="`<?= URL_ADMIN . "posts/edit/" ?>` + article.id" class="btn btn-light btn-sm w120p">
                            <i class="fa fa-pencil-alt"></i>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var article_id = <?= $article_id ?>;

// VueApp
//-----------------------------------------------------------------------------
var help_app = new Vue({
    el: '#help_app',
    created: function(){
        this.get_list();
    },
    data: {
        posts: [],
        key: -1,
        article_id: article_id,
        article: { post_name: 'Loading...', content: ''},
        form_values: { q: ''}
    },
    methods: {
        get_list: function(){
            var form_data = new FormData;
            form_data.append('type', 20);   //Help article post type
            form_data.append('q', this.form_values.q);
            form_data.append('o', 'integer_1');
            form_data.append('ot', 'DESC');
            
            axios.post(url_api + 'posts/get/1', form_data)
            .then(response => {
                this.posts = response.data.list
                if ( this.article_id == 0 ) {
                    this.set_key(0)
                } else {
                    this.get_info(article_id)
                    var is_article = (element) => element.id == article_id
                    this.key = this.posts.findIndex(is_article)
                }
            })
            .catch(function (error) { console.log(error) })
        },
        set_key: function(key){
            this.key = key
            article_id = this.posts[key].id
            this.get_info(article_id)
        },
        get_info: function(article_id){
            axios.get(url_api + 'posts/get_info/' + article_id)
            .then(response => {
                this.article = response.data.row;
                history.pushState(null, null, url_admin + 'app/help/' + article_id);
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>