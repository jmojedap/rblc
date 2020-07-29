<div class="full_width_title">
    <h2>Messages</h2>
</div>

<div class="page" id="conversation_app">
    <div class="alert alert-warning" v-show="conversations.length == 0">You have no messages yet</div>
    <div class="row" v-show="conversations.length > 0">
        <div class="col-md-4">
            <form class="mb-2" accept-charset="utf-8" method="POST" id="conversations_form" @submit.prevent="get_conversations">
                <input class="form-control" type="text" placeholder="Search" name="q">
            </form>
            <ul class="list-group conversations">
                <li class="list-group-item" v-for="(conversation, key) in conversations" v-on:click="set_current(key)"
                    v-bind:class="{active: conversation.id == conversation_id}">
                    <div class="media">
                        <img class="rounded-circle user_img mr-3" v-bind:src="conversation.url_image" alt="Image user profile" onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'">
                        <div class="media-body">
                            <h5 class="mt-0">{{ conversation.title }}</h5>
                            <small class="text-muted">{{ conversation.updated_at | ago }}</small>
                        </div>
                        <span class="badge badge-pill badge-warning" v-show="conversation.qty_unread > 0">{{ conversation.qty_unread }}</span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
            <div v-show="conversation_id > 0">
                <!-- Chat Box -->
                <div class="bg-white overflow-auto" id="conversation_messages">
                    <div class="text-center mb-2 d-none">
                        <button type="button" class="btn btn-white">
                            Load previous... {{ last_id }}
                        </button>
                    </div>
                    <div class="messages">
                        <div class="media message"
                            v-for="(message, key_message) in messages"
                            v-bind:class="{'message-right': message.user_id == user_id, 'message-active': message.id == current_message.id}"
                            v-bind:id="`message_` + message.id">
                            <img class="rounded-circle user_img mr-2" alt="User image"
                                v-bind:src="conversations[conversation_key].url_image"
                                v-if="message.user_id != user_id"
                                onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                                >
                            
                            <div class="media-body" v-on:click="set_current_message(key_message)">
                                <div class="chat-content" v-bind:title="message.sent_at">
                                    <p class="globe">{{ message.text }}</p>
                                    <div v-show="message.id == current_message.id">
                                        <p>
                                            <span class="float-right">
                                                <button class="a4" data-toggle="modal" data-target="#delete_modal">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </span>
                                            <small class="float-right mr-3 text-muted">
                                                {{ message.sent_at }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <img class="rounded-circle user_img ml-2" src="<?= $this->session->userdata('src_img') ?>" alt="" v-if="message.user_id == user_id">
                        </div>
                    </div>

                </div>
                <!-- End Chat Box -->

                <!-- Message Input-->
                <form class="mt-2" accept-charset="utf-8" method="POST" id="message_form" @submit.prevent="send_message">
                    <div class="message-input">
                        <textarea id="field-text" name="text" class="form-control mb-2" rows="2" required placeholder="Write a message here"></textarea>
                    </div>
                    <button class="btn btn-main w120p" type="submit">SEND</button>
                </form>
                <!-- End Message Input-->

            </div>
        </div>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<?php $this->load->view('messages/conversation/vue_v') ?>