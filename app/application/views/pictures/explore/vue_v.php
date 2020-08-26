
<script>
// Variables
//-----------------------------------------------------------------------------
    var type_names = <?php echo json_encode($arr_types); ?>;
    var menu_elements = [
            { slug: '', text: 'All'},
            { slug: 'kitchen', text: 'Kitchen'},
            { slug: 'bathroom', text: 'Bathroom'},
            {
                slug: 'livingroom',
                text: 'Living Room'
            },
            {
                slug: 'wine-cellar',
                text: 'Wine cellar'
            },
            {
                slug: 'outdoor',
                text: 'Outdoor'
            },
            {
                slug: 'library',
                text: 'Library'
            },
            {
                slug: 'woodwork',
                text: 'Woodwork'
            },
            {
                slug: 'bar',
                text: 'Bars'
            },
            {
                slug: 'stonework',
                text: 'Stonework'
            },
            {
                slug: 'basement',
                text: 'Basement'
            },
            {
                slug: 'ironwork',
                text: 'Ironwork'
            }
        ];

// Filters
//-----------------------------------------------------------------------------

    /*Vue.filter('type_name', function (value) {
        if (!value) return '-';
        new_value = type_names[value];
        return new_value;
    });*/

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
            menu_elements: menu_elements,
            tag: menu_elements[0],
            loading_more: false,
            picture: {
                row: {description: ''},
                tags: {}
            }
        },
        methods: {
            get_list: function(){
                var params = new URLSearchParams();
                params.append('q', app_q);
                params.append('tag', this.tag.slug);
                axios.post(url_api + this.controller + '/get/' + this.num_page, params)
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
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
                this.get_details();
            },
            //Obtener detalles de Picture
            get_details: function(){
                axios.get(url_api + 'pictures/get_details/' + this.element.id)
                .then(response => {
                    this.picture = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });  
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
            //Like or unlike image
            alt_like: function(image_key){
                var image = this.list[image_key];
                axios.get(url_api + 'files/alt_like/' + image.id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        this.list[image_key].liked = 1;
                    } else if ( response.data.status == 2 ) {
                        this.list[image_key].liked = 0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Cargar más resultados al final del listado
            get_more: function(){
                this.num_page++;
                var params = new URLSearchParams();
                params.append('q', app_q);
                params.append('tag', this.tag.slug);
                axios.post(url_api + this.controller + '/get/' + this.num_page, params)
                .then(response => {
                    this.list = this.list.concat(response.data.list);   //Agregar resultados al listado
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                    this.loading_more = false;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Detectar el final del documento, scroll
            handle_scroll(e) {
                if($(window).scrollTop() + $(window).height() == $(document).height()) {
                    console.log('Load more page: ' + this.num_page);
                    if ( this.num_page < this.max_page )
                    {
                        this.loading_more = true;
                        this.get_more();
                    }
                }
            }
        },
        created() {
            this.get_list();
            window.addEventListener('scroll', this.handle_scroll);
        },
        destroyed() {
            window.removeEventListener('scroll', this.handle_scroll);
        }
    });
</script>