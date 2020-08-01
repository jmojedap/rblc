
<script>
// Variables
//-----------------------------------------------------------------------------
    var menu_elements = [
        { id: '', slug: '', text: 'All' },
        { id: '', slug: 'furniture', text: 'Furniture' },
        { id: '', slug: 'plants', text: 'Plants' },
        { id: '', slug: 'illumination', text: 'Illumination' },
        { id: '', slug: 'beds', text: 'Beds' },
        { id: '', slug: 'chairs', text: 'Chairs' },
        { id: '', slug: 'home-appliances', text: 'Home appliances' },
        { id: '', slug: 'upholstery', text: 'Upholstery' },
        { id: '', slug: 'doors', text: 'Doors' }
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
            cf: '<?php echo $cf; ?>',
            controller: '<?php echo $controller; ?>',
            num_page: 1,
            max_page: 1,
            search_num_rows: 0,
            list: [],
            element: [],
            filters: {},
            showing_filters: false,
            menu_elements: menu_elements,
            category: menu_elements[0]
        },
        methods: {
            get_list: function(){
                var params = new URLSearchParams();
                params.append('q', app_q);
                params.append('cat', this.category.slug);
                axios.post(url_api + this.controller + '/get/' + this.num_page, params)
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
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
            set_category: function(menu_key){
                console.log(this.category);
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