<div id="settings_app">
    <div class="center_box_450">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Email notifications</h3>
                <form accept-charset="utf-8" method="POST" id="settings_form" @submit.prevent="send_form">
                    <fieldset v-bind:disabled="loading">
                        <div class="form-group">
                            <div class="form-check d-flex">
                                <input class="form-check-input" type="checkbox" name="notify_new_follower" value="1" id="field-new_follower" v-model="settings.notify_new_follower">
                                <label class="form-check-label" for="field-new_follower">
                                    New follower
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notify_new_message" value="1" id="field-new_message" v-model="settings.notify_new_message">
                                <label class="form-check-label" for="field-new_message">
                                    New inbox message
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="notify_new_comment" value="1" id="field-new_comment" v-model="settings.notify_new_comment">
                                <label class="form-check-label" for="field-new_comment">
                                    New comment
                                </label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-main w120p" type="submit">Save</button>
                        </div>
                    <fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var settings_app = new Vue({
    el: '#settings_app',
    created: function() {
        this.get_settings()
    },
    data: {
        settings: {},
        loading: false,
    },
    methods: {
        get_settings: function(){
            axios.get(url_api + 'accounts/get_settings/')
            .then(response => {
                this.settings = response.data.settings
            })
            .catch(function(error) { console.log(error) })
        },
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('settings_form'))
            axios.post(url_api + 'accounts/save_settings/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>