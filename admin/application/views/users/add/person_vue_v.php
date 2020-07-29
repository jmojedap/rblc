<script>
    var random = '2579' + Math.floor(Math.random() * 100000);
    var form_values = {};
    var form_values = {
        role: '023',
        display_name: 'Walter Eliminar',
        email: 'walter' + random + '@eliminar.com',
        password: 'dc' + random,
        country: 'US',
        state_province: 'NJ',
        city: 'Sacramento',
        address: 'Calle 13 10-23',
        address_line_2: 'Floor 3',
        phone_number: '3017399563'
    };
    
    /*var form_values = {
        role: '023',
        display_name: '',
        email: '',
        password: '',
        country: 'US',
        state_province: 'NJ',
        city: '',
        address: '',
        address_line_2: '',
        phone_number: ''
    };*/
            
    new Vue({
        el: '#add_user',
        data: {
            form_values: form_values,
            validation: {
                id_number_unique: true,
                username_unique: true,
                email_unique: true
            },
            row_id: 0
        },
        methods: {
            validate_send: function () {
                axios.post(app_url + 'accounts/validate_signup/', $('#add_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.send_form();
                    } else {
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            send_form: function() {
                axios.post(app_url + 'users/insert/', $('#add_form').serialize())
                .then(response => {
                    console.log('status: ' + response.data.message);
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
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('first_name', this.form_values.first_name);
                params.append('last_name', this.form_values.last_name);
                
                axios.post(app_url + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
            validate_form: function() {
                axios.post(app_url + 'accounts/validate_signup/', $('#add_form').serialize())
                .then(response => {
                    //this.form_valido = response.data.status;
                    this.validation = response.data.validation;
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
                window.location = app_url + 'users/profile/' + this.row_id;
            },
            generate_display_name: function(){
                form_values.display_name = form_values.first_name + ' ' + form_values.last_name;
                if ( form_values.institution_name.length > 0 )
                {
                    form_values.display_name = form_values.institution_name + ' (' + form_values.first_name + ' ' + form_values.last_name + ')';
                }
            },
            empty_generate_display_name: function(){
                if ( form_values.display_name.length == 0 ) { this.generate_display_name(); }
            }
        }
    });
</script>