<div class="users">
    <div class="user" v-for="(user, key) in list" v-bind:id="`row_` + element.id">
        <div class="row">
            <div class="col-md-9 pt-2">
                <h2>{{ user.display_name }}</h2>
                <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ user.city }}, {{ user.state_province }}</p>
                <div class="overflow-hidden" v-html="user.about" style="max-height: 100px;"></div>
                <p>
                    <small><a v-bind:href="`<?= base_url('professionals/profile/') ?>` + user.id + `/` + user.username">View more +</a></small>
                </p>
            </div>
            <div class="col-md-3">
                <a v-bind:href="`<?= base_url('professionals/profile/') ?>` + user.id + `/` + user.username">
                    <img
                        v-bind:src="user.url_thumbnail"
                        class="w100pc"
                        alt="User image"
                        onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                    >
                </a>
            </div>
        </div>
    </div>
</div>