<?php $this->load->view('assets/recaptcha') ?>

<div id="recovery_app" class="text-center center_box_450">

    <div v-show="app_status == 'start'">
        <p>
            Write your email address.
            We will send you a link to assign a new password.
        </p>        

        <form id="app_form" @submit.prevent="send_form" >
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

            <div class="form-group">
                <label class="sr-only" for="email">E-mail</label>
                <input
                    name="email" type="email" class="form-control" required
                    placeholder="E-mail" title="E-mail" v-model="email"
                    >
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Send</button>
            </div>
        </form>
    </div>

    <div v-show="app_status == 'no_user'">
        <a href="<?= URL_FRONT . "accounts/recovery" ?>" class="btn btn-light mb-2">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <div class="alert alert-warning" role="alert">
            <i class="fa fa-user-slash"></i>
            <br/>
            There is no registered any user with the email: <b>{{ email }}</b>.
        </div>
    </div>


    <div class="card" style="margin-bottom: 10px;" v-show="app_status == 'sent'">
        <div class="card-body">
            <i class="fa fa-check fa-2x text-success"></i>
            <p>
                We send a link to the e-mail address <strong class="text-success">{{ email }}</strong> for reset your password.
            <p>
            <p>Remember to also check the spam folder.</p>
        </div>
    </div>

    <p>You do not have an account? <a href="<?= URL_FRONT . 'accounts/signup' ?>">Sign Up</a></p>
</div>

<script>
    new Vue({
        el: '#recovery_app',
        data: {
            email: '',
            app_status: 'start'
        },
        methods: {
            send_form: function(){
                axios.post(url_api + 'accounts/recovery_email/', $('#app_form').serialize())
                .then(response => {
                    console.log(response.data.status);
                    if ( response.data.status == 1 ) {
                        this.no_user = false;
                        this.app_status = 'sent';
                    } else if ( response.data.status == 0 ) {
                        this.app_status = 'no_user';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>
