<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <h3>Bussines Information</h3>
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="cat_1" class="col-md-4 col-form-label">Main professional category</label>
                    <div class="col-md-8">
                        <select name="cat_1" v-model="form_values.cat_1" class="form-control" required>
                            <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
                        </select>
                    </div>
                </div>
            
                <div class="form-group row">
                    <label for="display_name" class="col-md-4 col-form-label">Company name</label>
                    <div class="col-md-8">
                        <input
                            id="field-display_name"
                            name="display_name"
                            class="form-control"
                            title="Company name"
                            required
                            autofocus
                            v-model="form_values.display_name"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="about" class="col-md-4 col-form-label">Description</label>
                    <div class="col-md-8">
                        <textarea
                            name="about" id="field-about" class="form-control" required rows="5"
                            v-model="form_values.about"
                        ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="phone_number" class="col-md-4 col-form-label">Phone number</label>
                    <div class="col-md-8">
                        <input
                            name="phone_number" id="field-phone_number" type="text" class="form-control" required
                            v-model="form_values.phone_number"
                        >
                    </div>
                </div>

                <div class="form-group row" id="form-group_email">
                    <label for="email" class="col-md-4 col-form-label">E-mail</label>
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
                            <span v-show="! validation.email_unique">An account already exists with this email</span>
                        </span>
                    </div>
                </div>

                <div class="form-group row" id="form-group_username">
                    <label for="username" class="col-md-4 col-form-label">Username</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <!-- /btn-group -->
                            <input
                                id="field-username"
                                name="username"
                                class="form-control"
                                v-bind:class="{ 'is-invalid': ! validation.username_unique }"
                                placeholder="username"
                                title="It can contain letters and numbers, between 6 and 25 characters, must not contain spaces or special characters"
                                required
                                pattern="^[A-Za-z0-9_]{6,25}$"
                                v-model="form_values.username"
                                v-on:change="validate_form"
                                >
                            <div class="input-group-append">
                                <button type="button" class="btn btn-main" title="Generate username" v-on:click="generate_username">
                                    <i class="fa fa-magic"></i>
                                </button>
                            </div>
                            
                            <span class="invalid-feedback">
                                The written username is not available, please choose another
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label">Address</label>
                    <div class="col-md-8">
                        <input
                            name="address" id="field-address" type="text" class="form-control"
                            required
                            title="Address" placeholder="Address"
                            v-model="form_values.address"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address_line_2" class="col-md-4 col-form-label">Address line 2</label>
                    <div class="col-md-8">
                        <input
                            name="address_line_2" id="field-address_line_2" type="text" class="form-control"
                            required
                            title="Address line 2" placeholder="Address line 2"
                            v-model="form_values.address_line_2"
                        >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="city_id" class="col-md-4 col-form-label">City</label>
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
                    <label for="city_id" class="col-md-4 col-form-label">State</label>
                    <div class="col-md-8">
                        <input
                            name="state_province" id="field-state_province" type="text" class="form-control"
                            required
                            title="State" placeholder="State"
                            v-model="form_values.state_province"
                        >
                    </div>
                </div>

                

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-main btn-block" type="submit">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var form_values = {
        display_name: '<?php echo $row->display_name ?>',
        about: '<?php echo $row->about ?>',
        phone_number: '<?php echo $row->phone_number ?>',
        username: '<?php echo $row->username ?>',
        email: '<?php echo $row->email ?>',
        city: '<?php echo $row->city ?>',
        state_province: '<?php echo $row->state_province ?>',
        birth_date: '<?php echo $row->birth_date ?>',
        gender: '0<?php echo $row->gender ?>',
        cat_1: '0<?php echo $row->cat_1 ?>',
        address: '<?php echo $row->address ?>',
        address_line_2: '<?php echo $row->address_line_2 ?>'
    };
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?php echo $row->id ?>',
            validation: {
                username_unique: true,
                email_unique: true,
            },
            options_cat_1: <?= json_encode($options_cat_1) ?>
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
                        toastr['error']('Please check the red fields');
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
                        toastr['success']('Updated');
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
                
                axios.post(url_api + 'users/username/', params)
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