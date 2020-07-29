<div id="activation_app" class="text-center center_box_450">
    <div v-show="status == 1">
        <p class="text-center"><i class="fa fa-check-circle text-success fa-2x"></i></p>
        <h1>{{ display_name }}</h1>
        <p>
            Your account was successfully activated
        </p>
        <a class="btn btn-success btn-lg btn-block" href="<?= base_url('app/logged') ?>">
            Continue
        </a>
    </div>
    <div v-show="status == 0">
        <p class="text-center"><i class="fa fa-exclamation-triangle text-warning"></i> Do not activated</p>
        <h1>Unidentified account</h1>
        <p>The activation key does not correspond to any user account</p>
    </div>
</div>

<script>
    new Vue({
        el: '#activation_app',
        created: function(){
            this.activate();
        },
        data: {
            status: -1,
            url_api: '<?= URL_API ?>',
            user_id: -1,
            display_name: '',
            activation_key: '<?= $activation_key ?>'
        },
        methods: {
            activate: function(){
                axios.get(this.url_api + 'accounts/activate/' + this.activation_key)
                .then(response => {
                    this.status = response.data.status;
                    if ( response.data.status == 1 ) {
                        this.user_id = response.data.user_id;
                        this.display_name = response.data.display_name;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>