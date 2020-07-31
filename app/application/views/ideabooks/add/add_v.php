<div id="add_ideabook">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="add_form" accept-charset="utf-8" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Ideabook name</label>
                    <div class="col-md-8">
                        <input
                            id="field-post_name" class="form-control"
                            name="post_name"
                            required
                            autofocus
                            maxlength="14"
                            placeholder="maximum 14 characteres"
                            v-model="form_values.post_name">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="excerpt" class="col-md-4 col-form-label text-right">Description</label>
                    <div class="col-md-8">
                        <input
                            id="field-excerpt"
                            type="text"
                            required
                            name="excerpt"
                            class="form-control"
                            title="maximum 50 characteres"
                            placeholder="maximum 50 characteres"
                            maxlength="50"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8 col-sm-12">
                        <button class="btn btn-main w120p" type="submit">Save</button>
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
                    <h5 class="modal-title" id="exampleModalLongTitle">Ideabook created</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Ideabook created
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="go_created">
                        Open ideabook
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
$this->load->view('ideabooks/add/vue_v');