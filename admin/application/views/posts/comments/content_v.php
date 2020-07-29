    <div class="center_box_750 mb-2">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modal_form" v-on:click="clean_form">
            Agregar comentario
        </button>
    </div>
    <div class="card center_box_750 mb-2">
        <div class="card-body comments">
            <div v-for="(comment, key) in comments" class="comment">
                <div>
                    <div class="float-right">
                        <button class="a4" v-on:click="reply_comment(key)" title="Responder">
                            <i class="fas fa-reply"></i>
                        </button>
                        <button class="a4" data-toggle="modal" data-target="#delete_comment_modal" v-on:click="set_current(key)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <b>{{ comment.display_name }}</b> &middot; {{ comment.username }} &middot; {{ comment.created_at | ago }}
                    <br>
                    <p>{{ comment.comment_text }}</p>
                    <p>
                        <button class="btn btn-sm btn-light" v-on:click="get_answers(key)">
                            <i class="far fa-comment"></i> {{ comment.qty_comments }}
                        </button>
                    </p>
                </div>

                <!-- Respuestas al comentario -->
                <div v-for="(answer, answer_key) in comment.answers" class="answer">
                    <div class="float-right">
                        <button class="a4" data-toggle="modal" data-target="#delete_answer_modal" v-on:click="set_current_answer(key, answer_key)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                    <b>{{ answer.display_name }}</b> &middot; {{ answer.username }} &middot; {{ answer.created_at | ago }}
                    <br>
                    <p>{{ answer.comment_text }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Siguiente etiqueta puesta para evitar error de no actualización de answers -->
    <pre class="d-none">{{ $data }}</pre>