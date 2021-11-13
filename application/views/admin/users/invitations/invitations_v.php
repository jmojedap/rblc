<div id="invitations_app">
    <div class="row">
        <div class="col-md-5">
            <?php $this->load->view('admin/users/invitations/search_form_v') ?>
            <table class="table bg-white">
                <thead>
                    <th width="10px"></th>
                    <th>Professional</th>
                    <th>Invitaciones</th>
                    <th width="10px"></th>
                </thead>
                <tbody>
                    <tr v-for="(user, key) in list" v-bind:class="{'table-info': current.id == user.id }">
                        <td>
                            <i class="far fa-circle text-danger" v-if="user.status == 0" title="Inactivo"></i>
                            <i class="fa fa-check-circle text-success" v-if="user.status == 1" title="Activo"></i>
                            <i class="fa fa-check-circle text-warning" v-if="user.status == 2" title="Registrado"></i>
                        </td>
                        <td>{{ user.display_name }}</td>
                        <td class="text-center">
                            <span class="text-muted" v-if="user.qty_invitations == 0">{{ user.qty_invitations }}</span>
                            <strong class="text-primary" v-else="user.qty_invitations > 0">{{ user.qty_invitations }}</strong>
                        </td>
                        <td>
                            <button class="a4" v-on:click="set_user(key)">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php $this->load->view('common/vue_range_pagination_v') ?>
        </div>
        <div class="col-md-7">
            <div v-show="current.id > 0">
                <form accept-charset="utf-8" method="POST" id="form_id" @submit.prevent="send_invitation()">
                    <fieldset v-bind:disabled="loading">
                        <div class="form-group row">
                            <label for="bcc" class="col-form-label col-md-3 text-right">Copia para:</label>
                            <div class="col-md-9">
                                <input
                                    name="text_message" type="email" class="form-control"
                                    v-model="form_values.bcc"
                                >
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="text_message" class="col-form-label col-md-3 text-right">Texto mensaje:</label>
                            <div class="col-md-9">
                                <textarea
                                    name="text_message" type="text" class="form-control" rows="2"
                                    required
                                    title="Texto" placeholder="Texto"
                                    v-model="form_values.text_message"
                                ></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3 offset-md-3">
                                <button class="btn btn-success btn-block" type="submit">Enviar E-mail</button>
                            </div>
                        </div>
                    <fieldset>
                </form>
                <div class="card mb-2">
                    <div class="card-body">
                        <p>
                            <span class="text-muted">Para:</span> <strong>{{ current.email }}</strong>
                        </p>
                        <?php $this->load->view('admin/users/invitations/message_v') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <?php $this->load->view('admin/users/invitations/summary_v') ?>
    
</div>

<?php $this->load->view($this->views_folder . 'invitations/vue_v') ?>