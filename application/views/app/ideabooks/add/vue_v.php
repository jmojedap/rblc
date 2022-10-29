<script>
    var form_values = {
        post_name: '',
        excerpt: '',
        text_1: 'ideabook-01',
        integer_1: 1
    };
            
    new Vue({
        el: '#add_ideabook',
        data: {
            form_values: form_values,
            row_id: 0
        },
        methods: {
            send_form: function() {
                axios.post(URL_API + 'ideabooks/insert/', $('#add_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data);
                    if ( response.data.status == 1 )
                    {
                        this.row_id = response.data.saved_id;
                        this.clean_form();
                        $('#modal_created').modal();
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            clean_form: function() {
                for ( key in form_values ) {
                    this.form_values[key] = '';
                }
            },
            go_created: function() {
                window.location = URL_APP + 'ideabooks/info/' + this.row_id;
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