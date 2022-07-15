<?php
    $current_services = $this->pml->query_to_array($services, 'related_1', 'meta_id');
    $current_tags = $this->pml->query_to_array($tags, 'related_1', 'meta_id');
?>

<?php $this->load->view('assets/bs4_chosen') ?>

<div id="editServices" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="servicesForm" @submit.prevent="send_form">
                

                <div class="form-group row">
                    <label for="services" class="col-md-4 col-form-label text-right">Professional services</label>
                    <div class="col-md-8">
                        <select name="services[]" id="field-services" multiple class="form-control form-control-chosen">
                            <?php foreach ( $options_services as $service_key => $service_name ) { ?>
                                <?php
                                    $selected = '';
                                    if ( in_array($service_key, $current_services) ) { $selected = 'selected'; }
                                ?>
                                <option value="0<?= $service_key ?>" <?= $selected ?>><?= $service_name ?></option>
                            <?php } ?>
                        </select>
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
                        <button class="btn btn-success w120p" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var editServices = new Vue({
    el: '#editServices',
    data: {
        row_id: '<?php echo $row->id ?>'
    },
    methods: {
        send_form: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('servicesForm'))
            axios.post(url_api + 'professionals/update_services/' + this.row_id, formValues)
            .then(response => {
                if (response.data.status == 1) {
                    toastr['success']('Saved');
                }
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>