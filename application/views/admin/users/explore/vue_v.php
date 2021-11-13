
<script>
// Variables
//-----------------------------------------------------------------------------
var role_names = <?= json_encode($arr_roles); ?>;
var id_number_type_names = <?= json_encode($arr_id_number_types); ?>;
var status_icons = {
    "0":'<i class="far fa-circle text-danger" title="Inactivo"></i>',
    "1":'<i class="fa fa-check-circle text-success" title="Activo"></i>'
};

// Filters
//-----------------------------------------------------------------------------
Vue.filter('status_icon', function (value) {
    if (!value) return '';
    value = status_icons[value];
    return value;
});

Vue.filter('role_name', function (value) {
    if (!value) return '';
    value = role_names[value];
    return value;
});

Vue.filter('id_number_type_name', function (value) {
    if (!value) return '';
    value = id_number_type_names['0' + value];
    return value;
});

// App
//-----------------------------------------------------------------------------
var app_aplore = new Vue({
    el: '#app_explore',
    data: {
        cf: '<?= $cf; ?>',
        controller: '<?= $controller; ?>',
        search_num_rows: <?= $search_num_rows ?>,
        num_page: 1,
        max_page: 1,
        list: <?= json_encode($list) ?>,
        element: [],
        selected: [],
        all_selected: false,
        filters: <?= json_encode($filters) ?>,
        showing_filters: false,
        options_role: <?= json_encode($options_role) ?>,
        options_invitation_status: {"":"[ All ]", "00":"Sin invitación", "01":"Invitado"},
        options_status: {"":"[ All ]", '00':'Inactivo', "01":"Activado", "02":"Registrado"},
    },
    methods: {
        get_list: function(){
            axios.post(url_app + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
            .then(response => {
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                this.all_selected = false;
                this.selected = [];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        select_all: function() {
            this.selected = [];
            if (this.all_selected) {
                for (element in this.list) {
                    this.selected.push(this.list[element].id);
                }
            }
        },
        sum_page: function(sum){
            this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
            this.get_list();
        },
        delete_selected: function(){
            var params = new FormData();
            params.append('selected', this.selected);
            
            axios.post(url_app + this.controller + '/delete_selected', params)
            .then(response => {
                this.hide_deleted();
                this.selected = [];
                if ( response.data.status == 1 )
                {
                    toastr_cl = 'info';
                    toastr_text = 'Registros eliminados';
                    toastr[toastr_cl](toastr_text);
                }
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        hide_deleted: function(){
            for (let index = 0; index < this.selected.length; index++) {
                const element = this.selected[index];
                console.log('ocultando: row_' + element);
                $('#row_' + element).addClass('table-danger');
                $('#row_' + element).hide('slow');
            }
        },
        set_current: function(key){
            this.element = this.list[key];
        },
        toggle_filters: function(){
            this.showing_filters = !this.showing_filters;
            $('#adv_filters').toggle('fast');
        },
    }
});
</script>