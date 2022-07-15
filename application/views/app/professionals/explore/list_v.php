<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div class="users" v-show="!loading">
    <div class="user" v-for="(user, key) in list" v-bind:id="`row_` + element.id">
        <div class="row">
            <div class="col-md-9 pt-2">
                <a v-bind:href="`<?= URL_FRONT . 'professionals/profile/' ?>` + user.id + `/` + user.username" class="title_list">
                    <h2>{{ user.display_name }}</h2>
                </a>
                <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ user.city }}, {{ user.state_province }}</p>
                <div v-html="user.about" style="max-height: 150px; overflow: hidden;"></div>
                <small><a v-bind:href="`<?= URL_FRONT . 'professionals/profile/' ?>` + user.id + `/` + user.username">View more +</a></small>
            </div>
            <div class="col-md-3">
                <a v-bind:href="`<?= URL_FRONT . 'professionals/profile/' ?>` + user.id + `/` + user.username">
                    <img
                        v-bind:src="user.url_thumbnail"
                        class="w100pc"
                        alt="User image"
                        onerror="this.src='<?= URL_IMG ?>users/md_user.png'"
                    >
                </a>
            </div>
        </div>
    </div>
</div>