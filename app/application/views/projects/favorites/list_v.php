<div class="">
    <div class="row">
        <div class="col-md-4" v-for="(project, key) in list" v-bind:id="`row_` + project.id">
            <a v-bind:href="`<?= base_url('projects/info/') ?>` + project.id + `/` + project.slug">
                <img
                    v-bind:src="project.url_thumbnail"
                    class="w100pc mb-2"
                    alt="Project image"
                    onerror="this.src='<?php echo URL_IMG ?>front/sm_coming_soon.png'"
                >
            </a>
        </div>
    </div>
</div>