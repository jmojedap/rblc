<p>
    Subscribed emails for newsletter: <?= $subscribers->num_rows() ?>
</p>

<div id="subscribers_app">
    <table class="table bg-white">
        <thead>
            <th>E-mail</th>
            <th>Related user</th>
            <th width="10px"></th>
        </thead>
        <tbody>
            <tr v-for="(subscriber, subscriber_key) in subscribers">
                <td>{{ subscriber.email }}</td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "users/profile/" ?>` + subscriber.creator_id" class="">
                        {{ subscriber.creator_name }}
                    </a>
                </td>
                <td>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(subscriber_key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
    new Vue({
        el: '#subscribers_app',
        created: function(){
            //this.get_list();
        },
        data: {
            subscribers: <?= json_encode($subscribers->result()) ?>,
            subscriber_key: 0,
            curr_subscriber: {}
        },
        methods: {
            set_current: function(subscriber_key){
                this.subscriber_key = subscriber_key;
                this.curr_subscriber = this.subscribers[subscriber_key];
            },
            delete_element: function(){
                axios.get(URL_APP + 'posts/delete/' + this.curr_subscriber.id)
                .then(response => {
                    if ( response.data.status == 1 ) {
                        window.location = URL_APP + 'users/newsletter_subscribers';
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },   
        }
    });
</script>
