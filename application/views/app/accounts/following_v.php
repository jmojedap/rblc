<div class="full_width_title">
    <h3>Who I'm following</h3>
</div>

<div id="following_app">
    <div class="users">
        <div class="user" v-for="(user, key) in list" v-bind:id="`row_` + user.id">
            <div class="row">
                <div class="col-md-9 pt-2">
                    <h2>{{ user.display_name }}</h2>
                    <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ user.city }}, {{ user.state_province }}</p>
                    <div class="mb-2">
                        <button class="btn btn-white">
                            Message
                        </button>
                    </div>
                    <div class="overflow-hidden" v-html="user.about" style="max-height: 100px;"></div>
                    <p>
                        <small><a v-bind:href="`<?= URL_FRONT . 'professionals/profile/' ?>` + user.id + `/` + user.username">View more +</a></small>
                    </p>
                </div>
                <div class="col-md-3">
                    <a v-bind:href="`<?= URL_FRONT . 'professionals/profile/' ?>` + user.id + `/` + user.username">
                        <img
                            v-bind:src="user.url_thumbnail"
                            class="w100pc"
                            alt="User image"
                            onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
                        >
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#following_app',
        created: function(){
            this.get_list();
        },
        data: {
            user_id: APP_UID,
            list: []
        },
        methods: {
            get_list: function(){
                axios.get(URL_API + 'users/following/' + APP_UID)
                .then(response => {
                    this.list = response.data.list
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>