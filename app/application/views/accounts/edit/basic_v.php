<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_gender = $this->Item_model->options('category_id = 59 AND cod <= 2', 'Gender');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <h3>Personal Information</h3>
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="validate_send">
                <div class="form-group row">
                    <label for="first_name" class="col-md-4 col-form-label">First name</label>
                    <div class="col-md-8">
                        <input
                            id="field-first_name"
                            name="first_name"
                            class="form-control"
                            placeholder="First name"
                            title="First name"
                            required
                            autofocus
                            v-model="form_values.first_name"
                            >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="last_name" class="col-md-4 col-form-label"> Last name</label>
                    <div class="col-md-8">
                        <input
                            id="field-last_name"
                            name="last_name"
                            class="form-control"
                            placeholder="Last name"
                            title="Last name"
                            required
                            v-model="form_values.last_name"
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
                    <label for="birth_date" class="col-md-4 col-form-label">Birth date</label>
                    <div class="col-md-8">
                        <input
                            id="field-birth_date"
                            name="birth_date"
                            class="form-control bs_datepicker"
                            v-model="form_values.birth_date"
                            type="date"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="gender" class="col-md-4 col-form-label">Gender</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('gender', $options_gender, $row->gender, 'class="form-control" required') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="address" class="col-md-4 col-form-label">Address</label>
                    <div class="col-md-8">
                        <input
                            name="address" id="field-address" type="text" class="form-control"
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
        first_name: '<?php echo $row->first_name ?>',
        last_name: '<?php echo $row->last_name ?>',
        username: '<?php echo $row->username ?>',
        email: '<?php echo $row->email ?>',
        city: '<?php echo $row->city ?>',
        state_province: '<?php echo $row->state_province ?>',
        birth_date: '<?php echo $row->birth_date ?>',
        gender: '0<?php echo $row->gender ?>',
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
                params.append('first_name', this.form_values.first_name);
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