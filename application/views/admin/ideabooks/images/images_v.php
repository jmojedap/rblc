<?php $this->load->view('assets/lightbox2') ?>
<?php $this->load->view('assets/justified_gallery') ?>

<script>
    $(document).ready(function(){
        $("#image_gallery").justifiedGallery({
            rowHeight : 150,
            lastRow : 'nojustify',
            margins : 5
        });
    });
</script>

<div id="project_images">
    <div id="image_gallery" >
        <a v-for="(image, image_key) in images" v-bind:href="image.url" data-lightbox="image-1">
            <img v-bind:alt="image.title" v-bind:src="image.url">
        </a>
    </div>
</div>

<script>
    new Vue({
        el: '#project_images',
        created: function(){
            //this.get_list();
        },
        data: {
            images: <?= json_encode($images->result()); ?>
        },
        methods: {
            
        }
    });
</script>