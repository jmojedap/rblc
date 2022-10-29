<!-- Modal -->
<div class="modal fade" id="modal_upload_file" tabindex="-1" role="dialog" aria-labelledby="modal_upload_file" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <?php $this->load->view('app/professionals/images/upload_file_form_v') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light w100p" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
var modal_upload_file = new Vue({
    el: '#modal_upload_file',
    created: function(){
        //this.get_list()
    },
    data: {
        form_values: { cat_1: '' },
        user_id: APP_UID,
        loading: false,
        options_cat_1: <?= json_encode($options_cat_1) ?>
    },
    methods: {
        send_file_form: function(){
            let form_data = new FormData();
            form_data.append('file_field', this.file);
            form_data.append('table_id', 1000);   //Imagen asociada a registro en la tabla user (1000)
            form_data.append('related_1', this.user_id);   //Imágenes generales de usuario
            form_data.append('album_id', 10);   //Imágenes generales de usuario
            form_data.append('cat_1', this.form_values.cat_1)

            axios.post(URL_API + 'files/upload/' + this.user_id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Cargar imágenes
                if ( response.data.status == 1 ) {
                    $('#modal_upload_file').modal('hide')
                    user_profile.get_images()
                    console.log(response.data)
                }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                //Limpiar formulario
                $('#field-file').val(''); 
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        handle_file_upload(){
            this.file = this.$refs.file_field.files[0];
        },
    }
})
</script>