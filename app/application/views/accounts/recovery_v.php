<script src='https://www.google.com/recaptcha/api.js'></script>

<div id="recovery_app" class="text-center center_box_450">

    <div v-show="app_status == 'start'">
        <p>
            Write your email address.
             We will send you a link to assign a new password.
        </p>

        

        <form id="app_form" @submit.prevent="send_form" >
            <div class="form-group">
                <label class="sr-only" for="email">E-mail</label>
                <input
                    name="email"
                    type="email"
                    class="form-control"
                    placeholder="E-mail"
                    required
                    value=""
                    title="E-mail"
                    >
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Send</button>
            </div>
        </form>
    </div>

    <div class="alert alert-warning" role="alert" v-show="no_user">
        <i class="fa fa-user-slash"></i>
        <br/>
        There is no registered user with the written email.
    </div>

    <div class="card" style="margin-bottom: 10px;" v-show="app_status == 'sent'">
        <div class="card-body">
            <i class="fa fa-check fa-2x text-success"></i>
            <p>
                We send a link to your email to reset your password.
            <p>
            <p>
                Remember to also check your spam folder.
            </p>
        </div>
    </div>

    <p>You do not have an account? <a href="<?php echo base_url('accounts/signup') ?>">Sign Up</a></p>
</div>

<script>
    var api_url = '<?php echo URL_API ?>';
    new Vue({
        el: '#recovery_app',
        data: {
            app_status: 'start',
            no_user: false
        },
        methods: {
            send_form: function(){
                axios.post(api_url + 'accounts/recover/', $('#app_form').serialize())
                .then(response => {
                    console.log(response.data.status);
                    if ( response.data.status == 1 ) {
                        this.no_user = false;
                        this.app_status = 'sent';
                    } else if ( response.data.status == 2 ) {
                        this.no_user = true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>
