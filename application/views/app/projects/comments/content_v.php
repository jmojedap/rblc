
    <div class="full_width_title">
        <h3 class="only-lg">Comments about this project</h3>
        <h4 class="only-sm">Comments</h3>
    </div>
    
    <?php if ( $this->session->userdata('logged') ) { ?>
        <div class="mb-2">
            <button class="btn btn-main" data-toggle="modal" data-target="#modal_form" v-on:click="clean_form">
                Add comment
            </button>
        </div>
    <?php } ?>
    
    <div class="comments">
        <div v-for="(comment, key) in comments" class="comment">
            <div>
                <div class="float-right">
                    <small>{{ comment.created_at | ago }}</small>
                </div>
                <b>{{ comment.display_name }}</b> &middot; {{ comment.username }} says:
                <br>
                <p>{{ comment.comment_text }}</p>
                <p>
                    <button class="btn btn-sm btn-light" v-on:click="get_answers(key)">
                        <i class="far fa-comment"></i> {{ comment.qty_comments }}
                    </button>
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <button class="btn btn-sm" v-on:click="reply_comment(key)" title="Answer">
                            Reply
                        </button>
                        <button class="btn btn-sm" data-toggle="modal" data-target="#delete_comment_modal" v-on:click="set_current(key)" v-if="APP_UID == comment.creator_id">
                            Delete
                        </button>
                    <?php } ?>
                </p>
            </div>

            <!-- Respuestas al comentario -->
            <div v-for="(answer, answer_key) in comment.answers" class="answer">
                <div class="float-right">
                    <small>{{ answer.created_at | ago }}</small>
                </div>

                <b>{{ answer.display_name }}</b> &middot; {{ answer.username }}
                <br>
                <p>{{ answer.comment_text }}</p>
                <p v-if="APP_UID == answer.creator_id">
                    <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#delete_answer_modal" v-on:click="set_current_answer(key, answer_key)">
                        Delete
                    </button>
                </p>
            </div>
        </div>
    </div>

    <!-- Siguiente etiqueta puesta para evitar error de no actualización de answers -->
    <pre class="d-none">{{ $data }}</pre>