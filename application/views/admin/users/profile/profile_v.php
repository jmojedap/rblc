<script>
// Variables
//-----------------------------------------------------------------------------
    user_id = '<?= $row->id ?>';

// Document Ready
//-----------------------------------------------------------------------------

    $(document).ready(function(){
        $('#btn_set_activation_key').click(function(){
            set_activation_key();
        });
    });

// Functions
//-----------------------------------------------------------------------------

    function set_activation_key(){
        $.ajax({        
            type: 'POST',
            url: URL_API + 'users/set_activation_key/' + user_id,
            success: function(response){
                $('#activation_key').html(url_front + 'accounts/recover/' + response);
                toastr['success']('Activation link generated');
            }
        });
    }
</script>

<?php 
    //Imagen
        $att_img['class'] = 'card-img-top';

    $qty_login = $this->Db_model->num_rows('events', "user_id = {$row->id} AND type_id = 101");
?>

<div class="center_box_920">
    <div class="row">
        <div class="col col-md-4">
            <!-- Page Widget -->
            <div class="card text-center">
                <img src="<?= $row->url_image ?>" alt="Imagen del usuario" width="100%" onerror="this.src='<?= URL_IMG ?>users/md_user.png'">
                <div class="card-body">
                    <h4 class="profile-user"><?= $this->Item_model->name(58, $row->role) ?></h4>
    
                    <?php if ($this->session->userdata('role') <= 2) { ?>
                        <a href="<?= URL_ADMIN . "accounts/ml/{$row->id}" ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                            <i class="fa fa-sign-in"></i>
                            Log in
                        </a>
                    <?php } ?>
    
                </div>
                <div class="card-footer">
                    <div class="row no-space">
                        <div class="col-6">
                            <?php if ( strlen($row->birth_date) > 0 ) { ?>
                                <strong class="profile-stat-count"><?= $this->pml->age($row->birth_date); ?></strong>
                                <span>Años</span>
                            <?php } ?>
                        </div>
                        <div class="col-6">
                            <strong class="profile-stat-count"><?= $qty_login ?></strong>
                            <span>Sesiones</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Widget -->
        </div>
        <div class="col col-md-8">
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td class="text-right"><span class="text-muted">First name</span></td>
                        <td><?= $row->first_name ?></td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Last name</span></td>
                        <td><?= $row->last_name ?></td>
                    </tr>
    
                    <tr>
                        <td class="text-right"><span class="text-muted">Username</span></td>
                        <td><?= $row->username ?></td>
                    </tr>
    
                    <tr>
                        <td class="text-right"><span class="text-muted">E-mail</span></td>
                        <td><?= $row->email ?></td>
                    </tr>
    
                    <tr>
                        <td class="text-right"><span class="text-muted">Gender</span></td>
                        <td><?= $this->Item_model->name(59, $row->gender) ?></td>
                    </tr>
    
                    <tr>
                        <td class="text-right"><span class="text-muted">Role</span></td>
                        <td><?= $this->Item_model->name(58, $row->role) ?></td>
                    </tr>
    
                    <tr>
                        <td class="text-right"><span class="text-muted">Updated at</span></td>
                        <td>
                            <?= $this->pml->date_format($row->updated_at, 'Y-m-d h:i') ?> por <?= $this->App_model->name_user($row->updater_id, 'du') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><span class="text-muted">Created at</span></td>
                        <td>
                            <?= $this->pml->date_format($row->created_at, 'Y-m-d H:i') ?> por <?= $this->App_model->name_user($row->creator_id, 'du') ?>
                        </td>
                    </tr>
                    <?php if ( $this->session->userdata('role') <= 2  ) { ?>
                        <tr>
                            <td class="text-right">
                                <button class="btn btn-primary btn-sm" id="btn_set_activation_key">
                                    <i class="fa fa-redo-alt"></i>
                                </button>
                                <span class="text-muted">Activation</span>
                            </td>
                            <td>
                                <span id="activation_key"></span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
