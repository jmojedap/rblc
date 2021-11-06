

<div id="login_app" class="text-center">
    <p>
        Escribe tu correo electrónico
        <br/>
        y tu contraseña para ingresar.
    </p>

    <form accept-charset="utf-8" method="POST" id="login_form" @submit.prevent="validate_login">
        <div class="form-group">
            <label class="sr-only" for="inputEmail">Email</label>
            <input
                class="form-control form-control-lg"
                name="username"
                placeholder="e-mail"
                required
                title="Username o dirección de correo electrónico">
        </div>
        <div class="form-group">
            <label class="sr-only" for="inputPassword">Contraseña</label>
            <input type="password" class="form-control form-control-lg" id="inputPassword" name="password" placeholder="password" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block btn-lg">LOG IN</button>
        </div>
        
        <div class="form-group clearfix">
            <a href="<?= URL_FRONT . 'accounts/recovery' ?>">¿Olvidaste los datos de tu cuenta?</a>
        </div>
        
        
    </form>

    <br/>
    
    <div id="messages" v-if="!status">
        <div class="alert alert-warning" v-for="message in messages">
            {{ message }}
        </div>
    </div>

    <p>¿No tienes una cuenta? <a href="<?= URL_FRONT . 'accounts/signup' ?>">Regístrate</a></p>
</div>

<script>
    var form_destination = url_app + 'accounts/validate_login';
    new Vue({
        el: '#login_app',
        data: {
            messages: [],
            status: 1
        },
        methods: {
            validate_login: function(){                
                axios.post(form_destination, $('#login_form').serialize())
                   .then(response => {
                        if ( response.data.status == 1 )
                        {
                           window.location = url_app + 'accounts/logged';
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
