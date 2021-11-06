<script>
    var form_values = {
        post_name: '<?= $row->post_name ?>',
        excerpt: '<?= $row->excerpt ?>',
        text_1: '<?= $row->text_1 ?>',
        integer_1: '<?= $row->integer_1 ?>'
    };
            
    new Vue({
        el: '#edit_ideabook',
        data: {
            form_values: form_values,
            ideabook_id: <?= $row->id ?>
        },
        methods: {
            send_form: function() {
                axios.post(url_api + 'ideabooks/update/' + this.ideabook_id, $('#edit_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        toastr['success']('Ideabook updated');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            //Seleccionar clase fondo y código fondo
            select_background: function(bg_number){
                var bg_code = '0' + bg_number;
                this.form_values.integer_1 = bg_number;
                this.form_values.text_1 = 'ideabook-' + bg_code.substring(bg_code.length - 2, bg_code.length);
            }
        }
    });
</script>