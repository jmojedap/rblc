<?php $this->load->view('assets/recaptcha') ?>

<?php
    $email = '';
    if ( $this->session->userdata('logged') ) {
        $email = $this->Db_model->field_id('users', $this->session->userdata('user_id'), 'email');
    }
?>

<div id="subscribe_app">
    <div class="card center_box_450 text-center">
        <div class="card-body">
            <h3 class="card-title">E-mail susbcription</h3>
            <p class="">
                Get E-mail updates about our latest shop and special offers.
            </p>
            <form accept-charset="utf-8" method="POST" id="subscription_form" @submit.prevent="save_subscription" v-show="status == 10">
                <!-- Campo para validación Google ReCaptcha V3 -->
                <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                <div class="form-group">
                    <input
                        name="email" id="field-email" type="email" class="form-control"
                        required
                        title="E-mail" placeholder="Write your E-mail"
                        v-model="email"
                    >
                </div>
                <div class="form-group">
                    <button class="btn btn-main btn-block">
                        Subscribe
                    </button>
                </div>
            </form>

            <div v-show="status == 5">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
            </div>

            <div class="" v-show="status == 1">
                <h3>Thanks!</h3>
                <p><strong>{{ email }}</strong></p>
                <p><i class="fa fa-check text-success"></i> Your email was successfully registered</p>
            </div>

            <div class="" v-show="status == 0">
                <h3>Oops!</h3>
                <p><strong>{{ email }}</strong></p>
                <p><i class="fa fa-exclamation-triangle text-warning"></i> Something went wrong</p>
                <a href="<?= base_url("app/subscribe") ?>" class="btn btn-warning w120p">
                    Try again
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#subscribe_app',
        data: {
            status: 10, //Inicial, sin empezar
            email: '<?= $email ?>',
        },
        methods: {
            save_subscription: function(){
                this.status = 5;    //En proceso
                axios.post(url_api + 'app/save_subscription/', $('#subscription_form').serialize())
                .then(response => {
                    this.status = response.data.status;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>