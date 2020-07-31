
<script>
// Variables
//-----------------------------------------------------------------------------
    var menu_elements = [
        {
            slug: 'artist-artisan',
            text: 'Artist and artisan'
        },
        {
            slug: 'marketing-resources',
            text: 'Marketing resources'
        },
        {
            slug: 'materials-spaces',
            text: 'Materials and spaces'
        },
        {
            slug: 'special-services',
            text: 'Special services'
        },
        {
            slug: 'woodwork',
            text: 'Woodwork'
        },
        {
            slug: 'electronic-services',
            text: 'Electronic services'
        },
        {
            slug: 'builders-remodelators',
            text: 'Builders and remodelators'
        },
        {
            slug: 'professional',
            text: 'Professional'
        }
    ];

// Filters
//-----------------------------------------------------------------------------

    

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
            tag: menu_elements[0]
        },
        methods: {
            get_list: function(){
                axios.post(url_api + this.controller + '/get/' + this.num_page + '/?' + this.str_filters)
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, app_url + this.cf + this.num_page +'/?' + response.data.str_filters);
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
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            set_current: function(key){
                this.element = this.list[key];
            },
        }
    });
</script>