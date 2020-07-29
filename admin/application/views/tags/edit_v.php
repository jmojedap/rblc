<div id="edit_tag" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="tag_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="post_name" class="col-md-4 col-form-label text-right">Name</label>
                    <div class="col-md-8">
                        <input
                            name="name" id="field-name"
                            type="text" class="form-control"
                            required
                            value="<?php echo $row->name ?>"
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
    new Vue({
        el: '#edit_tag',
        data: {
            row_id: '<?php echo $row->id ?>'
        },
        methods: {
            send_form: function(){
                axios.post(app_url + 'tags/update/' + this.row_id, $('#tag_form').serialize())
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