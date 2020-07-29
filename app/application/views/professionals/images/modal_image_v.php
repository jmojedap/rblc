<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img class="" style="max-height: 600px;"
                    alt="Colibri Picture"
                    v-bind:src="current.url"
                    onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger float-left" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
                <a class="btn btn-main" v-bind:href="`<?php echo base_url('pictures/info/') ?>` + current.id">View more</a>
                <button type="button" class="btn btn-light w100p" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>