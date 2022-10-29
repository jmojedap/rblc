<?php //$this->load->view('assets/lightbox2') ?>

<div id="inspiration_app">
    <ul id="nav_2" class="nav_2 nav nav-pills nav-fill mb-4">
        <li class="nav-item" v-for="(menu, menu_key) in menu_elements">
            <a class="nav-link" href="#" v-on:click="set_tag(menu_key)" v-bind:class="{'active': menu.slug == tag.slug }">
                {{ menu.text }}
            </a>
        </li>
    </ul>
    <div class="row">
        <div class="col-md-4" v-for="(image, image_key) in images">
            <img
                v-bind:alt="image.title"
                v-bind:src="image.url_thumbnail"
                v-on:click="alt_like(image_key)"
                class="w100pc mb-2"
                onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
                >
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
    var menu_elements = [
        {
            slug: 'kitchen',
            text: 'Kitchen'
        },
        {
            slug: 'bathroom',
            text: 'Bathroom'
        },
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

// Vue Application
//-----------------------------------------------------------------------------
    new Vue({
        el: '#inspiration_app',
        created: function(){
            this.get_images();
        },
        data: {
            menu_elements: menu_elements,
            tag: menu_elements[0],
            images:[],
            num_page: 1
        },
        methods: {
            get_images: function(){
                var params = new URLSearchParams();
                params.append('q', app_q);
                axios.post(URL_API + 'professionals/inspiration_images/' + this.tag.slug + '/1', params)
                .then(response => {
                    this.images = response.data.images;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            set_tag: function(menu_key){
                this.tag = this.menu_elements[menu_key];
                this.get_images();
                history.pushState(null, null, URL_APP + 'professionals/inspiration/' + this.tag.slug + '/' + this.num_page);
            },
            alt_like: function(image_key){
                var image = this.images[image_key];
                axios.get(URL_API + 'files/alt_like/' + image.id)
                .then(response => {
                    /*if ( response.data.status == 1 ) {
                        
                    }*/
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>