<?php $this->load->view('assets/recaptcha') ?>

<style>
    .bg-main {
        background-color: #F37062;
    }

    .role_type{
        background-color: white;
        padding: 30px;
        margin: 30px;
        height: 420px;
        cursor: pointer;
    }

    .type_role_selector {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    .bg-main h1 { color:white; }
    .bg-main h2 { color:#666; }
</style>

<div id="signup_app" class="text-center">
    <div class="type_role_selector bg-main mb-2" v-show="step == 1">
        <h1>Welcome to Colibri!</h1>
        <h2>Which describes you best?</h2>
        <div class="row">
            <div class="col-md-4 offset-md-2">
                <div class="role_type" v-on:click="set_role_type('professional')">
                    <img src="<?= URL_IMG ?>front/icon_professional.png" alt="Proffesional icon" class="w100pc">
                    <h3>Professional</h3>
                    <p>I ofered projects, services or products the people can share</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role_type" v-on:click="set_role_type('homeowner')">
                    <img src="<?= URL_IMG ?>front/icon_homeowner.png" alt="Homeowner icon" class="w100pc">
                    <h3>Homeowner</h3>
                    <p>Interested in look for products, projects and profesionales to get ideas and contacts.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 center_box_450" v-show="step == 2">
        <h3>You are a {{ role_type }}</h3>
        <h4>Write your E-mail</h4>
        <form accept-charset="utf-8" method="POST" id="email_form" @submit.prevent="check_email">
            <div class="form-group">
                <label class="sr-only" for="email">E-mail</label>
                <input
                    name="email"
                    type="email"
                    class="form-control"
                    placeholder="Your E-mail"
                    autofocus
                    required
                    value=""
                    title="E-mail"
                    v-model="email"
                    v-bind:class="{'is-invalid': email_is_unique == 0, 'is-valid': email_is_unique == 1}"
                    >
                <div class="invalid-feedback" v-show="email_is_unique == 0">
                    An account already exists with this email
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-main btn-block">Next</button>
            </div>


            <a href="<?php echo base_url("accounts/login") ?>" class="btn btn-block btn-success mb-3" v-show="email_is_unique == 0">
                Log in
            </a>

        </form>
    </div>


    <?php $this->load->view('accounts/start_alerts_v') ?>

    <form id="signup_form" @submit.prevent="register" v-show="step == 3" class="center_box_450 mt-3">
        <!-- Campo para validación Google ReCaptcha V3 -->
        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        <input type="hidden" name="email" v-model="email">

        <h3>
            You are a {{ role_type }}
        </h3>
        <h4 v-on:click="restart_email">{{ email }}</h4>

        <div class="form-group">
            <label class="sr-only" for="first_name">First name</label>
            <input
                ref="field_first_name"
                class="form-control"
                name="first_name"
                placeholder="First name"
                required
                title="Debe tener al menos tres caracteres"
                minlength="3"
                v-model="first_name"
                >
            
        </div>
        
        <div class="form-group">
            <label class="sr-only" for="first_name">Last name</label>
            <input
                class="form-control"
                name="last_name"
                placeholder="Last name"
                required
                title="Debe tener al menos tres caracteres"
                minlength="3"
                v-model="last_name"
                >
        </div>

        <div class="form-group">
            <label class="sr-only" for="password">Password</label>
            <input
                id="field-password"
                name="new_password"
                type="password"
                class="form-control"
                placeholder="Write your password"
                required
                v-model="pw"
                v-on:change="validate_pw_match"
                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                title="8 caractéres o más, al menos un número y una letra minúscula"
                >
        </div>

        <div class="input-group mb-3">
            <label class="sr-only" for="password">Confirm your password</label>
            <input
                id="field-passconf"
                name="passconf"
                type="password"
                class="form-control"
                placeholder="Repeat your password"
                required
                title="Repeat your password"
                v-model="pc"
                v-on:change="validate_pc_match"
                v-bind:class="{'is-invalid': pw_match == 0}"
                >
            <div class="invalid-feedback" v-show="pw_match == 0">
                Please check, it doesn't match.
            </div>
        </div>        
        
        <div class="form-group">
            <button type="submit" class="btn btn-main btn-block">Create</button>
        </div>
        
        <div class="form-group d-none">
            <a href="<?php //echo $g_client->createAuthUrl(); ?>" class="btn btn-light btn-block">
                <img src="<?php //echo URL_IMG . 'app/google.png'?>" style="width: 20px">
                Sign Up with Google Account
            </a>
        </div>
    </form>

    <p>Do you already have an account? <a href="<?php echo base_url('accounts/login') ?>">Log in</a></p>
</div>

<script>
    new Vue({
        el: '#signup_app',
        data: {
            step: 1,
            role_type: '',
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
                            window.location = app_url + 'accounts/registered/' + response.data.saved_id;
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
            restart_email: function(){
                this.step = 2;
                this.email_is_unique = -1;  
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
                this.role_type = role_type;
                this.step = 2;
            },

        }
    });
</script>