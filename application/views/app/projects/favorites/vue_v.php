
<script>
// Variables
//-----------------------------------------------------------------------------
    var menu_elements = [
        { slug: 'furniture', text: 'Furniture' },
        { slug: 'plants', text: 'Plants' },
        { slug: 'illumination', text: 'Illumination' },
        { slug: 'beds', text: 'Beds' },
        { slug: 'chairs', text: 'Chairs' },
        { slug: 'home-appliances', text: 'Home appliances' },
        { slug: 'upholstery', text: 'Upholstery' },
        { slug: 'doors', text: 'Doors' }
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
            cf: '<?= $cf; ?>',
            controller: '<?= $controller; ?>',
            num_page: 1,
            max_page: 1,
            search_num_rows: 0,
            list: [],
            element: [],
            filters: {},
            showing_filters: false,
            menu_elements: menu_elements,
            tag: menu_elements[0]
        },
        methods: {
            get_list: function(){
                axios.post(URL_API + this.controller + '/get/' + this.num_page + '/?like=1', $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, URL_APP + this.cf + this.num_page +'/?' + response.data.str_filters);
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
            set_tag: function(menu_key){
                this.tag = this.menu_elements[menu_key];
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