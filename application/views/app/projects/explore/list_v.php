<div class="text-center mb-2" v-show="loading">
    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
</div>

<div class="users" v-show="!loading">
    <div class="user" v-for="(project, key) in list" v-bind:id="`row_` + project.id">
        <div class="row">
            <div class="col-md-9 pt-2">
                <a v-bind:href="`<?= URL_FRONT . 'projects/info/' ?>` + project.id + `/` + project.slug" class="title_list">
                    <h2>{{ project.name }}</h2>
                </a>
                <p>
                    <small><a v-bind:href="`<?= URL_FRONT . 'projects/info/' ?>` + project.id + `/` + project.slug">View more +</a></small>
                </p>
            </div>
            <div class="col-md-3">
                <a v-bind:href="`<?= URL_FRONT . 'projects/info/' ?>` + project.id + `/` + project.slug">
                    <img
                        v-bind:src="project.url_thumbnail"
                        class="w100pc"
                        alt="Project image"
                        onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.jpg'"
                    >
                </a>
            </div>
        </div>
    </div>
</div>