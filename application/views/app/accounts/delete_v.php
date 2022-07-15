<div id="deleteApp">
    <div v-if="user_id > 0">
        <div class="text-center pb-3 border-bottom mb-3">
            <h2 class="text-center">Delete account: <?= $row->display_name ?></h2>
            <p class="text-muted"><?= $row->username ?></p>
        </div>
        <div class="row mb-2">
            <div class="col-md-6">
                <h3 class="text-main">Are you sure?</h3>
                <p class="">
                    <i class="fa fa-exclamation-triangle text-danger mr-2"></i>
                    All your data and files will be permanently deleted
                </p>
                <p>
                    We would hate to see you go, but if you are completely
                    sure, tell uss why are you leaving.
                </p>
                <p>
                    Pick a reason as to why you're delete your account.
                </p>
            </div>
            <div class="col-md-6">
                <form id="deleteForm" method="post" accept-charset="utf-8" @submit.prevent="sendForm" v-show="user_id > 0">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="user_id" v-model="user_id">
                        <input type="hidden" name="activation_key" v-model="formValues.activation_key">
                        <input type="hidden" name="delete_reason" v-model="formValues.delete_reason">
                        <div class="mb-3">
                            <div class="form-check" v-for="(optionReason,key) in optionsReason">
                                <input class="form-check-input" type="radio" name="reason" v-bind:id="`gridRadios` + key" v-bind:value="optionReason" v-model="formValues.delete_reason">
                                <label class="form-check-label" v-bind:for="`gridRadios` + key">
                                    {{ optionReason }}
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="description" v-model="formValues.description" rows="2" required
                                placeholder="Let us know why..." class="form-control"
                            ></textarea>
                        </div>

                        <div class="d-flex">
                            <button class="btn btn-secondary btn-lg mr-2" type="submit" v-bind:disabled="submitDisabled">
                                Delete my Account
                            </button>
                            <a class="btn btn-light btn-lg w120p" href="<?= URL_APP ?>home/">
                                Cancel
                            </a>
                        </div>
                    <fieldset>
                </form>
            </div>
        </div>
    
    
        <div class="alert alert-danger" v-show="errors">
            <i class="fa fa-info-circle"></i>
            {{ errors }}
        </div>
    </div>

    <div class="alert alert-danger" v-else>
        <i class="fa fa-info-circle"></i>
        Unidentified user with key: <strong>{{ formValues.activation_key }}</strong>
    </div>

</div>

<script>
var deleteApp = new Vue({
    el: '#deleteApp',
    data: {
        loading: false,
        user_id: <?= $user_id ?>,
        formValues: {
            activation_key: '<?= $activation_key ?>',
            delete_reason: '',
            description: '',
        },
        hide_message: true,
        errors: 0,
        optionsReason: [
            'Trouble getting started',
            'Created a second account',
            'Privacy concerns',
            'Too busy',
            'I don`t see the benefit',
            'Other'
        ],
    },
    methods: {
        sendForm: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('deleteForm'))
            axios.post(url_api + 'accounts/delete_account/', formValues)
            .then(response => {
                this.errors = response.data.errors;
                if ( response.data.status == 1 ) {
                    toastr['info']('Your account was successfully deleted');
                    setTimeout(function(){ window.location = url_app + 'accounts/login'; }, 3000);
                } else {
                    this.loading = false
                }
            })
            .catch(function (error) { console.log(error) })
        }
    },
    computed: {
        submitDisabled: function(){
            if ( this.formValues.delete_reason.length == 0 ) return true
            return false
        },
    }
});
</script>