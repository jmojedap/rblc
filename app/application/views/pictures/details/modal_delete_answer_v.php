<div class="modal" tabindex="-1" role="dialog" id="delete_answer_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete answer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Delete this comment?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="delete_answer" data-dismiss="modal">
                    Delete
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>