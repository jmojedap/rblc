<?php $this->load->view('assets/bs4_chosen') ?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="role" class="col-md-4 col-form-label text-right">Role</label>
                    <div class="col-md-8">
                        <?= form_dropdown('role', $options_role, '', 'class="form-control" v-model="form_values.role"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label text-right">Name <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input
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
                    <label for="country" class="col-md-4 col-form-label text-right">Country <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <?= form_dropdown('country', $options_country, '', 'id="field-country" v-model="form_values.country" class="form-control form-control-chosen"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="state_province" class="col-md-4 col-form-label text-right">State/Province <span class="text-danger">*</span></label>
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
                        <label for="username" class="col-md-4 col-form-label text-right">Username <span class="text-danger">*</span></label>
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
                        <label for="cat_1" class="col-md-4 col-form-label text-right">Category</label>
                        <div class="col-md-8">
                            <select name="cat_1" v-model="form_values.cat_1" class="form-control" v-on:change="unset_cat_2">
                                <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cat_2" class="col-md-4 col-form-label text-right">Subcategory</label>
                        <div class="col-md-8">
                            <select name="cat_2" v-model="form_values.cat_2" class="form-control" required>
                                <option value=""> :: Select Subcategory :: </option>
                                <option v-for="option_cat_2 in options_cat_2" v-bind:value="`0` + option_cat_2.cod">{{ option_cat_2.item_name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="score_1" class="col-md-4 col-form-label text-right">Score to order (followers)</label>
                        <div class="col-md-8">
                            <input
                                name="score_1" type="number" class="form-control" min="0"
                                required
                                title="Number of followers"
                                v-model="form_values.score_1"
                            >
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
// Variables
//-----------------------------------------------------------------------------
//Loading values in variable
var form_values = {
    role: '0<?= $row->role ?>',
    display_name: "<?= $row->display_name ?>",
    country: '<?= $row->country ?>',
    state_province: '<?= $row->state_province ?>',
    city: "<?= $row->city ?>",
    zip_code: '<?= $row->zip_code ?>',
    address: "<?= $row->address ?>",
    address_line_2: "<?= $row->address_line_2 ?>",
    email: '<?= $row->email ?>',
    username: '<?= $row->username ?>',
    code: '<?= $row->code ?>',
    type_id: '0<?= $row->type_id ?>',
    cat_1: '0<?= $row->cat_1 ?>',
    cat_2: '0<?= $row->cat_2 ?>',
    score_1: '<?= $row->score_1 ?>',
    phone_number: '<?= $row->phone_number ?>',
    about: "<?= $row->about ?>",
    admin_notes: '<?= $row->admin_notes ?>',
};

var items_cat_2 = <?= json_encode($items_cat_2->result()) ?>;

// VueApp
//-----------------------------------------------------------------------------
var app_edit = new Vue({
    el: '#app_edit',
    data: {
        form_values: form_values,
        row_id: '<?= $row->id ?>',
        validation: {
            id_number_unique: true,
            username_unique: true,
            email_unique: true
        },
        options_cat_1: <?= json_encode($options_cat_1) ?>,
    },
    methods: {
        validate_form: function() {
            axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
            .then(response => {
                this.validation = response.data.validation
            })
            .catch(function (error) { console.log(error)} )
        },
        validate_send: function () {
            axios.post(url_app + 'users/validate/' + this.row_id, $('#edit_form').serialize())
            .then(response => {
                if (response.data.status == 1) {
                    this.send_form()
                } else {
                    toastr['error']('Revise las casillas en rojo')
                }
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function() {
            axios.post(url_app + 'users/update/' + this.row_id, $('#edit_form').serialize())
            .then(response => {
                console.log('status: ' + response.data.mensaje)
                if (response.data.status == 1)
                {
                    toastr['success']('Saved')
                }
            })
            .catch(function (error) {console.log(error)})
        },
        generate_username: function() {
            const params = new URLSearchParams();
            params.append('display_name', this.form_values.display_name);
            params.append('last_name', this.form_values.last_name);
            
            axios.post(url_app + 'users/username/', params)
            .then(response => {
                this.form_values.username = response.data;
            })
            .catch(function (error) { console.log(error) })
        },
        unset_cat_2: function(){
            this.form_values.cat_2 = ""
        },
    },
    computed: {
        //Establecer opciones dependiendo del valor de cat_1
        options_cat_2: function(){
            var cat_1_int = parseInt(this.form_values.cat_1)
            return items_cat_2.filter(item => item.parent_id == cat_1_int)
        },
    },
});
</script>