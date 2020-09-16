<div class="modal" tabindex="-1" role="dialog" id="delete_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Delete this element?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger w100p" v-on:click="delete_element" data-dismiss="modal">
                    Delete
                </button>
                <button type="button" class="btn btn-secondary w100p" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>