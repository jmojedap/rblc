<div id="edit_ideabook">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="send_form">
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
                            v-model="form_values.excerpt"
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
</div>

<?php
$this->load->view('ideabooks/edit/vue_v');