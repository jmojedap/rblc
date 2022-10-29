<?php
    $arr_projects = array();

    foreach ($projects->result() as $post)
    {
        $arr_projects[] = $post;
    }
?>

<div id="product_projects" class="center_box_750">
    <?php if ( $this->session->userdata('role') <= 2 ) { ?>
        <div class="card mb-2">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="projects_form" @submit.prevent="add_project" clas="form-horizontal">
                    <div class="form-group row">
                        <label for="post_id" class="col-md-2 col-form-label text-right">Project</label>
                        <div class="col-md-8">
                            <input
                                name="project_id" id="field-project_id" type="text" class="form-control"
                                required
                                title="ID Project" placeholder="ID Project"
                                v-model="project_id"
                            >
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php } ?>

    <table class="table bg-white">
        <thead>
            <th width="50px">ID</th>
            <th>Project name</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(project, project_key) in projects">
                <td>{{ project.id }}</td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "projects/info/" ?>` + `/` + project.id">
                        {{ project.title }}
                    </a>
                </td>
                <td>
                    <button class="btn btn-sm btn-warning" v-on:click="delete_meta(project.meta_id)">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#product_projects',
        created: function(){
            //this.get_list();
        },
        data: {
            ideabook_id: <?= $row->id ?>,
            projects: <?= json_encode($arr_projects) ?>,
            project_id: ''
        },
        methods: {
            add_project: function(){
                axios.get(URL_APP + 'ideabooks/add_project/' + this.ideabook_id + '/' + this.project_id)
                .then(response => {
                    console.log(response.data)
                    window.location = URL_APP + 'ideabooks/projects/' + this.ideabook_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            delete_meta: function(meta_id){
                axios.get(URL_APP + 'posts/delete_meta/' + this.ideabook_id + '/' + meta_id)
                .then(response => {
                    console.log(response.data)
                    window.location = URL_APP + 'ideabooks/projects/' + this.ideabook_id;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>