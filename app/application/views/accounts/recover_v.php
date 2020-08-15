<div id="recover_app" class="center_box_450">
    <div class="text-center">
        <h2 class="white">Reset password</h2>
        <h4 class="white"><?php echo $row->first_name . ' ' . $row->last_name ?></h4>
        <p class="text-muted">
            <i class="fa fa-user"></i>
            <?php echo $row->username ?>
        </p>
        <p>Set your <strong>new password</strong> in <?php echo APP_NAME ?></p>
    </div>

    <form id="recover_form" method="post" accept-charset="utf-8" @submit.prevent="send_form">
        <div class="form-group">
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="password"
                required
                autofocus
                title="Must have a lowercase number and letter, and at least 8 characters"
                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                >
        </div>
        <div class="form-group">
            <input
                type="password"
                name="passconf"
                class="form-control"
                placeholder="confirm your password"
                required
                title="password confirmation"
                >
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-main btn-block">Save</button>
        </div>
    </form>

    <div class="alert alert-danger" v-show="!hide_message">
        <i class="fa fa-info-circle"></i>
        Passwords do not match
    </div>

</div>

<script>
    new Vue({
        el: '#recover_app',
        data: {
            activation_key: '<?php echo $activation_key ?>',
            hide_message: true
        },
        methods: {
            send_form: function(){
                
                axios.post(url_api + 'accounts/reset_password/' + this.activation_key, $('#recover_form').serialize())
                .then(response => {
                    this.hide_message = response.data.status;
                    if ( response.data.status == 1 ) {
                        toastr['success']('Your password was successfully changed');
                        setTimeout(function(){ window.location = url_app + 'app/logged'; }, 3000);
                        
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        }
    });
</script>