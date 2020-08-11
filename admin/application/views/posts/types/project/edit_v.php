<div id="edit_project" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="project_form" @submit.prevent="send_form">
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
                    <label for="integer_1" class="col-md-4 col-form-label text-right">Project price in US</label>
                    <div class="col-md-8">
                        <input
                            name="integer_1"
                            type="text" required class="form-control"
                            title="Price"
                            value="<?= $row->integer_1 ?>"
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
        el: '#edit_project',
        created: function(){
            //this.get_list();
        },
        data: {
            row_id: '<?php echo $row->id ?>'
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'posts/update/' + this.row_id, $('#project_form').serialize())
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