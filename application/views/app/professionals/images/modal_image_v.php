<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete_modal"><i class="fa fa-trash"></i></button>
                    <a class="btn btn-main w120p" v-bind:href="`<?= URL_FRONT . 'pictures/edit/' ?>` + current.id">Edit</a>
                    <button type="button" class="btn" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <img class="w100pc" alt="Colibri Picture"
                v-bind:src="current.url" onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
            >
        </div>
    </div>
</div>