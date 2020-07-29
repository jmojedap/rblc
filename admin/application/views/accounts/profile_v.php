<?php 
    //Imagen
        $att_img = $this->App_model->att_img_user($row);
        $att_img['class'] = 'card-img-top';
?>

<div class="row">
    <div class="col col-md-3">
        <!-- Page Widget -->
        <div class="card text-center">
            <img src="<?php echo $att_img['src'] ?>" alt="Imagen del usuario" width="100%">
            <div class="card-body">
                <h4 class="profile-user"><?php echo $this->Item_model->name(58, $row->role) ?></h4>

                <?php if ($this->session->userdata('role') <= 1) { ?>
                    <a href="<?php echo base_url("admin/ml/{$row->id}") ?>" role="button" class="btn btn-primary" title="Ingresar como este usuario">
                        <i class="fa fa-sign-in"></i>
                        Acceder
                    </a>
                <?php } ?>
            </div>
        </div>
        <!-- End Page Widget -->
    </div>
    <div class="col col-md-9">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td class="text-right" width="25%"><span class="text-muted">No. Documento</span></td>
                    <td>
                        <?php echo $row->id_number ?>
                        <?php echo $this->Item_model->name(53, $row->id_number_type); ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Nombre</span></td>
                    <td><?php echo $row->display_name ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Username</span></td>
                    <td><?php echo $row->username ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Correo electrónico</span></td>
                    <td><?php echo $row->email ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Sexo</span></td>
                    <td><?php echo $this->Item_model->name(59, $row->gender) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Rol de usuario</span></td>
                    <td><?php echo $this->Item_model->name(58, $row->role) ?></td>
                </tr>

                <tr>
                    <td class="text-right"><span class="text-muted">Fecha de nacimiento</span></td>
                    <td><?php echo $this->pml->date_format($row->birth_date, 'Y-M-d') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>