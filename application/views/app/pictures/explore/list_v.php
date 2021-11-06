<p class="text-center mt-3 mb-1" v-show="!gallery_visible">
    <img src="<?= URL_IMG ?>gifs/colibri.gif" alt="colibri animation">
</p>
<div class="gallery-3" v-show="gallery_visible">
    <div class="image-container" v-for="(image, image_key) in list">
        <img
            class="picture" data-toggle="modal" data-target="#detail_modal"
            v-bind:alt="image.title" v-bind:src="image.url_thumbnail" v-on:click="set_current(image_key)"
            onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.jpg'"
            >
        <?php if ( $this->session->userdata('logged') ) { ?>
            <img
                alt="like icon"
                src="<?= URL_IMG ?>front/icon_like.png"
                class="like_button"
                v-show="image.liked == 0"
                v-on:click="alt_like(image_key)">
            <img
                alt="like mark"
                src="<?= URL_IMG ?>front/icon_liked.png"
                class="like_mark"
                v-show="image.liked == 1"
                v-on:click="alt_like(image_key)">
        <?php } ?>
        <!-- <a v-bind:href="`<?= URL_FRONT . "pictures/details/" ?>` + image.id" class=""></a> -->
    </div>
</div>
<p class="text-center mt-3 mb-1" v-show="loading_more">
    <img src="<?= URL_IMG ?>gifs/colibri.gif" alt="colibri animation">
</p>
<p class="text-center" v-show="num_page == max_page">No more results</p>