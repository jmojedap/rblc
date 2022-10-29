<div id="social_links">
    <button class="btn btn-primary w120p mb-2" v-on:click="clean_form">
        <i class="fa fa-plus"></i> Nuevo
    </button>


    <form accept-charset="utf-8" method="POST" id="social_form" @submit.prevent="send_form">
        
        <table class="table bg-white">
            <tbody>
                <tr v-for="(element, element_key) in list" v-bind:class="{'table-info': element.id == form_values.id }">
                    <td class="text-right">{{ element.type }}</td>
                    <td>
                        <a v-bind:href="element.url" target="_blank">
                            {{ element.url }}
                        </a>
                    </td>
                    <td width="100px">
                        <button class="a4" v-on:click="set_current(element_key)" type="button">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="a4" v-on:click="set_current(element_key)" type="button" data-toggle="modal" data-target="#delete_modal">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= form_dropdown('related_1', $options_link_type, '', 'class="form-control" v-model="form_values.related_1"') ?>
                    </td>
                    <td>
                        <input
                            name="text_1" id="field-text_1" type="url" class="form-control"
                            required
                            title="URL" placeholder="URL"
                            v-model="form_values.url"
                        >
                    </td>
                    <td>
                        <button class="btn btn-success" type="submit">
                            Guardar
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
    new Vue({
        el: '#social_links',
        created: function(){
            this.get_list();
        },
        data: {
            list: <?= json_encode($social_links->result()); ?>,
            form_values: {
                id: '0',
                related_1: '099',
                text_1: ''
            },
            user_id: '<?= $row->id ?>'
        },
        methods: {
            get_list: function(){
                axios.get(URL_APP + 'users/get_social_links/' + this.user_id)
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            send_form: function(){
                axios.post(URL_APP + 'users/save_social_link/' + this.user_id, $('#social_form').serialize())
                .then(response => {
                    if ( response.data.saved_id > 0 ) {
                        toastr["success"]('Guardado');
                        this.get_list();
                        this.clean_form();
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            set_current: function(element_key){
                this.form_values.id = this.list[element_key].id;
                this.form_values.url = this.list[element_key].url;
                this.form_values.related_1 = '0' + this.list[element_key].related_1;
            },
            clean_form: function(){
                //Limpiar datos
                this.form_values.id = 0;
                this.form_values.related_1 = '099';
                this.form_values.url = '';
            },
            delete_element: function(){
                axios.get(URL_APP + 'users/delete_meta/' + this.user_id + '/' + this.form_values.id)
                .then(response => {
                    this.get_list();
                    this.clean_form();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>