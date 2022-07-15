<?php $this->load->view('assets/recaptcha') ?>
<?php $this->load->view('app/accounts/facebook_login/script_v') ?>

<style>
    .mt-lg-75{
        margin-top: 75px;
    }

    .ready_data {
        font-size: 1.1em;
        color: green;
        padding: 5px 10px;
        text-align: left;
        cursor: pointer;
    }
</style>

<div id="signup_app" class="text-center">



    <div class="type_role_selector bg-main mb-2" v-show="step == 1">
        <h1>Welcome to Colibri!</h1>
        <h2>Which describes you best?</h2>
        <div class="row">
            <div class="col-md-4 offset-md-2">
                <div class="role_type" v-on:click="set_role_type('professional')">
                    <img src="<?= URL_IMG ?>front/icon_professional.png" alt="Proffesional icon" class="w100pc">
                    <h3>Professional</h3>
                    <p>I ofered projects, services or products the people can share</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role_type" v-on:click="set_role_type('homeowner')">
                    <img src="<?= URL_IMG ?>front/icon_homeowner.png" alt="Homeowner icon" class="w100pc">
                    <h3>Homeowner</h3>
                    <p>Interested in look for products, projects and profesionales to get ideas and contacts.</p>
                </div>
            </div>
        </div>
    </div>

    <div v-show="step > 1" class="center_box_320 mt-3">
        <!-- <div class="text-left mb-2">
            <button class="btn btn-light mb-2 w120p" v-on:click="set_step(step-1)"><i class="fa fa-arrow-left"></i> Back</button>
        </div> -->
        <div class="text-center" v-show="loading">
            <i class="fa fa-spin fa-spinner fa-3x text-muted"></i>
        </div>
        <div class="ready_data" v-on:click="set_step(1)">
            <i class="fa fa-arrow-left mr-2"></i> {{ role_type }}
        </div>
        <div class="ready_data" v-show="step > 2" v-on:click="set_step(2)">
            <i class="fa fa-arrow-left mr-2"></i> {{ email }}
        </div>
        <div class="ready_data" v-show="step > 3 && role_type == 'professional'" v-on:click="set_step(3)">
            <i class="fa fa-arrow-left mr-2"></i> {{ options_cat_1[cat_1] }} / {{ cat_2_label }}
        </div>
    </div>

    <div class="center_box_320 mt-3" v-show="step == 2">
        <h4>Your E-mail</h4>
        <form accept-charset="utf-8" method="POST" id="email_form" @submit.prevent="check_email">
            <div class="form-group">
                <label class="sr-only" for="email">E-mail</label>
                <input
                    name="email"
                    type="email"
                    class="form-control"
                    placeholder="Your E-mail"
                    autofocus
                    required
                    value=""
                    title="E-mail"
                    v-model="email"
                    v-bind:class="{'is-invalid': email_is_unique == 0, 'is-valid': email_is_unique == 1}"
                    >
                <div class="invalid-feedback" v-show="email_is_unique == 0">
                    An account already exists with this email
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-main btn-block">Next</button>
            </div>

            <!-- Botón Login con Facebook -->
            <!-- <div class="form-group">
                <div class="fb-login-button"
                    data-size="large" data-button-type="login_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false" data-width=""
                    scope="public_profile,email" onlogin="checkLoginState();">
                </div>
            </div> -->

            <a href="<?= URL_FRONT . "accounts/login" ?>" class="btn btn-block btn-success mb-3" v-show="email_is_unique == 0">
                Log in
            </a>

        </form>
    </div>

    <!-- CATEGORY AND SUBCATEGORY -->
    <div class="center_box_320 my-3" v-show="step == 3">
        <h4>What do you do?</h4>
        <div class="mb-2">
            <select name="cat_1" v-model="cat_1" class="form-control" v-on:change="unset_cat_2">
                <option value=""> :: Select category :: </option>
                <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
            </select>
        </div>
        <div class="mb-2">
            <select name="cat_2" v-model="cat_2" class="form-control">
                <option value=""> :: Select subcategory :: </option>
                <option v-for="option_cat_2 in options_cat_2" v-bind:value="option_cat_2.cod">{{ option_cat_2.item_name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <button type="button" class="btn btn-main btn-block"
                v-on:click="set_step(4)" v-bind:disabled="cat_2 == ''"
                >
                Next
            </button>
        </div>
    </div>
    
    <form id="signup_form" @submit.prevent="register" v-show="step == 4" class="center_box_320 mt-3">
        <fieldset v-bind:disabled="loading">
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

            <!-- E-mail escrito en el paso anterior -->
            <input type="hidden" name="email" v-model="email">

            <input type="hidden" name="cat_1" v-model="cat_1">
            <input type="hidden" name="cat_2" v-model="cat_2">
            

            <div class="form-group">
                <label class="sr-only" for="first_name">First name</label>
                <input
                    ref="field_first_name"
                    class="form-control"
                    name="first_name"
                    placeholder="First name"
                    required
                    title="Debe tener al menos tres caracteres"
                    minlength="3"
                    v-model="first_name"
                    >
            </div>
            
            <div class="form-group">
                <label class="sr-only" for="first_name">Last name</label>
                <input
                    class="form-control"
                    name="last_name"
                    placeholder="Last name"
                    required
                    title="Debe tener al menos tres caracteres"
                    minlength="3"
                    v-model="last_name"
                    >
            </div>

            <div class="form-group">
                <label class="sr-only" for="password">Password</label>
                <input
                    id="field-password"
                    name="new_password"
                    type="password"
                    class="form-control"
                    placeholder="Write your password"
                    required
                    v-model="pw"
                    v-on:change="validate_passwords_match"
                    pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                    title="8 caractéres o más, al menos un número y una letra minúscula"
                    >
            </div>

            <div class="input-group mb-3">
                <label class="sr-only" for="password">Confirm your password</label>
                <input
                    id="field-passconf"
                    name="passconf"
                    type="password"
                    class="form-control"
                    placeholder="Confirm your password"
                    required
                    title="Confirm your password"
                    v-model="pc"
                    v-on:change="validate_passwords_match"
                    v-bind:class="{'is-invalid': passwords_match == 0}"
                    >
                <div class="invalid-feedback" v-show="passwords_match == 0">
                    Please check, it doesn't match.
                </div>
            </div>        
            
            <div class="form-group">
                <button type="submit" class="btn btn-main btn-block">
                    CREATE
                </button>
            </div>
        <fieldset>
    </form>

    <p>Do you already have an account? <a href="<?= URL_FRONT . 'accounts/login' ?>">Log in</a></p>
</div>

<?php $this->load->view('app/accounts/signup/vue_v') ?>