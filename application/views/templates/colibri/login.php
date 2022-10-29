<?php $this->load->view('app/accounts/facebook_login/script_v') ?>

<div id="login_app" class="">
    <h1>Costumer Login</h1>
    <div class="row">
        <div class="col-md-4">
            <h2>Registered Costumers</h2>
            <p>
                If you have an account, sign in with your username or email address  
            </p>
            <form accept-charset="utf-8" method="POST" id="login_form" @submit.prevent="validate_login">
                <div class="form-group">
                    <label class="" for="inputEmail">Username or Email Address <span class="text-highlight">*</span></label>
                    <input
                        class="form-control"
                        name="username"
                        required
                        title="Username o dirección de correo electrónico">
                </div>
                <div class="form-group">
                    <label class="" for="inputPassword">Password <span class="text-highlight">*</span></label>
                    <input type="password" class="form-control" id="inputPassword" name="password" required>
                </div>

                <div class="form-check form-group">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="field-remember_me">
                    <label class="form-check-label" for="field-remember_me">
                        Remember Me
                    </label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-main mr-3">Log in</button>

                    <a href="<?= URL_FRONT . "accounts/recovery" ?>" class="">
                        Forgot You Password?
                    </a>
                </div>
            </form>
            <p>
                <span class="text-highlight">*</span> Required Fields
            </p>
            <div id="messages" v-if="!status">
                <div class="alert alert-warning" v-for="message in messages">
                    {{ message }}
                </div>
            </div>

            <hr>

            <!-- Botón Login con Facebook -->
            <div class="form-group text-center">
                <div class="fb-login-button"
                    data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false" data-width=""
                    scope="public_profile,email" onlogin="checkLoginState();">
                </div>
            </div>
        </div>
        <div class="col-md-6 offset-md-2">
            <h2>New Costumers</h2>
            <p>
                Creating an account has many benefits: create your own
                contents, upload your photos, save your favorites and more.
            </p>
            <p>
                <a class="btn btn-main" href="<?= URL_FRONT . 'accounts/signup' ?>">Create an Account</a>
            </p>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#login_app',
        data: {
            messages: [],
            status: 1
        },
        methods: {
            validate_login: function(){                
                axios.post(URL_API + 'accounts/validate_login', $('#login_form').serialize())
                   .then(response => {
                        if ( response.data.status == 1 )
                        {
                           window.location = URL_APP + 'accounts/logged';
                        } else {
                            this.messages = response.data.messages;
                            this.status = response.data.status;
                        }
                   })
                   .catch(function (error) {
                        console.log(error);
                   });
            }
        }
    });
</script>
