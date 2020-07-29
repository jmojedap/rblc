<div class="page" id="conversation_app">
    <!-- Message Sidebar -->
    <div class="page-aside">
        <div class="page-aside-switch">
            <i class="icon wb-chevron-left" aria-hidden="true"></i>
            <i class="icon wb-chevron-right" aria-hidden="true"></i>
        </div>
        <div class="page-aside-inner">
            <div class="input-search">
                <button class="input-search-btn" type="submit">
                    <i class="icon wb-search" aria-hidden="true"></i>
                </button>
                <form>
                    <input class="form-control" type="text" placeholder="Buscar" name="">
                </form>
            </div>

            <div class="app-message-list page-aside-scroll">
                <div data-role="container">
                    <div data-role="content">
                        <ul class="list-group">
                            <li
                                class="list-group-item"
                                v-for="(conversation, key) in conversations"
                                v-on:click="set_current(key)"
                                v-bind:class="{active: conversation.id == conversation_id}"
                                >
                                <div class="media">
                                    <div class="pr-20">
                                        <a class="avatar avatar-online" href="javascript:void(0)">
                                            <img
                                                class="img-fluid"
                                                src="<?php echo URL_IMG ?>users/sm_user.png"
                                                alt="..."><i></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-5">{{ conversation.title }}</h5>
                                        <span class="media-time">15 sec ago</span>
                                    </div>
                                    <div class="pl-20">
                                        <span class="badge badge-pill badge-danger">3</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="media">
                                    <div class="pr-20">
                                        <a class="avatar avatar-online" href="javascript:void(0)">
                                            <img
                                                class="img-fluid"
                                                src="<?php echo URL_IMG ?>users/sm_user.png"
                                                alt="..."><i></i>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-5">{{ last_id }}</h5>
                                        <span class="media-time">15 sec ago</span>
                                    </div>
                                    <div class="pl-20">
                                        <span class="badge badge-pill badge-danger">3</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Message Sidebar -->
    <div class="page-main">
        <!-- Chat Box -->
        <div class="app-message-chats" id="app_message_chats">
            <button type="button" id="historyBtn" class="btn btn-round btn-outline btn-default">
                Anteriores... {{ last_id }}
            </button>
            <div class="chats">
                <div
                    v-for="(message, key_message) in messages"
                    class="chat"
                    v-bind:class="{'chat-left': message.user_id != user_id}"
                    v-bind:id="`message_` + message.id"
                    >
                    <div class="chat-avatar">
                        <a class="avatar" data-toggle="tooltip" href="#" data-placement="right" title="">
                            <img src="<?php echo URL_IMG ?>users/sm_user.png" alt="June Lane" style="width: 35px;">
                        </a>
                    </div>
                    <div class="chat-body" v-on:click="set_current_message(key_message)">
                        <div class="chat-content" v-bind:title="message.sent_at">
                            <p>{{ message.text }}</p>
                            <p style="color: #b3e5fc; font-size: 0.8em; text-align: right" v-show="message.id == current_message.id">
                                {{ message.sent_at }}
                            </p>
                            <p style="color: #b3e5fc; font-size: 0.8em; text-align: right" v-show="message.id == current_message.id">
                                <button class="btn btn-light btn-sm" v-on:click="delete_message(key_message)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End Chat Box -->

        <!-- Message Input-->
        <form class="app-message-input" accept-charset="utf-8" method="POST" id="message_form" @submit.prevent="send_message">
            <div class="message-input">
                <textarea
                    id="field-text"
                    name="text"
                    class="form-control"
                    rows="1"
                    required
                ></textarea>
            </div>
            <button class="message-input-btn btn btn-primary" type="submit">ENVIAR</button>
        </form>
        <!-- End Message Input-->

    </div>
</div>

<?php $this->load->view('messages/conversation/vue_v') ?>