    <div class="row">
        <div class="col-md-8">
            <img
                class="w100pc mb-2"
                v-bind:src="picture.url"
                alt="Colibri Picture"
                onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
            >
        </div>
        <div class="col-md-4">
            <div class="media pb-2 border-bottom my-2">
                <a v-bind:href="`<?= URL_FRONT . "professionals/profile/" ?>` + user.id" class="small-title">
                    <img
                        v-bind:src="user.url_thumbnail"
                        class="rounded-circle mr-1 w40p"
                        alt=""
                        onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                    >
                </a>
                <div class="media-body">
                    <a v-bind:href="`<?= URL_FRONT . "professionals/profile/" ?>` + user.id" class="small-title">
                        {{ user.display_name }}
                    </a>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="p-2">
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon like" v-on:click="alt_like" v-show="like_status == 0">
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_liked.png" alt="Icon liked" v-on:click="alt_like" v-show="like_status > 0">
                    <?php } else { ?>
                        <img class="" src="<?= URL_IMG ?>front/icon_like.png" alt="Icon liked">
                    <?php } ?>
        
                    <span class="counter">{{ picture.qty_likes }}</span>
                    <?php if ( $this->session->userdata('logged') ) { ?>
                        <img class="action_icon" src="<?= URL_IMG ?>front/icon_comment.png" alt="Icon comment" data-toggle="modal" data-target="#modal_form" v-on:click="clean_form">
                    <?php } ?>
                </div>
                <!-- HERRAMIENTAS PARA EL ADMINISTRADOR -->
                <?php if ( in_array($this->session->userdata('role'), array(1,2)) ) : ?>
                    <div class="text-right">
                        <a v-bind:href="`<?= URL_ADMIN . "files/edit/" ?>` + picture.id" class="btn btn-sm btn-light" target="_blank" title="Editar imagen">
                            <i class="fa fa-pencil-alt"></i>
                        </a>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#delete_modal" title="Eliminar imagen">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
            </div>

            
            <div class="comments">
                <div v-for="(comment, key) in comments" class="comment">
                    <div>
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
                        <p>
                            <small class="text-muted">{{ comment.created_at | ago }}</small>
                        </p>
                    </div>
        
                    <!-- Respuestas al comentario -->
                    <div v-for="(answer, answer_key) in comment.answers" class="answer">
                        <b>{{ answer.display_name }}</b> &middot; {{ answer.username }}
                        <br>
                        <p>{{ answer.comment_text }}</p>
                        <p v-if="APP_UID == answer.creator_id">
                            <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#delete_answer_modal" v-on:click="set_current_answer(key, answer_key)">
                                Delete
                            </button>
                        </p>
                        <p><small>{{ answer.created_at | ago }}</small></p>
                    </div>
                </div>
            </div>
        
            <!-- Siguiente etiqueta puesta para evitar error de no actualización de answers -->
            <pre class="d-none">{{ $data }}</pre>
        </div>
    </div>
    