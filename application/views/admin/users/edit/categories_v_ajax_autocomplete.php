<?php $this->load->view('assets/jquery_autocomplete') ?>

<style>
    li.label-deleteable{
        background-clip: padding-box;
        position: relative;
        max-width: 320px;
        margin: .175rem .25rem;
        padding: .25rem 1.5rem .25rem .25rem;
        border: 1px solid #ced4da;
        background-color: #f8f9fa;
        border-top-left-radius: 2px;
        border-top-right-radius: 2px;
        border-bottom-right-radius: 2px;
        border-bottom-left-radius: 2px;
        cursor: default;
        font-size: 0.875rem;
        line-height: 1;
        color: #6c757d;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    li.label-deleteable a{
        display: inline-block;
        position: absolute;
        top: .2rem;
        right: .125rem;
        width: 1rem;
        height: 1rem;
        cursor: pointer;
        background-size: 1rem 1rem;
        background-position: center center;
        background-repeat: no-repeat;
    }
</style>

<script>
    var user_tags = [];


    $( function() {
      
        $("#field-tags").autocomplete({
            source: "<?= URL_ADMIN . 'app/arr_elements/tag' ?>",
            minLength: 3,
            select: function( event, ui ) { log(ui.item); }
        });

        function log(item) {
            user_tags.push(item);
            //console.log(user_tags);
            $('#field-tags').val();
            //$('#field_tags_array').val(user_tags);
            update_tags_display();
        }

        function update_tags_display()
        {
            var html_content = '';
            user_tags.forEach(element => {
                console.log(element);
                
                
                html_content += '<li class="label-deleteable"><span>'+ element.value +'</span><a><i class="fa fa-times"></i></a></li>';
            });
            $('#tags_display').html(html_content);
        }
    } );
</script>

<?php
    $current_services = $this->pml->query_to_array($services, 'related_1', 'meta_id');
?>

<?php $this->load->view('assets/bs4_chosen') ?>

<div id="edit_user" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="user_form" @submit.prevent="send_form">
                

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
                        <input
                             id="field-tags" type="text" class="form-control"
                            title="Search a tag" placeholder="Search a tag"
                        >
                        <input type="text" name="tags[]" class="form-control mt-2" id="field_tags_array">
                        <div class="chosen-container-multi">
                            <ul id="tags_display" class="chosen-choises"></ul>
                        </div>
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
    new Vue({
        el: '#edit_user',
        created: function(){
            //this.get_list();
        },
        data: {
            row_id: '<?= $row->id ?>'
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'professionals/update_categories/' + this.row_id, $('#user_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        toastr['success']('Saved');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>