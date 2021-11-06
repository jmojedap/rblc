<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_gender = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Sexo');
    $options_city = $this->App_model->options_place('type_id = 4', 'cr', 'Ciudad');
    $options_id_number_type = $this->Item_model->options('category_id = 53', 'Tipo documento');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="first_name" class="col-md-4 col-form-label text-right">Nombre | Apellidos</label>
                    <div class="col-md-4">
                        <input
                            id="field-first_name"
                            name="first_name"
                            class="form-control"
                            placeholder="Nombres"
                            title="Nombres"
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
                            title="Apellidos"
                            required
                            v-model="form_values.last_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Mostrar como</label>
                    <div class="col-md-8">
                        <input
                            id="field-display_name"
                            name="display_name"
                            class="form-control"
                            placeholder="Nombre para mostrar"
                            title="Nombre para mostrar"
                            required
                            v-model="form_values.display_name"
                            >
                    </div>
                </div>

                <div class="form-group row" id="form-group_username">
                    <label for="username" class="col-md-4 col-form-label text-right">Username</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <!-- /btn-group -->
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

                <div class="form-group row" id="form-group_email">
                    <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico</label>
                    <div class="col-md-8">
                        <input
                            id="field-email"
                            name="email"
                            type="email"
                            class="form-control"
                            v-bind:class="{ 'is-invalid': ! validation.email_valid }"
                            placeholder="Dirección de correo electrónico"
                            title="Dirección de correo electrónico"
                            v-model="form_values.email"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            <span v-show="! validation.email_unique">Ya está registrado para otro usuario</span>
                            <span v-show="! validation.email_gmail">El correo electrónico debe ser de Gmail (@gmail.com)</span>
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city_id" class="col-md-4 col-form-label text-right">Ciudad residencia</label>
                    <div class="col-md-8">
                        <input
                            name="city" id="field-city" type="text" class="form-control"
                            required
                            title="City" placeholder="City"
                            v-model="form_values.city"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="birth_date" class="col-md-4 col-form-label text-right">Fecha de nacimiento</label>
                    <div class="col-md-8">
                        <input
                            id="field-birth_date" name="birth_date" class="form-control bs_datepicker" type="date"
                            v-model="form_values.birth_date"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label text-right">Sexo</label>
                    <div class="col-md-8">
                        <?= form_dropdown('gender', $options_gender, $row->gender, 'class="form-control" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Número celular</label>
                    <div class="col-md-8">
                        <input
                            id="field-phone_number"
                            name="phone_number"
                            class="form-control"
                            placeholder="Número celular"
                            title="Número celular"
                            minlength="10"
                            v-model="form_values.phone_number"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-info w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var form_values = {
        first_name: '<?= $row->first_name ?>',
        last_name: '<?= $row->last_name ?>',
        display_name: '<?= $row->display_name ?>',
        id_number: '<?= $row->id_number ?>',
        id_number_type: '0<?= $row->id_number_type ?>',
        username: '<?= $row->username ?>',
        email: '<?= $row->email ?>',
        city_id: '0<?= $row->city_id ?>',
        birth_date: '<?= $row->birth_date ?>',
        gender: '<?= $row->gender ?>',
        phone_number: '<?= $row->phone_number ?>',
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                username_unique: true,
                email_valid: true,
                email_unique: true,
                email_gmail: true,
                id_number_unique: true
            }
        },
        methods: {
            validate_form: function() {
                axios.post(url_api + 'accounts/validate/', $('#edit_form').serialize())
                .then(response => {
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(url_api + 'accounts/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    if (response.data.status == 1) {
                        this.send_form();
                    } else {
                        toastr['error']('Revise las casillas en rojo');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            send_form: function() {
                axios.post(url_api + 'accounts/update/', $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.message);
                        if (response.data.status == 1)
                        {
                        toastr['success']('Datos actualizados');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('first_name', this.form_values.first_name);
                params.append('last_name', this.form_values.last_name);
                
                axios.post(url_app + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data;
                })
                .catch(function (error) {
                     console.log(error);
                });
            },
        }
    });
</script>