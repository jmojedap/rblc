<!-- Modal -->
<div class="modal fade" id="picture_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <img
                            class="w100pc"
                            v-bind:src="picture.url"
                            alt="Colibri Picture"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                        
                    </div>
                    <div class="col-md-3">
                        <div class="media mt-2">
                            <a href="<?php echo base_url("professionals/profile/{$row->id}") ?>" class="small-title">
                                <img
                                    src="<?= $row->url_image ?>"
                                    class="rounded-circle mr-1 w40p"
                                    alt=""
                                    onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                                >
                            </a>
                            <div class="media-body">
                                <a href="" class="small-title">
                                    <?= $row->display_name ?>
                                </a>
                            </div>
                        </div>
                        <p class="mt-2">
                            {{ picture.description }}
                        </p>
                        <p>
                            <a v-bind:href="`<?php echo base_url("pictures/explore/1/?q=") ?>` + tag.name" v-for="tag in picture.tags">
                                #{{ tag.name }}
                            </a>
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