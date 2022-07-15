<script>
var product_images = new Vue({
    el: '#product_images',
    created: function(){
        this.get_list();
    },
    data: {
        file: '',
        product_id: '<?= $row->id ?>',
        images: <?= json_encode($images->result()); ?>,
        curr_image: {}
    },
    methods: {
        get_list: function(){
            axios.get(url_api + 'posts/get_images/' + this.product_id)
            .then(response => {
                this.images = response.data.images;
            })
            .catch(function (error) { console.log(error) })
        },
        send_file_form: function(){
            let form_data = new FormData();
            form_data.append('file_field', this.file)
            form_data.append('table_id', '2000')
            form_data.append('related_1', this.product_id)

            axios.post(url_api + 'files/upload/', form_data, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Cargar imágenes
                if ( response.data.status == 1 ) { this.get_list(); }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                //Limpiar formulario
                $('#field-file').val(''); 
            })
            .catch(function (error) { console.log(error) })
        },
        handle_file_upload(){
            this.file = this.$refs.file_field.files[0]
        },
        set_current: function(key){
            this.curr_image = this.images[key]
        },
        delete_element: function(){
            var file_id = this.curr_image.id
            axios.get(url_api + 'files/delete/' + file_id)
            .then(response => {
                this.get_list()
            })
            .catch(function (error) { console.log(error) })
        },
        set_main_image: function(key){
            this.set_current(key)
            var file_id = this.curr_image.id
            console.log(this.curr_image)
            axios.get(url_api + 'posts/set_main_image/' + this.product_id + '/' + file_id)
            .then(response => {
                if ( response.data.status == 1 ) this.get_list()
            })
            .catch(function (error) { console.log(error) })
        },
        update_position: function(file_id, new_position){
            axios.get(url_api + 'files/update_position/' + file_id + '/' + new_position)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.get_list()
                } else {
                    toastr['warning']('No se cambió el orden de las imágenes')
                }
            })
            .catch(function(error) { console.log(error) })
        },
    }
});
</script>