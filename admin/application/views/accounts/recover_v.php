<div id="recover_app">
    <div>
        <h2 class="white">Establecer contraseña</h2>
        <h4 class="white"><?php echo $row->first_name . ' ' . $row->last_name ?></h4>
        <p class="text-muted">
            <i class="fa fa-user"></i>
            <?php echo $row->username ?>
        </p>
        <p>Establece tu nueva contraseña para <?php echo APP_NAME ?></p>
    </div>

    <form id="activation_form" method="post" accept-charset="utf-8" @submit.prevent="send_form">
        <div class="form-group">
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="contrase&ntilde;a"
                required
                autofocus
                title="Debe tener un número y una letra minúscula, y al menos 8 caractéres"
                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                >
        </div>
        <div class="form-group">
            <input
                type="password"
                name="passconf"
                class="form-control"
                placeholder="confirma tu contrase&ntilde;a"
                required
                title="passconf contrase&ntilde;a"
                >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Guardar</button>
        </div>
    </form>

    <div class="alert alert-danger" v-show="!hide_message">
        <i class="fa fa-info-circle"></i>
        Las contraseñas no coinciden
    </div>

</div>

<script>
    new Vue({
        el: '#recover_app',
        data: {
            app_url: '<?= base_url() ?>',
            activation_key: '<?php echo $activation_key ?>',
            hide_message: true
        },
        methods: {
            send_form: function(){
                
                axios.post(this.app_url + 'accounts/reset_password/' + this.activation_key, $('#activation_form').serialize())
                .then(response => {
                    console.log(response.data);
                    this.hide_message = response.data.status;
                    if ( response.data.status == 1 ) {
                        //window.location = this.app_url + 'app/logged';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>