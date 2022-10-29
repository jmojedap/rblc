<?php if ( $row->id == $this->session->userdata('user_id') ) { ?>
    <div id="app_password" class="card center_box_450 mt-2">
        <div class="card-body">
            
            <form accept-charset="utf-8" id="password_form" @submit.prevent="send_form">
                
                <div class="form-group row">
                    <label for="current_password" class="col-md-5 col-form-label text-right">Current passoword</label>
                    <div class="col-md-7">
                        <input
                            id="field-current_password"
                            name="current_password"
                            type="password"
                            class="form-control"
                            placeholder="Current password"
                            title="Current password"
                            required
                            autofocus
                            >
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="password" class="col-md-5 col-form-label text-right">New password</label>
                        <div class="col-md-7">
                            <input
                                id="field-password"
                                name="password"
                                type="password"
                                class="form-control"
                                placeholder="New password"
                                title="At least one number and one lowercase letter, and at least 8 characters"
                                pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                                required
                                >
                        </div>
                    </div>
            
                <div class="form-group row">
                    <label for="passconf" class="col-md-5 col-form-label text-right">Confirm new password</label>
                    <div class="col-md-7">
                        <input
                            id="field-passconf"
                            name="passconf"
                            type="password"
                            class="form-control"
                            placeholder="Confirm new password"
                            title="Confirm new password"
                            pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            required
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7 offset-md-5">
                        <button class="btn btn-main btn-block" type="submit">
                            Change
                        </button>
                    </div>
                </div>
            </form>

            <div class="alert alert-success" role="alert" id="success_alert" style="display: none;">
                <i class="fa fa-check"></i>
                The password was successfully changed
            </div>
            <div class="alert alert-danger" role="alert" id="error_alert" style="display: none;">
                <i class="fa fa-exclamation-triangle"></i>
                <span id="error_text"></span>
            </div>
        </div>
    </div>
<?php } ?>

<script>

// Document ready
//-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#password_form').submit(function(){
            change_password();
            return false;
        });
    });

// Funciones
//-----------------------------------------------------------------------------

    function change_password(){
        $.ajax({        
            type: 'POST',
            url: URL_API + 'accounts/change_password',
            data: $('#password_form').serialize(),
            success: function(response){
                if ( response.status == 1 ) {
                    $('#success_alert').show() 
                    $('#error_alert').hide() 
                } else {
                    $('#error_text').html(response.message) 
                    $('#error_alert').show() 
                }
                clean_form();
            }
        });
    }

    function clean_form()
    {
        $('#field-current_password').val('');
        $('#field-password').val('');
        $('#field-passconf').val('');
    }
</script>