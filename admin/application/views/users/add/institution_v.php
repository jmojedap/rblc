<?php $this->load->view('assets/bs4_chosen'); ?>

<?php 
    $gender_options = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
    $id_number_type_options = $this->Item_model->options('category_id = 53 AND filters LIKE "%organization%"', 'Tipo documento');
    $city_options = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
?>

<div id="add_user">
    <form id="add_form" accept-charset="utf-8" @submit.prevent="validate_send">
        <input type="hidden" name="role" value="23">

        <div class="form-group row">
            <label for="institution_name" class="col-md-4 col-form-label text-right">Nombre institución</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-institution_name"
                    name="institution_name"
                    required
                    class="form-control"
                    placeholder="Nombre institución"
                    title="Nombre institución"
                    v-model="form_values.institution_name"
                    >
            </div>
        </div>

        <div class="form-group row" id="form-group_id_number">
            <label for="id_number" class="col-md-4 col-form-label text-right">No. Documento</label>
            <div class="col-md-4">
                <input
                    id="field-id_number"
                    name="id_number"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': ! validation.id_number_unique }"
                    placeholder="Número de documento"
                    title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                    required
                    pattern=".{5,}[0-9]"
                    v-model="form_values.id_number"
                    v-on:change="validate_form"
                    >
                <span class="invalid-feedback">
                    El número de documento escrito ya fue registrado para otro usuario
                </span>
            </div>
            <div class="col-md-4">
                <?= form_dropdown('id_number_type', $id_number_type_options, '', 'class="form-control" required v-model="form_values.id_number_type"') ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="first_name" class="col-md-4 col-form-label text-right">Persona Contacto</label>
            <div class="col-md-4">
                <input
                    id="field-first_name"
                    name="first_name"
                    class="form-control"
                    placeholder="Nombres"
                    title="Nombres del usuario"
                    required
                    autofocus
                    v-model="form_values.first_name"
                    >
            </div>
            <div class="col-md-4">
                <input
                    id="field-last_name"
                    name="last_name"
                    class="form-control"
                    placeholder="Apellidos"
                    title="Apellidos del usuario"
                    required
                    accept=""v-model="form_values.last_name"
                    >
            </div>
        </div>

        <div class="form-group row">
            <label for="gender" class="col-md-4 col-form-label text-right">Sexo</label>
            <div class="col-md-8">
                <?= form_dropdown('gender', $gender_options, '', 'class="form-control" required v-model="form_values.gender"') ?>
            </div>
        </div>
        

        <div class="form-group row">
            <label for="display_name" class="col-md-4 col-form-label text-right">Mostrar como</label>
            <div class="col-md-8">
                <div class="input-group">
                    <input
                        type="text"
                        id="field-display_name"
                        name="display_name"
                        required
                        class="form-control"
                        placeholder="Ej. Juan Pérez"
                        title="Nombre mostrar"
                        v-model="form_values.display_name"
                        v-on:focus="empty_generate_display_name"
                        >
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" title="Generar Mostrar Como" v-on:click="generate_display_name">
                            <i class="fa fa-magic"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group row" id="form-group_email">
            <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
            <div class="col-md-8">
                <input
                    id="field-email"
                    name="email"
                    type="email"
                    class="form-control"
                    v-bind:class="{ 'is-invalid': ! validation.email_unique }"
                    placeholder="Dirección de correo electrónico"
                    title="Dirección de correo electrónico"
                    v-model="form_values.email"
                    v-on:change="validate_form"
                    >
                <span class="invalid-feedback">
                    El correo electrónico ya fue registrado, por favor escriba otro
                </span>
            </div>
        </div>
        
        <div class="form-group row" id="form-group_username">
            <label for="username" class="col-md-4 col-form-label text-right">Username</label>
            <div class="col-md-8">
                <div class="input-group">
                    <input
                        id="field-username"
                        name="username"
                        class="form-control"
                        v-bind:class="{ 'is-invalid': ! validation.username_unique }"
                        placeholder="username"
                        title="Puede contener letras y números, entre 6 y 25 caractéres, no debe contener espacios ni caracteres especiales"
                        required
                        pattern="^[A-Za-z0-9_]{6,25}$"
                        v-model="form_values.username"
                        v-on:change="validate_form"
                        >
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" title="Generar username" v-on:click="generate_username">
                            <i class="fa fa-magic"></i>
                        </button>
                    </div>
                    <span class="invalid-feedback">
                        El username escrito no está disponible, por favor elija otro
                    </span>
                    
                </div>
            </div>
        </div>
        
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-right">Contraseña</label>
            <div class="col-md-8">
                <input
                    id="field-password"
                    name="password"
                    class="form-control"
                    placeholder="Escriba la contraseña del usuario"
                    title="Debe tener al menos un número y una letra minúscula, y como mínimo 8 caractéres"
                    required
                    v-model="form_values.password"
                    pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                    >
            </div>
        </div>

        <div class="form-group row">
            <label for="city_id" class="col-md-4 col-form-label text-right">Ciudad ubicación</label>
            <div class="col-md-8">
                <?= form_dropdown('city_id', $city_options, '', 'id="field-city_id" class="form-control form-control-chosen" required v-model="form_values.city_id"') ?>
            </div>
        </div>

        <div class="form-group row">
            <label for="city_name" class="col-md-4 col-form-label text-right">Vereda/Corregimiento</label>
            <div class="col-md-8">
                <input
                    type="text"
                    id="field-city_name"
                    name="city_name"
                    required
                    class="form-control"
                    placeholder="Vereda/Corregimiento"
                    title="Vereda/Corregimiento"
                    v-model="form_values.city_name"
                    >
            </div>
        </div>

        <div class="form-group row">
            <label for="address" class="col-md-4 col-form-label text-right">Dirección</label>
            <div class="col-md-8">
                <textarea
                    id="field-address"
                    name="address"
                    required
                    class="form-control"
                    placeholder="Dirección"
                    title="Dirección"
                    rows="2"
                    v-model="form_values.address"
                    ></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="celular" class="col-md-4 col-form-label text-right">Teléfono / Celular</label>
            <div class="col-md-8">
                <input
                    id="field-phone_number"
                    type="number"
                    name="phone_number"
                    class="form-control"
                    placeholder="Número celular"
                    title="Número celular"
                    v-model="form_values.phone_number"
                    required
                    >
            </div>
        </div>
        
        
        
        
        <div class="form-group row">
            <div class="offset-4 col-md-8">
                <button class="btn btn-success w120p" type="submit">
                    Crear
                </button>
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
$this->load->view('users/add/institution_vue_v');