<!-- Modal -->
<div class="modal fade" id="picture_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="btn btn-light" v-on:click="set_key(key - 1)"><i class="fa fa-chevron-left"></i></button>
                <button class="btn btn-light" v-on:click="set_key(key + 1)"><i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9 text-center">
                        <img
                            class="only-lg" style="max-height: 600px; max-width: 800px;"
                            v-bind:src="picture.url"
                            alt="Colibri Picture"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                        <img
                            class="only-sm w100pc" style="max-height: 600px;"
                            v-bind:src="picture.url"
                            alt="Colibri Picture"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                        
                    </div>
                    <div class="col-md-3">
                        <p class="mt-2">
                            {{ picture.description }}
                        </p>
                        <p>
                            <a v-bind:href="`<?php echo base_url("pictures/explore/1/?q=") ?>` + tag.name" v-for="tag in picture.tags">
                                #{{ tag.name }}
                            </a>
                        </p>
                        <p>
                            <a class="btn btn-main" v-bind:href="`<?= base_url("pictures/details/") ?>` + picture.id">+ Details</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-light w100p" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>