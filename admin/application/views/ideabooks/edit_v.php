<div id="edit_ideabook" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="ideabook_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Project name</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-post_name"
                            name="post_name"
                            required
                            class="form-control"
                            placeholder="Project name"
                            title="Project name"
                            value="<?php echo $row->post_name ?>"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="excerpt" class="col-md-4 col-form-label text-right">Description</label>
                    <div class="col-md-8">
                        <textarea
                            id="field-excerpt"
                            name="excerpt"
                            class="form-control"
                            title="Description"
                            rows="3"
                            ><?= $row->excerpt ?></textarea>
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
        el: '#edit_ideabook',
        created: function(){
            //this.get_list();
        },
        data: {
            row_id: '<?php echo $row->id ?>'
        },
        methods: {
            send_form: function(){
                axios.post(app_url + 'ideabooks/update/' + this.row_id, $('#ideabook_form').serialize())
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