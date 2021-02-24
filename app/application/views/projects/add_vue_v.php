<script>
    var form_values = {
        post_name: '',
        integer_1: ''
    };
            
    new Vue({
        el: '#add_project',
        data: {
            form_values: form_values,
            row_id: 0,
            options_project_type: <?= json_encode($options_project_type) ?>
        },
        methods: {
            send_form: function() {
                axios.post(url_api + 'projects/insert/', $('#add_form').serialize())
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
                window.location = url_app + 'projects/edit/' + this.row_id;
            }
        }
    });
</script>