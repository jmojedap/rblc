<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $current_tags = $this->pml->query_to_array($tags, 'id');
?>

<div id="picture_app">
    <div class="row mt-2">
        <div class="col-md-6">
            <img class="w100pc" src="<?= $row->url ?>" alt="Picture">
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="picture_form" @submit.prevent="send_form">
                        <div class="form-group row">
                            <label for="title" class="col-md-4 col-form-label text-right">Title</label>
                            <div class="col-md-8">
                                <input
                                    name="title" type="text" class="form-control" required value="<?= $row->title ?>"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cat_1" class="col-md-4 col-form-label text-right">Picture category</label>
                            <div class="col-md-8">
                                <select name="cat_1" v-model="form_values.cat_1" class="form-control" required>
                                    <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-right">Description</label>
                            <div class="col-md-8">
                                <textarea name="description" id="field-description" class="form-control" required rows="3" maxlength="280"><?= $row->description ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="group_1" class="col-md-4 col-form-label text-right">Position in Home</label>
                            <div class="col-md-8">
                                <input
                                    name="group_1" type="number" class="form-control"
                                    required
                                    title="Position in Home"
                                    v-model="form_values.group_1"
                                >
                                <small class="form-text text-muted">Escriba un número mayor a 0 para que esta imagen aparezca en el HOME.</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="keywords" class="col-md-4 col-form-label text-right">Keywords</label>
                            <div class="col-md-8">
                                <textarea name="keywords" id="field-keywords" class="form-control" required rows="3"><?= $row->keywords ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tags" class="col-md-4 col-form-label text-right">Tags</label>
                            <div class="col-md-8">
                                <select name="tags[]" id="field-tags" multiple class="form-control form-control-chosen">
                                    <?php foreach ( $options_tag as $tag_key => $tag_name ) { ?>
                                        <?php
                                            $selected = '';
                                            if ( in_array($tag_key, $current_tags) ) { $selected = 'selected'; }
                                        ?>
                                        <option value="0<?= $tag_key ?>" <?= $selected ?>><?= $tag_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w120p" type="submit">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var picture_app = new Vue({
    el: '#picture_app',
    
    data: {
        file_id: <?= $row->id ?>,
        form_values: {
            cat_1: '0<?= $row->cat_1 ?>',
            group_1: <?= $row->group_1 ?>
        },
        options_cat_1: <?= json_encode($options_cat_1) ?>
    },
    methods: {
        send_form: function(){
            axios.post(URL_API + 'files/update_full/' + this.file_id, $('#picture_form').serialize())
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Saved');
                }
            })
            .catch(function (error) {
                console.log(error) })
        },
    }
});
</script>