<div id="user_image_app" class="center_box_450">
    <div class="card">
        <img
            class="card-img-top" alt="User image"
            v-bind:src="user.url_image"
            onerror="this.src='<?= URL_IMG ?>users/user.png'"
        >
        <div class="card-body">
            <div v-show="user.image_id == 0">
                <div class="text-center my-2" v-if="loading">
                    <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
                </div>
                <div v-else>
                    <?php $this->load->view('common/upload_file_form_v') ?>
                </div>
            </div>
            <div v-show="user.image_id > 0">
                <a class="btn btn-light" id="btn_crop" href="<?= URL_FRONT . "accounts/edit/cropping" ?>">
                    <i class="fa fa-crop"></i>
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#delete_modal">
                    <i class="fa fa-trash"></i>
                </button>
            </div>

        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
var user_image_app = new Vue({
    el: '#user_image_app',
    data: {
        loading: false,
        user: {
            image_id: <?= $row->image_id ?>,
            url_image: '<?= $row->url_image ?>'
        },
        default_image: '<?= URL_IMG ?>users/user.png'
    },
    methods: {
        send_file_form: function(){
            this.loading = true
            let formValues = new FormData();
            formValues.append('file_field', this.file);

            axios.post(URL_API + 'accounts/set_image/', formValues, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                this.loading = false
                //Cargar imagen
                if ( response.data.status == 1 )
                { 
                    this.user.image_id = response.data.image_id;
                    this.user.url_image = response.data.url_image;
                    window.location = URL_APP + 'accounts/edit/cropping';
                }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                //Limpiar formulario
                $('#field-file').val('');
            })
            .catch(function (error) { console.log(error) })
        },
        handle_file_upload(){
            this.file = this.$refs.file_field.files[0];
        },
        delete_element: function(){
            axios.get(URL_API + 'accounts/remove_image/')
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.user.image_id = 0;
                    this.user.url_image = this.default_image;
                }
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>