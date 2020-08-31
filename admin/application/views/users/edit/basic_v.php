<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_type = $this->Item_model->options("category_id = 63", 'Account type');
    $options_country = $this->App_model->options_country();
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Name</label>
                    <div class="col-md-8">
                        <input
                            id="field-display_name"
                            name="display_name"
                            class="form-control"
                            placeholder="Name"
                            title="Name of user"
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
                            placeholder="E-mail"
                            title="E-mail"
                            v-model="form_values.email"
                            v-on:change="validate_form"
                            >
                        <span class="invalid-feedback">
                            El correo electrónico ya fue registrado, por favor escriba otro
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="country" class="col-md-4 col-form-label text-right">Country</label>
                    <div class="col-md-8">
                        <?= form_dropdown('country', $options_country, '', 'id="field-country" v-model="form_values.country" class="form-control form-control-chosen" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="state_province" class="col-md-4 col-form-label text-right">State/Province</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-state_province"
                            name="state_province"
                            required
                            class="form-control"
                            placeholder="State or Province"
                            title="State or Province"
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
                            class="form-control"
                            placeholder="City"
                            title="City"
                            v-model="form_values.city"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zip_code" class="col-md-4 col-form-label text-right">Zip code</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-zip_code"
                            name="zip_code"
                            class="form-control"
                            placeholder="Zip code"
                            title="Zip code"
                            v-model="form_values.zip_code"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label text-right">Address</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-address"
                            name="address"
                            class="form-control"
                            placeholder="Address"
                            title="Address"
                            v-model="form_values.address"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="address_line_2" class="col-md-4 col-form-label text-right">Address Line 2</label>
                    <div class="col-md-8">
                        <input
                            type="text"
                            id="field-address_line_2"
                            name="address_line_2"
                            class="form-control"
                            placeholder="Address Line 2"
                            title="Address Line 2"
                            v-model="form_values.address_line_2"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="celular" class="col-md-4 col-form-label text-right">Phone number</label>
                    <div class="col-md-8">
                        <input
                            id="field-phone_number"
                            type="text"
                            name="phone_number"
                            class="form-control"
                            placeholder=""
                            title="Número celular"
                            v-model="form_values.phone_number"
                            >
                    </div>
                </div>

                <?php if ( $this->session->userdata('type') <= 2 ) { ?>
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

                    <div class="form-group row">
                        <label for="code" class="col-md-4 col-form-label text-right">Colibri ID</label>
                        <div class="col-md-8">
                            <input
                                type="text"
                                id="field-code"
                                name="code"
                                required
                                class="form-control"
                                placeholder="Colibri ID"
                                title="Colibri ID"
                                v-model="form_values.code"
                                >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="about" class="col-md-4 col-form-label text-right">About</label>
                        <div class="col-md-8">
                            <textarea
                                name="about"
                                class="form-control"
                                v-model="form_values.about"
                                ></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="type_id" class="col-md-4 col-form-label text-right">Type</label>
                        <div class="col-md-8">
                            <?= form_dropdown('type_id', $options_type, '', 'class="form-control" v-model="form_values.type_id"') ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="admin_notes" class="col-md-4 col-form-label text-right">Administration notes (Private)</label>
                        <div class="col-md-8">
                            <textarea
                                name="admin_notes"
                                class="form-control"
                                title="Notas administrador"
                                v-model="form_values.admin_notes"
                                ></textarea>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    //Loading values in variable
    var form_values = {
            display_name: '<?= $row->display_name ?>',
            country: '<?= $row->country ?>',
            state_province: '<?= $row->state_province ?>',
            city: '<?= $row->city ?>',
            zip_code: '<?= $row->zip_code ?>',
            address: '<?= $row->address ?>',
            address_line_2: '<?= $row->address_line_2 ?>',
            email: '<?= $row->email ?>',
            username: '<?= $row->username ?>',
            code: '<?= $row->code ?>',
            type_id: '0<?= $row->type_id ?>',
            phone_number: '<?= $row->phone_number ?>',
            about: '<?= $row->about ?>',
            admin_notes: '<?= $row->admin_notes ?>',
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>',
            validation: {
                id_number_unique: true,
                username_unique: true,
                email_unique: true
            }
        },
        methods: {
            validate_form: function() {
                axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
                .then(response => {
                    //this.formulario_valido = response.data.status;
                    this.validation = response.data.validation;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validate_send: function () {
                axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
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
                axios.post(url_app + 'users/update/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        console.log('status: ' + response.data.mensaje);
                        if (response.data.status == 1)
                        {
                        toastr['success']('Saved');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            },
            generate_username: function() {
                const params = new URLSearchParams();
                params.append('display_name', this.form_values.display_name);
                params.append('last_name', this.form_values.last_name);
                
                axios.post(url_app + 'users/username/', params)
                .then(response => {
                    this.form_values.username = response.data;
                })
                .catch(function (error) {
                     console.log(error);
                });
            }
        }
    });
</script>