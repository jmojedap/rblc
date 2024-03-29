
<script>
// Variables
//-----------------------------------------------------------------------------


// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

// App
//-----------------------------------------------------------------------------

var app_explore = new Vue({
    el: '#app_explore',
    created: function(){
        this.calculate_active_filters()
    },
    data: {
        cf: '<?= $cf ?>',
        controller: '<?= $controller ?>',
        search_num_rows: <?= $search_num_rows ?>,
        num_page: <?= $num_page ?>,
        max_page: <?= $max_page ?>,
        list: <?= json_encode($list) ?>,
        element: [],
        selected: [],
        all_selected: false,
        filters: <?= json_encode($filters) ?>,
        display_filters: false,
        loading: false,
        active_filters: false,
        options_checked: {'':'[ Todos ]','01':'Revisados','02':'Sin revisión'},
        options_cat_1: <?= json_encode($options_cat_1) ?>,
        arrGroup1: [
            {value:1,'name':'Sí'},
            {value:2,'name':'No'},
        ],
    },
    methods: {
        get_list: function(e, num_page = 1){
            this.loading = true
            axios.post(URL_APP + this.controller + '/get/' + num_page, $('#search_form').serialize())
            .then(response => {
                this.num_page = num_page
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                $('#head_subtitle').html(response.data.search_num_rows)
                history.pushState(null, null, URL_APP + this.cf + this.num_page +'/?' + response.data.str_filters)
                this.all_selected = false
                this.selected = []
                console.log('Vamos a poner falso')
                this.loading = false

                this.calculate_active_filters()
            })
            .catch(function (error) { console.log(error) })
        },
        select_all: function() {
            if ( this.all_selected )
            { this.selected = this.list.map(function(element){ return element.id }) }
            else
            { this.selected = [] }
        },
        sum_page: function(sum){
            var new_num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page)
            this.get_list(null, new_num_page)
        },
        delete_selected: function(){
            var params = new FormData()
            params.append('selected', this.selected)
            
            axios.post(URL_APP + this.controller + '/delete_selected', params)
            .then(response => {
                this.hide_deleted()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Registros eliminados: ' + response.data.qty_deleted)
                }
            })
            .catch(function (error) { console.log(error) })
        },
        hide_deleted: function(){
            for ( let index = 0; index < this.selected.length; index++ )
            {
                const element = this.selected[index]
                console.log('ocultando: row_' + element)
                $('#row_' + element).addClass('table-danger')
                $('#row_' + element).hide('slow')
            }
        },
        set_current: function(key){
            this.element = this.list[key]
        },
        toggle_filters: function(){
            this.display_filters = !this.display_filters
            $('#adv_filters').toggle('fast')
        },
        remove_filters: function(){
            this.filters.q = ''
            this.filters.fe1 = ''
            this.filters.fe2 = ''
            this.filters.fe3 = ''
            this.filters.cat_1 = ''
            this.filters.d1 = ''
            this.filters.d2 = ''
            this.display_filters = false
            setTimeout(() => { this.get_list() }, 100)
        },
        calculate_active_filters: function(){
            var calculated_active_filters = false
            if ( this.filters.q ) calculated_active_filters = true
            if ( this.filters.fe1 ) calculated_active_filters = true
            if ( this.filters.fe2 ) calculated_active_filters = true
            if ( this.filters.fe3 ) calculated_active_filters = true
            if ( this.filters.cat_1 ) calculated_active_filters = true
            if ( this.filters.d1 ) calculated_active_filters = true
            if ( this.filters.d2 ) calculated_active_filters = true

            this.active_filters = calculated_active_filters
        },
    }
})
</script>