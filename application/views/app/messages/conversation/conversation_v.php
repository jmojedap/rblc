<?php $this->load->view('assets/autosize') ?>

<div class="full_width_title only-lg">
    <h2>Messages</h2>
</div>

<div class="page" id="conversation_app">
    <div class="alert alert-warning" v-show="conversations.length == 0">You have no messages yet</div>
    <div class="row" v-show="conversations.length > 0">
        <div class="col-md-4" v-bind:class="conversations_class()">
            <form class="mb-2" accept-charset="utf-8" method="POST" id="conversations_form" @submit.prevent="get_conversations">
                <input class="form-control" type="text" placeholder="Search" name="q">
            </form>
            <ul class="conversations">
                <li class="conversations-item" v-for="(conversation, key) in conversations" v-on:click="set_current(key)"
                    v-bind:class="{active: conversation.id == conversation_id}">
                    <div class="media">
                        <img class="rounded-circle user_img mr-3"
                            v-bind:src="conversation.url_image"
                            alt="Image user profile"
                            onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                            >
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
                <div class="p-1 mb-1" style="background-color: #f1f1f1;">
                    <div class="d-flex">
                        <button class="btn btn-light mr-1 only-sm" v-on:click="unset_current()">
                            <i class="fa fa-arrow-left"></i>
                        </button>
                        <div>
                            <img class="rounded-circle user_img mr-3 w40p"
                                v-bind:src="conversations[con_key].url_image"
                                alt="Image user profile"
                                onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                                >
                        </div>
                        <div>
                            <h5 class="mb-0">{{ conversations[con_key].title }}</h5>
                            <small class="text-muted">{{ conversations[con_key].updated_at | ago }}</small>
                        </div>
                    </div>
                </div>

                <!-- Chat Box -->
                <div class="bg-white overflow-auto" id="conversation_messages">
                    <div class="text-center mb-2 d-none">
                        <button type="button" class="btn btn-white">
                            Load previous...
                        </button>
                    </div>
                    <div class="messages">
                        <div class="media message"
                            v-for="(message, mk) in messages"
                            v-bind:class="{'message-right': message.user_id == user_id, 'message-active': message.id == current_message.id}"
                            v-bind:id="`message_` + message.id">
                            <img class="rounded-circle user_img mr-2" alt="User image"
                                v-bind:src="conversations[con_key].url_image"
                                v-if="message.user_id != user_id"
                                onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                                >
                            <div class="media-body" v-on:click="set_current_message(mk)">
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
                                                {{ message.sent_at }} &middot; {{ message.sent_at | ago }}  
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <img class="rounded-circle user_img ml-2"
                                src="<?= $this->session->userdata('picture') ?>" alt="Imagen usuario" onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                                v-if="message.user_id == user_id"
                                >
                        </div>
                    </div>

                </div>
                <!-- End Chat Box -->

                <!-- Message Input-->
                <form class="mt-2" accept-charset="utf-8" method="POST" id="message_form" @submit.prevent="send_message">
                    <div class="d-flex">
                        <div class="message-input flex-grow-1 pr-1">
                            <input id="field-text" name="text" class="form-control" required placeholder="Write a message here">
                        </div>
                        <div>
                            <button class="btn btn-main" type="submit">SEND</button>
                        </div>
                    </div>
                </form>
                <!-- End Message Input-->

            </div>
        </div>
    </div>
    <?php $this->load->view('app/messages/conversation/modal_delete_v') ?>
</div>

<?php $this->load->view('app/messages/conversation/vue_v') ?>