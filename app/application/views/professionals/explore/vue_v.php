
<script>
// Variables
//-----------------------------------------------------------------------------
    var menu_elements = [
        { id: '', slug: '', text: 'All'},
        { id: '5', slug: 'artist-artisan', text: 'Artist and artisan' },
        { id: '6', slug: 'marketing-resources', text: 'Marketing resources'},
        { id: '7', slug: 'materials-spaces', text: 'Materials and spaces' },
        { id: '9', slug: 'special-services', text: 'Special services' },
        { id: '2', slug: 'woodwork', text: 'Woodwork' },
        { id: '3', slug: 'electronic-services', text: 'Electronic services' },
        { id: '4', slug: 'builders-remodelators', text: 'Builders and remodelators' },
        { id: '8', slug: 'professional', text: 'Professional' }
    ];

// App
//-----------------------------------------------------------------------------

    new Vue({
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
            filters: <?php echo json_encode($filters) ?>,
            str_filters: '<?= $str_filters ?>',
            showing_filters: false,
            menu_elements: menu_elements,
            category: menu_elements[0],
            loading: false
        },
        methods: {
            get_list: function(){
                this.loading = true
                var params = new URLSearchParams();
                params.append('q', app_q);
                params.append('cat', this.category.id);
                axios.post(url_api + this.controller + '/get/' + this.num_page, params)
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, app_url + this.cf + this.num_page +'/?' + response.data.str_filters);
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
                    history.pushState(null, null, app_url + this.cf + this.num_page +'/?' + response.data.str_filters);
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