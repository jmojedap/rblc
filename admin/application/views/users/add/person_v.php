<?php $this->load->view('assets/bs4_chosen'); ?>

<?php
    $options_country = $this->App_model->options_country();
    $options_role = $this->Item_model->options("category_id = 58 AND cod >= {$this->session->userdata('role')}", 'Select the role');
    
?>

<div id="add_user">
    <form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
        <input type="hidden" name="role" value="23">

        <div class="form-group row">
            <label for="role" class="col-md-4 col-form-label text-right">Role</label>
            <div class="col-md-8">
                <?php echo form_dropdown('role', $options_role, '', 'class="form-control" v-model="form_values.role"') ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="display_name" class="col-md-4 col-form-label text-right">Name</label>
            <div class="col-md-8">
                <input
                    id="field-display_name"
                    name="display_name"
                    class="form-control"
                    required
                    autofocus
                    v-model="form_values.display_name"
                    >
            </div>
        </div>
        
        <div class="form-group row" id="form-group_email">
            <label for="email" class="col-md-4 col-form-label text-right">E-mail</label>
            <div class="col-md-8">
                <input
                    id="field-email"
                    name="email"
                    type="email"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': ! validation.email_unique }"
                    v-model="form_values.email"
                    v-on:change="validate_form"
                    >
                <span class="invalid-feedback">
                    El correo electrónico ya fue registrado, por favor escriba otro
                </span>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-right">User password</label>
            <div class="col-md-8">
                <input
                    id="field-password"
                    name="password"
                    class="form-control"
                    placeholder="Chose a password for the new user"
                    title="At least eight characters with letters and numbers"
                    required
                    v-model="form_values.password"
                    pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                    >
            </div>
        </div>

        <div class="form-group row">
            <label for="country" class="col-md-4 col-form-label text-right">Country</label>
            <div class="col-md-8">
                <?php echo form_dropdown('country', $options_country, '', 'id="field-country" class="form-control form-control-chosen" required v-model="form_values.country"') ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="state_province" class="col-md-4 col-form-label text-right">State/Province</label>
            <div class="col-md-8">
                <input
                    name="state_province" id="field-state_province" type="text" class="form-control"
                    required
                    title="" placeholder=""
                    v-model="form_values.state_province"
                >
            </div>
        </div>

        <div class="form-group row">
            <label for="city" class="col-md-4 col-form-label text-right">City</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-city"
                    name="city"
                    required
                    class="form-control"
                    v-model="form_values.city"
                    >
            </div>
        </div>

        

        <div class="form-group row">
            <label for="address" class="col-md-4 col-form-label text-right">Address</label>
            <div class="col-md-8">
                <textarea
                    id="field-address"
                    name="address"
                    required
                    class="form-control"
                    placeholder="Address"
                    title="Address"
                    v-model="form_values.address"
                    rows="2"
                    ></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="address_line_2" class="col-md-4 col-form-label text-right">Address line 2</label>
            <div class="col-md-8">
                <input
                    name="address_line_2" id="field-address_line_2" type="text" class="form-control"
                    required
                    v-model="form_values.address_line_2"
                >
            </div>
        </div>

        <div class="form-group row">
            <label for="celular" class="col-md-4 col-form-label text-right">Phone number</label>
            <div class="col-md-8">
                <input
                    id="field-phone_number"
                    type="number"
                    name="phone_number"
                    class="form-control"
                    v-model="form_values.phone_number"
                    required
                    >
            </div>
        </div>
        
        <div class="form-group row">
            <div class="offset-4 col-md-8">
                <button class="btn btn-success w120p" type="submit">Save</button>
            </div>
        </div>
    </form>
    
    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Usuario creado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Usuario creado correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="go_created">
                        Abrir usuario
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clean_form" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php
$this->load->view('users/add/person_vue_v');