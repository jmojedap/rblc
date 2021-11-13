<script>
var invitations_app = new Vue({
    el: '#invitations_app',
    created: function(){
        this.get_list()
        this.get_summary()
    },
    data: {
        summary: {},
        form_values: {
            bcc: '<?= $bcc ?>',
            text_message: '<?= $text_message ?>'
        },
        list: [],
        images: [],
        current: {id: 0},
        search_num_rows: 0,
        num_page: <?= $num_page ?>,
        max_page: 1,
        loading: false,
        filters: <?= json_encode($filters) ?>,
        options_invitation_status: {"":"[ All ]", "00":"Sin invitación", "01":"Invitado"},
        options_status: {"":"[ All ]", '00':'Inactivo', "01":"Activado", "02":"Registrado"},
    },
    methods: {
        get_list: function(){
            this.loading = true
            var form_data = new FormData()
            form_data.append('role', 13)
            if ( this.filters.q != null ) form_data.append('q', this.filters.q)
            if ( this.filters.u != null ) form_data.append('u', this.filters.u)
            if ( this.filters.fe1 != null ) form_data.append('fe1', this.filters.fe1)
            if ( this.filters.status != null ) form_data.append('status', this.filters.status)

            axios.post(url_api + 'users/get_users_invitations/' + this.num_page + '/10', form_data)
            .then(response => {
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                history.pushState(null, null, url_app + 'users/invitations/' + this.num_page +'/?' + response.data.str_filters);
                this.loading = false
                if (this.list.length > 0 && this.current.id == 0 ) {
                    this.set_user(0)
                }
            })
            .catch( function(error) {console.log(error)} )
        },
        set_user: function(key){
            this.current = this.list[key]
            this.get_images();
        },
        get_images: function(){
            axios.get(url_api + 'professionals/get_images/' + this.current.id)
            .then(response => {
                this.images = response.data.images
            })
            .catch(function(error) { console.log(error) })
        },
        send_invitation: function(){
            console.log('enviando')
            this.loading = true
            var form_data = new FormData()
            form_data.append('user_id', this.current.id)
            form_data.append('bcc', this.form_values.bcc)
            form_data.append('text_message', this.form_values.text_message)
            axios.post(url_api + 'users/send_invitation/', form_data)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success'](response.data.message)
                    this.current.qty_invitations += 1
                } else {
                    toastr['warning'](response.data.message)
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        get_summary: function(){
            axios.get(url_api + 'users/invitations_summary/')
            .then(response => {
                this.summary = response.data
            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>