<div class="users">
    <div class="user" v-for="(project, key) in list" v-bind:id="`row_` + project.id">
        <div class="row">
            <div class="col-md-9 pt-2">
                <a v-bind:href="`<?= base_url('projects/info/') ?>` + project.id + `/` + project.slug">
                    <h2>{{ project.name }}</h2>
                </a>
                <p>
                    <small><a v-bind:href="`<?= base_url('projects/info/') ?>` + project.id + `/` + project.slug">View more +</a></small>
                </p>
            </div>
            <div class="col-md-3">
                <a v-bind:href="`<?= base_url('projects/info/') ?>` + project.id + `/` + project.slug">
                    <img
                        v-bind:src="project.url_thumbnail"
                        class="w100pc"
                        alt="Project image"
                        onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                    >
                </a>
            </div>
        </div>
    </div>
</div>