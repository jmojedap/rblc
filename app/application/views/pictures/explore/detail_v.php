<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <img
                            class="w100pc"
                            v-bind:src="element.url"
                            alt="Colibri Picture"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                        
                    </div>
                    <div class="col-md-3">
                        <div class="media mt-2">
                            <a v-bind:href="`<?php echo base_url("professionals/profile/") ?>` + element.user_id" class="small-title">
                                <img
                                    v-bind:src="element.user_url_thumbnail"
                                    class="rounded-circle mr-1 w40p"
                                    alt=""
                                    onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                                >
                            </a>
                            <div class="media-body">
                                <a v-bind:href="`<?php echo base_url("professionals/profile/") ?>` + element.user_id" class="small-title">
                                    {{ element.user_display_name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-none">
                <a class="btn btn-light w100p" v-bind:href="`<?php echo base_url('pictures/info/') ?>` + element.id">View more</a>
                <button type="button" class="btn btn-light w100p" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>