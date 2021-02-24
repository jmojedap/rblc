<div id="add_project">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Name</label>
                    <div class="col-md-8">
                        <input
                            id="field-post_name" class="form-control"
                            name="post_name"
                            required
                            autofocus
                            v-model="form_values.post_name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="related_2" class="col-md-4 col-form-label text-right">Type</label>
                    <div class="col-md-8">
                        <select name="related_2" class="form-control" required>
                            <option v-for="(option_related_2, key_related_2) in options_project_type" v-bind:value="key_related_2">{{ option_related_2 }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="integer_1" class="col-md-4 col-form-label text-right">Price in US</label>
                    <div class="col-md-8">
                        <input
                            name="integer_1" type="text" class="form-control"
                            required title="Price"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8 col-sm-12">
                        <button class="btn btn-main w120p" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Project created</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Project created
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="go_created">
                        Open project
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clean_form" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Create another
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view('projects/add_vue_v');