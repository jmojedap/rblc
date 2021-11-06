<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $current_tags = $this->pml->query_to_array($tags, 'id');
    $pct_checked = $this->pml->percent($qty_checked, $qty_files);
?>

<div id="picture_app" class="">
    <div class="d-flex justify-content-around">
        <a href="<?= $this->url_controller . "check_previous/{$row->id}" ?>" class="btn btn-light w120p">Anterior</a>
        <div>
            Revisados <strong><?= $qty_checked ?></strong> / <?= $qty_files ?>
        </div>
        <div>
            
        </div>
        <a href="<?= $this->url_controller . "check_next/" ?>" class="btn btn-light w120p">Siguiente</a>
    </div>
    <div class="progress my-2">
        <div class="progress-bar" role="progressbar" style="width: <?= $pct_checked ?>%;" aria-valuenow="<?= $pct_checked ?>" aria-valuemin="0" aria-valuemax="100"><?= $pct_checked ?>%</div>
    </div>
    <hr>
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
                                    name="title" id="field-title" type="text" class="form-control"
                                    required
                                    value="<?= $row->title ?>"
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
                        <div class="form-group row">
                            <label for="keywords" class="col-md-4 col-form-label text-right">Keywords</label>
                            <div class="col-md-8">
                                <textarea name="keywords" id="field-keywords" class="form-control" rows="3"><?= $row->keywords ?></textarea>
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
            <div class="mt-2">
                <p>
                    <span class="text-muted">Creator:</span>
                    <a href="<?= URL_FRONT . "professionals/profile/{$row->creator_id}" ?>" target="_blank">
                        <?= $this->App_model->name_user($row->creator_id) ?>
                    </a>
                    &middot;
                    <span class="text-muted">ID File:</span>
                    <a href="<?= $this->url_controller . "info/{$row->id}" ?>" target="_blank">
                        <?= $row->id ?>
                    </a>
                </p>
            </div>
            <div class="mt-2">
                <button class="btn btn-warning" data-toggle="modal" data-target="#delete_modal">
                    <i class="fa fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
var picture_app = new Vue({
    el: '#picture_app',
    data: {
        file_id: <?= $row->id ?>,
        form_values: {
            cat_1: '0<?= $row->cat_1 ?>'
        },
        options_cat_1: <?= json_encode($options_cat_1) ?>
    },
    methods: {
        send_form: function(){
            axios.post(url_api + 'files/update_full/' + this.file_id, $('#picture_form').serialize())
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Guardado, abriendo siguiente imagen')
                    this.go_to_next()
                }
            })
            .catch(function (error) { console.log(error) })
        },
        delete_element: function(){
            axios.get(url_api + 'files/delete/' + this.file_id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Imagen eliminada')
                    this.go_to_next()
                } else{
                    toastr['info']('La imagen no se eliminó')
                }
            })
            .catch(function(error) { console.log(error) })
        },
        go_to_next: function(){
            setTimeout(() => {
                window.location = url_app + 'files/check_next'
            }, 1000)
        },
    }
});
</script>