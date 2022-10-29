<link rel="stylesheet" href="<?= URL_RESOURCES . 'templates/colibri_blue/colibri_slider.css' ?>">
<link rel="stylesheet" href="<?= URL_RESOURCES . 'templates/colibri_blue/home.css' ?>">

<div id="home_app">
    <div id="colibri-slider">
        <div id="colibri-slider-content" class="layer">
            <a class="slide" v-bind:class="{'active': k == current_carousel_key }" v-bind:href="image.external_link" v-for="(image, k) in carousel" v-bind:id="`slide_` + k">
                <img v-bind:src="image.url" v-bind:alt="image.title">
            </a>
        </div>
        <div id="colibri-slider-filter" class="layer"></div>
        <div id="colibri-slider-tools" class="layer">
            <div>
                <h2>IDEAS FOR YOUR HOME</h2>
                <a class="btn-slider" href="<?= URL_APP . 'accounts/signup' ?>">Register Now</a>
            </div>
            <div id="colibri-slider-selector">
                <span v-for="(image, k) in carousel" v-on:click="set_carousel_image(k)" v-bind:class="{'active': k == current_carousel_key }"></span>
            </div>
        </div>
    
    </div>

    <div class="container">
        <div class="only-lg">
            <h3 class="section-title">DISCOVER COLIBRI</h3>
            <div id="home_discover">
                <div class="item" v-for="item in discover_items">
                    <div class="layer bg-image" v-bind:style="`background-image: url('<?= URL_IMG . 'home/' ?>` + item.bg_file + `');`">
                        <!-- <img v-bind:src="`<?= URL_IMG . 'home/' ?>` + item.bg_file" alt="Cover 1"> -->
                    </div>
                    <div class="layer filter"></div>
                    <div class="layer cover">
                        <h3>{{ item.title }}</h3>
                    </div>
                    <div class="layer info">
                        <div class="text-center">
                            <p class="text-center mb-3 p-3">
                                <span>{{ item.text }}</span>
                            </p>
                            <div>
                                <img v-bind:src="`<?= URL_IMG ?>home/` + item.icon" alt="Icon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="home-roles text-center">
            <div>
                <h4 class="section-title">COLIBRI FOR HOME OWNERS</h4>
                <p>
                    An expansive database of local top quality professionals,
                    designed to easily connect you with an expert team and the
                    inspiration for your next project.
                </p>
                <a class="btn btn-main btn-lg w180p" href="<?= URL_APP . 'info/homeowners/' ?>">More Info</a>
            </div>
            <div>
                <h4 class="section-title">COLIBRI FOR PROFESSIONALS</h4>
                <p>
                    A directory, portfolio, and social platform designed to help
                    you connect with fellow professionals and reach thousands of
                    potential clients.
                </p>
                <a class="btn btn-main btn-lg w180p" href="<?= URL_APP . 'info/professionals' ?>">More Info</a>
            </div>
        </div>
        
        <div class="gallery-3 mb-3">
            <div class="image-container" v-for="(image, image_key) in list">
                <img
                    class="picture" data-toggle="modal" data-target="#picture_modal"
                    v-bind:alt="image.title" v-bind:src="image.url_thumbnail" v-on:click="get_details(image_key)"
                    onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
                    >
            </div>
        </div>

        <div class="home-register-section">
            <div class="layer tools">
                <div></div>
                <div class="text-center">
                    <img src="<?= URL_IMG ?>home/colibri-blue.png" alt="Logo colibri" class="mb-3">
                    <p class="mt-3">
                    Your work can help inspire & attract <br>
                    potential clients & professionals
                    </p>
                </div>
                <div class="text-center">
                    <a class="btn btn-secondary btn-lg" href="<?= URL_FRONT . "accounts/signup" ?>">
                        Register Now
                    </a>
                </div>
            </div>
            <img class="layer only-lg" src="<?= URL_IMG ?>home/register_background.jpg" alt="Fondo">
        </div>
    </div>
</div>

<script>
// VueApp
//-----------------------------------------------------------------------------
var home_app = new Vue({
    el: '#home_app',
    created: function(){
        this.get_list()
        this.set_carousel_image(0)
        this.update_carousel_image()
    },
    data: {
        list: [],
        carousel: <?= json_encode($carousel_images->result()) ?>,
        changes: '',
        current_carousel_key: 0,   //Current Carousel Key
        discover_items: [
            {
                title: 'INSPIRATION',
                text: 'You can look for ideas and references to complete your home.',
                bg_file: 'discover_1.jpg',
                icon: 'discover_inspiration.png',
            },
            {
                title: 'PROFESSIONALS',
                text: 'Contact services and enterprices who make the laundry, cleaning and reparing job.',
                bg_file: 'discover_2.jpg',
                icon: 'discover_professional.png',
            },
            {
                title: 'FIND PRODUCTS',
                text: 'Here all projects and products for luxury design.',
                bg_file: 'discover_3.jpg',
                icon: 'discover_products.png',
            },
        ],
    },
    methods: {
        get_list: function(){
            axios.get(URL_API + 'pictures/get_home_pictures/')
            .then(response => {
                this.list = response.data.list;
            })
            .catch(function (error) { console.log(error) })
        },
        //Obtener detalles de Picture
        get_details: function(key){
            axios.get(URL_API + 'pictures/get_details/' + this.list[key].id)
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
        populate_slider: function () {
            this.carousel.forEach(e => {
                let slide = document.createElement('a');
                $(slide)
                    .attr('href', e.external_link)
                    .append(`<img src="${e.url}">`)
                    .append(`<span class="slide-title">${e.title}<span>`)
                    .append(`<span class="slide-subtitle">${e.subtitle}<span>`);
                $('#colibri-slider-content').prepend(slide);
            });
        },
        update_carousel_image: function(){  
            this.interval = setInterval(() => {
                new_slide_key = this.current_carousel_key + 1;
                if ( new_slide_key >= this.carousel.length ) new_slide_key = 0
                this.set_carousel_image(new_slide_key)
            }, 6000);
        },
        set_carousel_image: function(k){  
            this.current_carousel_key = k
        }
    }
});
</script>

<!-- Modal -->
<div class="modal fade" id="picture_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="min-height: 600px;">
    <div class="modal-content">
      <div class="modal-body" id="picture_modal_content">
        <?php $this->load->view('app/pictures/details_modal/details_v') ?>
      </div>
    </div>
  </div>
</div>