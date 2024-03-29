    <div class="row">
        <div class="col-md-8">
            <img
                class="w100pc"
                v-bind:src="row.url"
                alt="Colibri Picture"
                onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
            >
        </div>
        <div class="col-md-4">
            <div class="d-flex">
                <div class="p-2">
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon like" v-on:click="alt_like" v-show="like_status == 0">
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_liked.png" alt="Icon liked" v-on:click="alt_like" v-show="like_status > 0">
                    <?php } else { ?>
                        <img class="" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon liked">
                    <?php } ?>
        
                    <span class="counter">{{ row.qty_likes }}</span>
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_comment.png" alt="Icon comment" data-toggle="modal" data-target="#modal_form" v-on:click="clean_form">
                    <?php } ?>
                </div>
            </div>
            
            <div class="comments">
                <div v-for="(comment, key) in comments" class="comment">
                    <div>
                        <div class="float-right">
                            {{ comment.created_at | ago }}
                            
                        </div>
                        <b>{{ comment.display_name }}</b> &middot; {{ comment.username }} says:
                        <br>
                        <p>{{ comment.comment_text }}</p>
                        <p>
                            <button class="btn btn-sm btn-light" v-on:click="get_answers(key)">
                                <i class="far fa-comment"></i> {{ comment.qty_comments }}
                            </button>
                            <?php if ( $this->session->userdata('logged') ) { ?>
                                <button class="btn btn-sm" v-on:click="reply_comment(key)" title="Answer">Reply</button>
                                <button class="btn btn-sm" data-toggle="modal" data-target="#delete_comment_modal" v-on:click="set_current(key)" v-if="APP_UID == comment.creator_id">
                                    Delete
                                </button>
                            <?php } ?>
                        </p>
                    </div>
        
                    <!-- Respuestas al comentario -->
                    <div v-for="(answer, answer_key) in comment.answers" class="answer">
                        <div class="float-right">
                            {{ answer.created_at | ago }}
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
        </div>
    </div>
    