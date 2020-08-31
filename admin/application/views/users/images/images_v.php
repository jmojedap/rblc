<div id="user_images">
    <div class="card center_box_750">
        <div class="card-body">
            <?php $this->load->view('common/upload_file_form_v') ?>
        </div>
    </div>
    <hr>
    <div class="d-flex flex-wrap">
        <img class="w120p mr-1 mb-1 pointer"
            v-for="(image, image_key) in images"
            v-bind:alt="image.title"
            v-bind:src="image.url_thumbnail"
            v-on:click="set_current(image_key)"
            data-toggle="modal" data-target="#detail_modal"
            >
    </div>

    <?php $this->load->view('users/images/modal_image_v.php') ?>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
    new Vue({
        el: '#user_images',
        created: function(){
            this.get_list();
        },
        data: {
            file: '',
            user_id: '<?= $row->id ?>',
            images: [],
            current: {}
        },
        methods: {
            get_list: function(){
                axios.get(url_app + 'professionals/get_images/' + this.user_id)
                .then(response => {
                    this.images = response.data.images;
                    if ( this.images.lenght > 0 )
                    {
                        this.current = this.images[0];
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            send_file_form: function(){
                let form_data = new FormData();
                form_data.append('file_field', this.file);
                form_data.append('table_id', 1000);   //Imagen asociada a registro en la tabla user (1000)
                form_data.append('related_1', this.user_id);   //Imágenes generales de usuario
                form_data.append('album_id', 10);   //Imágenes generales de usuario

                axios.post(url_app + 'files/upload/' + this.user_id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => {
                    //Cargar imágenes
                    if ( response.data.status == 1 ) { this.get_list(); }
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
            set_current: function(key){
                this.current = this.images[key];
            },
            delete_element: function(){
                axios.get(url_app + 'files/delete/' + this.current.id)
                .then(response => {
                    toastr['info']('Image deleted');
                    this.get_list();
                })
                .catch(function (error) {
                    console.log(error);
                });  
            }
        }
    });
</script>