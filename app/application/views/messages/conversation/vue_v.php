<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

// VueApplication
//-----------------------------------------------------------------------------

    new Vue({
        el: '#conversation_app',
        created: function(){
            this.get_conversations();
            setInterval(() => {this.refresh_messages()}, 100000);
        },
        data: {
            user_id: <?php echo $this->session->userdata('user_id'); ?>,
            conversations: [],
            conversation_id: <?= $conversation_id ?>,
            conversation_key: 0,
            messages: [],
            message: {
                id: 0,
                text: '',
                user_id: '<?php echo $this->session->userdata('user_id'); ?>',
                send_at: ''
            },
            current_message: {
                id: 0
            },
            message_key: 0,
            last_id: 0
        },
        methods: {
            //Cargar listado de conversaciones en las que participa el usuario
            get_conversations: function(){
                axios.post(url_api + 'messages/conversations/', $('#conversations_form').serialize())
                .then(response => {
                    this.conversations = response.data.conversations;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Marcar una conversación como la actual
            set_current: function(key){
                this.conversation_key = key;
                this.conversation_id = this.conversations[key].id;
                this.get_messages();
            },
            //Envía el mensaje escrito
            send_message: function(){
                new_id = Math.floor((Math.random() * 10000000) + 1)
                this.display_message(new_id);
                axios.post(url_api + 'messages/send_message/' + this.conversation_id, $('#message_form').serialize())
                .then(response => {
                    this.last_id = response.data.message_id;
                    console.log(response.data.message_id);
                })
                .catch(function (error) {
                    console.log(error);
                });
                $('#field-text').val('');   //Reiniciar casilla de mensaje
            },
            //Mostrar el mensaje enviado
            display_message: function(new_id){
                var new_message = {
                    id: new_id,
                    user_id: this.user_id,
                    text: $('#field-text').val()
                }
                this.messages.push(new_message);
                this.go_to_bottom();
            },
            //Cargar mensajes iniciales de la conversación
            get_messages: function(){
                axios.get(url_api + 'messages/get/' + this.conversation_id)
                .then(response => {
                    this.messages = response.data.messages;
                    this.set_last();
                    this.conversations[this.conversation_key].qty_unread = 0;   //0 Sin leer
                    this.go_to_bottom();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Cargar mensajes nuevos
            refresh_messages: function(){
                console.log('Buscando message_id > ' + this.last_id);
                axios.get(url_api + 'messages/get/' + this.conversation_id + '/' + this.last_id)
                .then(response => {
                    if ( response.data.qty_messages > 0 )
                    {
                        console.log('nuevos mensajes: ' + response.data.qty_messages );
                        //Agregando nuevos elementos
                        response.data.messages.forEach(element => { this.messages.push(element);});
                        this.set_last();
                    }
                    this.go_to_bottom();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //Establecer ID de último mensaje en la conversación (last_id)
            set_last: function(){
                if ( this.messages.length > 0 )
                {
                    var last_index = this.messages.length - 1;
                    this.last_id = this.messages[last_index].id;
                }
                this.go_to_bottom();
            },
            //Moverse a la parte inferior de la caja de mensajes
            go_to_bottom: function(){
                if ( this.messages.length > 0 )
                {
                    var pixeles = 250 * this.messages.length;
                    $("#conversation_messages").animate({scrollTop: pixeles}, 500);
                }
            },
            //Establecer un mensaje como mensaje actual o seleccionado
            set_current_message: function(message_key){
                this.current_message = this.messages[message_key];
                this.message_key = message_key;
                console.log(this.message.id);
            },
            //Eliminar un mensaje
            delete_element: function(){
                axios.get(url_api + 'messages/delete/' + this.current_message.id)
                .then(response => {
                    console.log(response.data.status)
                    if ( response.data.status )
                    {
                        this.messages.splice(this.message_key, 1);
                        toastr['success']('Message deleted');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>