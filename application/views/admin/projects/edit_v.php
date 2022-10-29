<?php
    $current_styles = $this->pml->query_to_array($styles, 'related_1', 'meta_id');
    $current_descriptors = $this->pml->query_to_array($descriptors, 'related_1', 'meta_id');
    $current_feelings = $this->pml->query_to_array($feelings, 'related_1', 'meta_id');
?>

<?php $this->load->view('assets/bs4_chosen') ?>

<div id="edit_project" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="project_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Name</label>
                    <div class="col-md-8">
                        <input
                            type="text" name="post_name" class="form-control"
                            required placeholder="Project name" title="Project name"
                            value="<?= $row->post_name ?>"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="related_2" class="col-md-4 col-form-label text-right">Type</label>
                    <div class="col-md-8">
                        <select name="related_2" class="form-control" required v-model="row.related_2">
                            <option v-for="(option_related_2, key_related_2) in options_project_types" v-bind:value="key_related_2">{{ option_related_2 }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="integer_1" class="col-md-4 col-form-label text-right">Project price in US</label>
                    <div class="col-md-8">
                        <input
                            id="field-integer_1"
                            type="text"
                            required
                            name="integer_1"
                            class="form-control"
                            title="Price"
                            value="<?= $row->integer_1 ?>"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="descriptors" class="col-md-4 col-form-label text-right">Descriptors</label>
                    <div class="col-md-8">
                        <select name="descriptors[]" id="field-descriptors" multiple class="form-control form-control-chosen">
                            <?php foreach ( $options_descriptors as $descriptor_key => $descriptor_name ) { ?>
                                <?php
                                    $selected = '';
                                    if ( in_array($descriptor_key, $current_descriptors) ) { $selected = 'selected'; }
                                ?>
                                <option value="0<?= $descriptor_key ?>" <?= $selected ?>><?= $descriptor_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="styles" class="col-md-4 col-form-label text-right">Styles</label>
                    <div class="col-md-8">
                        <select name="styles[]" id="field-styles" multiple class="form-control form-control-chosen">
                            <?php foreach ( $options_styles as $style_key => $style_name ) { ?>
                                <?php
                                    $selected = '';
                                    if ( in_array($style_key, $current_styles) ) { $selected = 'selected'; }
                                ?>
                                <option value="0<?= $style_key ?>" <?= $selected ?>><?= $style_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="feelings" class="col-md-4 col-form-label text-right">Feelings</label>
                    <div class="col-md-8">
                        <select name="feelings[]" id="field-feelings" multiple class="form-control form-control-chosen">
                            <?php foreach ( $options_feelings as $feeling_key => $feeling_name ) { ?>
                                <?php
                                    $selected = '';
                                    if ( in_array($feeling_key, $current_feelings) ) { $selected = 'selected'; }
                                ?>
                                <option value="0<?= $feeling_key ?>" <?= $selected ?>><?= $feeling_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="related_1" class="col-md-4 col-form-label text-right">Owner Professional ID</label>
                    <div class="col-md-8">
                        <input
                            name="related_1" type="text" class="form-control"
                            required
                            value="<?= $row->related_1 ?>"
                        >
                    </div>
                </div>

                

                <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success w120p" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var edit_project = new Vue({
        el: '#edit_project',
        data: {
            row:{
                id: '<?= $row->id ?>',
                related_2: '0<?= $row->related_2 ?>'
            },
            options_project_types: <?= json_encode($options_project_types) ?>
        },
        methods: {
            send_form: function(){
                axios.post(URL_API + 'projects/update_full/' + this.row.id, $('#project_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        toastr['success']('Saved')
                    }
                })
                .catch(function (error) { console.log(error) })
            },
        }
    });
</script>