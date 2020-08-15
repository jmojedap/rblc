<script>
    new Vue({
        el: '#signup_app',
        data: {
            step: 1,
            role_type: 'homeowner',
            email: '',
            first_name: '',
            last_name: '',
            email_is_unique: -1,
            pw_match: -1,
            pw: '',
            pc: '',
            validated: -1
        },
        methods: {
            register: function(){
                if ( this.validated ) {
                    axios.post(url_api + 'accounts/register/' + this.role_type, $('#signup_form').serialize())
                    .then(response => {
                        console.log(response.data.message);
                        if ( response.data.status == 1 ) {
                            window.location = url_app + 'accounts/registered/' + response.data.saved_id;
                        } else {
                            this.recaptcha_message = response.data.recaptcha_message;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                } else {
                    console.log('El formulario no ha sido validado');
                }
            },
            check_email: function(){                
                axios.post(url_api + 'accounts/check_email/', $('#email_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        this.email_is_unique = 0;
                    } else {
                        this.email_is_unique = 1;
                        this.validated = 1;
                        this.step = 3;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            set_step: function(step){
                this.step = step;
                if ( step == 2 ) { this.email_is_unique = -1 }
            },
            validate: function(){
                this.validated = 1;
                //Unique email
                if ( this.email_is_unique != 1 ) { this.validated = 0; }   
                //Password Match
                if ( this.pw_match != 1 ) { this.validated = 0; }
            },
            validate_pc_match: function(){
                //Match Password
                if ( this.pw == this.pc )
                {
                    this.pw_match = 1;
                } else {
                    this.pw_match = 0;
                }
                this.validate();
            },
            validate_pw_match: function(){
                if ( this.pc.length > 0 )
                {
                    //Match Password
                    if ( this.pw == this.pc )
                    {
                        this.pw_match = 1;
                    } else {
                        this.pw_match = 0;
                    }
                }
                this.validate();
            },
            set_role_type: function(role_type){
                signup_role_type = role_type;
                console.log('vue signup_role_type', signup_role_type);
                this.role_type = role_type;
                this.step = 2;
            }
        }
    });
</script>