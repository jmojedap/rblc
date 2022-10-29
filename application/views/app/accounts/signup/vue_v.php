<script>
// Variables
//-----------------------------------------------------------------------------
var items_cat_2 = <?= json_encode($items_cat_2->result()) ?>;

// VueApp
//-----------------------------------------------------------------------------
var signup_app = new Vue({
    el: '#signup_app',
    data: {
        step: 1,
        role_type: 'homeowner',
        email: '',
        first_name: '',
        last_name: '',
        cat_1: '',
        cat_2: '',
        email_is_unique: -1,
        passwords_match: -1,
        pw: '',
        pc: '',
        validated: -1,
        options_cat_1: <?= json_encode($options_cat_1) ?>,
        loading: false
    },
    methods: {
        register: function(){
            if ( this.validated ) {
                this.loading = true
                var form_data = new FormData(document.getElementById('signup_form'))
                axios.post(URL_API + 'accounts/register/' + this.role_type, form_data)
                .then(response => {
                    console.log(response.data.message);
                    if ( response.data.status == 1 ) {
                        window.location = URL_APP + 'accounts/registered/' + response.data.saved_id;
                    } else {
                        this.recaptcha_message = response.data.recaptcha_message;
                    }
                    this.loading = false
                })
                .catch(function (error) { console.log(error) })
            } else {
                console.log('El formulario no ha sido validado');
            }
        },
        check_email: function(){                
            this.loading = true
            var form_data = new FormData(document.getElementById('email_form'))
            axios.post(URL_API + 'accounts/check_email/', form_data)
            .then(response => {
                if ( response.data.status == 1 )
                {
                    this.email_is_unique = 0
                } else {
                    this.email_is_unique = 1
                    this.validated = 1
                    this.set_step(3)
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        set_step: function(step){
            this.step = step
            if ( step == 2 ) { this.email_is_unique = -1 }
            if ( step <= 3 ) {
                this.cat_1 = ''
                this.cat_2 = ''
            }
            if ( step == 3 && this.role_type == 'homeowner' ) {
                this.step = 4
            }
        },
        validate: function(){
            this.validated = 1
            //Unique email
            if ( this.email_is_unique != 1 ) { this.validated = 0 }
            //Password Match
            if ( this.passwords_match != 1 ) { this.validated = 0 }
        },
        /*validate_pc_match: function(){
            //Match Password
            if ( this.pw == this.pc )
            {
                this.passwords_match = 1;
            } else {
                this.passwords_match = 0;
            }
            this.validate();
        },*/
        validate_passwords_match: function(){
            if ( this.pc.length > 0 )
            {
                //Match Password
                if ( this.pw == this.pc )
                {
                    this.passwords_match = 1;
                } else {
                    this.passwords_match = 0;
                }
            }
            this.validate();
        },
        set_role_type: function(role_type){
            signup_role_type = role_type;
            this.role_type = role_type;
            this.set_step(2);
        },
        unset_cat_2: function(){
            this.cat_2 = ''
        },
    },
    computed: {
        //Establecer opciones dependiendo del valor de cat_1
        options_cat_2: function(){
            var cat_1_int = parseInt(this.cat_1)
            return items_cat_2.filter(item => item.parent_id == cat_1_int)
        },
        cat_2_label: function(){
            cat_2_label = ''
            if ( this.cat_2 != '' ) {
                var item_selected = items_cat_2.find(item => item.cod == this.cat_2)
                console.log(item_selected)
                cat_2_label = item_selected.item_name
            }
            return cat_2_label
        }
    },
});
</script>