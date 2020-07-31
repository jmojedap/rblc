<!-- Modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form accept-charset="utf-8" method="POST" id="comment_form" @submit.prevent="send_form">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Write your comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" name="parent_id" v-model="form_values.parent_id" class="d-none">
                <div class="form-group">
                    <textarea
                        name="comment_text" id="field-comment_text" type="text" class="form-control"
                        required rows="4" placeholder="Your comment..."
                        v-model="form_values.comment_text"
                    ></textarea>
                </div>
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-main">Submit</button>
            </div>
            </div>
        </div>
    </form>
</div>