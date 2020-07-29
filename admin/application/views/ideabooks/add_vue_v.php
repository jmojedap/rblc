<script>
    var form_values = {
        post_name: ''
    };
            
    new Vue({
        el: '#add_project',
        data: {
            form_values: form_values,
            row_id: 0
        },
        methods: {
            send_form: function() {
                axios.post(app_url + 'ideabooks/insert/', $('#add_form').serialize())
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
                for ( key in form_values ) { this.form_values[key] = ''; }
            },
            go_created: function() {
                window.location = app_url + 'ideabooks/edit/' + this.row_id;
            }
        }
    });
</script>