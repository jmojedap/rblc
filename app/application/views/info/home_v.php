<div id="home_app">
    <div class="home">
        <div id="new-slider">
            <div class="arrow prev"></div>
            <div class="arrow next"></div>
        </div>
        <div class="three-buttons bg-main only-lg">
            <div class="icons text-center">
                <div class="d-flex">
                    <div class="icon">
                        <img src="<?= URL_IMG ?>front/icon_inspiration.png" alt="Icon image" class="mb-3">
                        <h3>Find inspiraton</h3>
                        <p>
                            You can look for ideas and references to complete your home
                        </p>
                    </div>
                    <div class="icon">
                        <img src="<?= URL_IMG ?>front/icon_professional.png" alt="Icon image" class="mb-3">
                        <h3>Find professionals</h3>
                        <p>
                        Contact services and enterprices who make the laundry, cleaning and reparing job
                        </p>
                    </div>
                    <div class="icon">
                        <img src="<?= URL_IMG ?>front/icon_product.png" alt="Icon image" class="mb-3">
                        <h3>Find products</h3>
                        <p>
                            Here all projects and products of luxury design
                        </p>
                    </div>
                    
                    
                </div>
            </div>
        </div>

        <div class="gallery-3 my-3">
            <div class="image-container" v-for="(image, image_key) in list">
                <a v-bind:href="`<?php echo base_url("professionals/profile/") ?>` + image.user_id">
                    <img
                        class="picture" data-toggle="modal" data-target="#detail_modal"
                        v-bind:alt="image.title" v-bind:src="image.url_thumbnail" v-on:click="set_current(image_key)"
                        onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                </a>
            </div>
        </div>

        <div style="background-image: url('<?= URL_IMG ?>home/home-footer-1.jpg')" class="img-footer only-lg">
            <h3>You can be part of our inspiration fee as <br> a professional and offer your services</h3>
            <a href="<?= base_url("accounts/signup") ?>" class="btn btn-xl btn-main w210p">
                REGISTER NOW
            </a>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#home_app',
        created: function(){
            this.get_list();
            this.populate_slider();
            this.update_carousel_image();
        },
        data: {
            list: [],
            carousel: <?= json_encode($carousel_images->result()) ?>,
            changes: '',
            i: 0,   //Current Carousel Key
        },
        methods: {
            get_list: function(){
                axios.get(url_api + 'pictures/get_random/')
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
            populate_slider: function () {
                this.carousel.forEach(e => {
                    let slide = document.createElement('a');
                    $(slide)
                        .attr('href', e.external_link)
                        //.attr('target', e.target)
                        .append(`<img src="${e.url}">`)
                        .append(`<span class="slide-title">${e.title}<span>`)
                        .append(`<span class="slide-subtitle">${e.subtitle}<span>`);
                    $('#new-slider').prepend(slide);
                });
            },
            update_carousel_image: function(){  
                this.interval = setInterval(() => {
                    let last = $('#new-slider a').last();
                    $(last).animate({
                        'opacity': 0
                    }, {
                        duration: 350,
                        easing: 'linear',
                        complete: function () {
                            $(last).prependTo($('#new-slider'));
                            $(last).css('opacity', 1);
                        }
                    })
                }, 6000);
            }
        }
    });
</script>

<script>
    if ($('#new-slider')) {
        $('#new-slider').css('height', (9 * $('#new-slider').width() / 16) + 'px');
        console.log('configuring slider ...');
        $(window).resize(function () {
            $('#new-slider').css('height', (9 * $('#new-slider').width() / 16) + 'px');
        });
    }
</script>