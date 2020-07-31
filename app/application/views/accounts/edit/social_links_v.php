<?php
    $link_types = $this->db->get_where('item', 'category_id = 44 AND item_group = 1');
?>

<div id="social_links_app">
    <div class="card center_box_750">
        <div class="card-body">
            <h2>Social links</h2>
            <table class="table d-none">
                <tbody>
                    <tr v-for="(link_type, link_type_key) in link_types">
                        <td>{{ link_type.item_name }}</td>
                        <td></td>
                        <td width="10px">
                            <button class="a4">
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <form accept-charset="utf-8" method="POST" id="social_links_form" @submit.prevent="send_form">
                <?php foreach ( $link_types->result() as $row_type ) { ?>
                    <div class="form-group row">
                        <label for="<?= $row_type->slug ?>_link" class="col-md-4 col-form-label text-right"><?= $row_type->item_name ?></label>
                        <div class="col-md-8">
                            <input
                                name="<?= $row_type->slug ?>" id="field-<?= $row_type->slug ?>" type="url" class="form-control"
                                v-model="form_values.<?= $row_type->slug ?>"
                            >
                        </div>
                    </div>
                <?php } ?>
                <div class="from-group row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-main w120p" type="submit">
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
        el: '#social_links_app',
        created: function(){
            this.get_social_links();
        },
        data: {
            user_id: <?= $row->id ?>,
            social_links: [],
            link_types: <?= json_encode($link_types->result()) ?>,
            form_values: {facebook: '', instagram: '', twitter: '', linkedin: '', youtube: ''}
        },
        methods: {
            get_social_links: function(){
                axios.get(url_api + 'users/get_social_links/' + this.user_id)
                .then(response => {
                    this.social_links = response.data.list;
                    this.social_links.forEach(element => {
                        this.form_values[element.type] = element.url;
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function(){
                axios.post(url_api + 'accounts/save_social_links/', $('#social_links_form').serialize())
                .then(response => {
                    if ( response.data.qty_saved > 0 ) {
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