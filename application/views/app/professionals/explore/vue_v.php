<?php
    $cat_1 = 0;
    if ( ! is_null($filters['cat_1']) ) $cat_1 = $filters['cat_1'];
?>

<script>
// Variables
//-----------------------------------------------------------------------------
var menu_elements = {
    0: {id: '', slug: '', text: 'All'},
    1: {id:'1',slug:'design',text:'Design'},
    2: {id:'2',slug:'remodeling-renovation',text:'Remodeling & Renovation'},
    3: {id:'3',slug:'outdoor',text:'Outdoor'},
    4: {id:'4',slug:'apliances-systems',text:'Apliances & Systems'},
    5: {id:'5',slug:'services',text:'Services'}
};

var cat_1 = '<?= $cat_1 ?>';

// VueApp
//-----------------------------------------------------------------------------
var app_explore = new Vue({
    el: '#app_explore',
    created: function(){
        this.get_list();
    },
    data: {
        cf: 'professionals/explore/',
        controller: 'professionals',
        num_page: 1,
        max_page: 1,
        search_num_rows: 0,
        list: [],
        element: [],
        filters: <?= json_encode($filters) ?>,
        str_filters: '<?= $str_filters ?>',
        showing_filters: false,
        menu_elements: menu_elements,
        category: menu_elements[cat_1],
        loading: false
    },
    methods: {
        get_list: function(){
            this.loading = true
            var params = new URLSearchParams();
            params.append('q', app_q);
            params.append('cat_1', this.category.id);
            axios.post(url_api + this.controller + '/get/' + this.num_page, params)
            .then(response => {
                this.list = response.data.list;
                this.max_page = response.data.max_page;
                this.search_num_rows = response.data.search_num_rows;
                history.pushState(null, null, url_front + this.cf + this.num_page +'/?' + response.data.str_filters);
                this.loading = false
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        search: function(){
            axios.post(url_api + this.controller + '/get/' + this.num_page, $('#search_form').serialize())
            .then(response => {
                this.list = response.data.list;
                this.max_page = response.data.max_page;
                $('#head_subtitle').html(response.data.search_num_rows);
                history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                this.all_selected = false;
                this.selected = [];
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        sum_page: function(sum){
            this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page)
            this.get_list()
            window.scrollTo(0,0)
        },
        set_current: function(key){
            this.element = this.list[key];
        },
        set_category: function(menu_key){
            this.category = this.menu_elements[menu_key];
            this.num_page = 1;
            this.get_list();
        },
        set_app_q: function(){
            app_q = this.filters.q;
            this.num_page = 1;
            $('#app_q').val(this.filters.q);
            this.get_list();  
        },
    }
});
</script>