<?php
    $cat_1 = 0;
    if ( ! is_null($this->input->get('cat_1')) ) $cat_1 = $this->input->get('cat_1');
?>

<script>
// Variables
//-----------------------------------------------------------------------------
    var type_names = <?= json_encode($arr_types); ?>;
    var menu_elements = {
        0: {id: '', slug: '', text: 'All'},
        1: {id:'1',slug:'kitchen-dining',text:'Kitchen & Dining'},
        2: {id:'2',slug:'bathrooms',text:'Bathrooms'},
        3: {id:'3',slug:'closets',text:'Closets'},
        4: {id:'4',slug:'bedrooms',text:'Bedrooms'},
        5: {id:'5',slug:'home-office',text:'Home Office'},
        6: {id:'6',slug:'living',text:'Living'},
        7: {id:'7',slug:'bar-wine',text:'Bar & Wine'},
        8: {id:'8',slug:'outdoor',text:'Outdoor'},
        9: {id:'9',slug:'walkways',text:'Walkways'},
        10: {id:'10',slug:'other-rooms',text:'Other Rooms'},
        99: {id:'99',slug:'other',text:'Other'}
    };

    var cat_1 = <?= $cat_1 ?>;

// VueApp
//-----------------------------------------------------------------------------
var app_explore = new Vue({
    el: '#app_explore',
    created: function(){
        this.get_list();
    },
    data: {
        app_rid: app_rid,
        cf: '<?= $cf; ?>',
        controller: '<?= $controller; ?>',
        num_page: 1,
        max_page: 1,
        search_num_rows: 0,
        list: [],
        element: [],
        filters: {},
        menu_elements: menu_elements,
        category: menu_elements[cat_1],
        loading_more: false,
        picture: {
            row: {description: ''},
            tags: {}
        },
        gallery_visible: true
    },
    methods: {
        get_list: function(){
            this.gallery_visible = false;

            var params = new URLSearchParams();
            params.append('q', app_q);
            params.append('cat_1', this.category.id);
            axios.post(url_api + this.controller + '/get/' + this.num_page, params)
            .then(response => {
                this.list = response.data.list;
                this.max_page = response.data.max_page;
                this.search_num_rows = response.data.search_num_rows;
                history.pushState(null, null, url_front + this.cf + this.num_page +'/?' + response.data.str_filters);
                this.all_selected = false;
                this.selected = [];
                this.gallery_visible = true;
            })
            .catch(function (error) { console.log(error) })
        },
        sum_page: function(sum){
            this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
            this.get_list();
        },
        set_current: function(key){
            this.element = this.list[key];
            this.get_details(key);
        },
        //Obtener detalles de Picture
        get_details: function(key){
            axios.get(url_api + 'pictures/get_details/' + this.list[key].id)
            .then(response => {
                //Cargar datos en VueApp: #picture_app
                picture_app.picture.id = response.data.id
                picture_app.picture.url = response.data.url
                picture_app.picture.title = response.data.title
                picture_app.picture.description = response.data.description
                picture_app.picture.qty_likes = parseInt(response.data.qty_likes)

                picture_app.tags = response.data.tags
                picture_app.like_status = response.data.like_status
                picture_app.user = response.data.user

                picture_app.get_comments()
            })
            .catch(function (error) { console.log(error) })
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
            params.append('cat_1', this.category.id);
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
            .catch(function (error) { console.log(error) })
        },
        //Detectar el final del documento, scroll
        handle_scroll(e) {
            var documentHeightZoom = $(document).height() * 0.9
            if($(window).scrollTop() + $(window).height() > documentHeightZoom) {
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