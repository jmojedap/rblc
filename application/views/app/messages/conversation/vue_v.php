<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
    });

// VueApp
//-----------------------------------------------------------------------------

    new Vue({
        el: '#conversation_app',
        created: function(){
            this.get_conversations()
            this.update_qty_unread()
            setInterval(() => {this.update_qty_unread()}, 60 * 1000) //Latencia verificando si hay nuevos mensajes
        },
        data: {
            user_id: <?= $this->session->userdata('user_id'); ?>,
            conversations: [],
            conversation_id: <?= $conversation_id ?>,
            con_key: 0, //Conversatión Key
            messages: [],
            message: {
                id: 0, text: '',
                user_id: '<?= $this->session->userdata('user_id'); ?>',
                send_at: ''
            },
            qty_unread: 0,
            current_message: { id: 0 },
            message_key: 0,
        },
        methods: {
            update_qty_unread: function(){
                axios.get(url_api + 'messages/qty_unread/')
                .then(response => {
                    this.qty_unread = response.data
                    if ( this.qty_unread > 0 ) this.get_conversations()
                })
                .catch(function(error) { console.log(error) })
            },
            //Cargar listado de conversaciones en las que participa el usuario
            get_conversations: function(){
                axios.post(url_api + 'messages/conversations/', $('#conversations_form').serialize())
                .then(response => {
                    this.conversations = response.data.conversations
                    this.get_messages(this.con_key)
                })
                .catch(function (error) { console.log(error) })
            },
            //Marcar una conversación como la actual
            set_current: function(key){
                this.con_key = key
                this.conversation_id = this.conversations[key].id
                this.get_messages(key)
            },
            unset_current: function(){
                this.con_key = 0
                this.conversation_id = 0
                this.messages = []
                console.log(this.conversation_id)
            },
            // Clase para el listado de conversaciones
            conversations_class: function(){
                var conversations_class = ''
                if ( screen.width < 768 && this.conversation_id > 0 ) {
                    conversations_class = 'd-none'
                }
                return conversations_class
            },
            //Envía el mensaje escrito
            send_message: function(){
                var key = this.con_key
                var new_id = Math.floor((Math.random() * 10000000) + 1)
                this.display_message(key, new_id)
                axios.post(url_api + 'messages/send_message/' + this.conversation_id, $('#message_form').serialize())
                .then(response => {
                    this.conversations[key].last_id = response.data.message_id
                    console.log(response.data.message_id)
                })
                .catch(function (error) { console.log(error) })
                $('#field-text').val('')   //Reiniciar casilla de mensaje
            },
            //Mostrar el mensaje enviado
            display_message: function(key, new_id){
                var new_message = {
                    id: new_id,
                    user_id: this.user_id,
                    text: $('#field-text').val()
                }
                this.conversations[key].messages.push(new_message)
                this.go_to_bottom(key)
            },
            //Requerir y cargar mensajes de la conversación key
            get_messages: function(key){
                var last_id = this.conversations[key].last_id
                axios.get(url_api + 'messages/get/' + this.conversation_id + '/' + last_id)
                .then(response => {
                    //Cargar mensajes en la conversación
                    response.data.messages.forEach(element => {
                        this.conversations[key].messages.push(element)
                    })

                    //Mensajes mostrados
                    this.messages = this.conversations[key].messages

                    this.set_last(key)
                    this.conversations[key].qty_unread = 0   //0 Sin leer en la conversación
                    this.qty_unread = this.qty_unread - response.data.messages.length
                    if ( this.qty_unread < 0 ) this.qty_unread = 0
                })
                .catch(function (error) { console.log(error) })
            },
            //Establecer ID de último mensaje en la conversación (last_id)
            set_last: function(key){
                if ( this.conversations[key].messages.length > 0 )
                {
                    var last_index = this.conversations[key].messages.length - 1
                    this.conversations[key].last_id = this.conversations[key].messages[last_index].id
                }
                this.go_to_bottom(key)
            },
            //Moverse a la parte inferior de la caja de mensajes
            go_to_bottom: function(key){
                if ( this.conversations[key].messages.length > 0 )
                {
                    var pixeles = 250 * this.conversations[key].messages.length
                    $("#conversation_messages").animate({scrollTop: pixeles}, 200)
                }
            },
            //Establecer un mensaje como mensaje actual o seleccionado
            set_current_message: function(message_key){
                var key = this.con_key
                this.current_message = this.conversations[key].messages[message_key];
                this.message_key = message_key;
                console.log(this.message.id);
            },
            //Eliminar un mensaje
            delete_element: function(){
                var key = this.con_key
                axios.get(url_api + 'messages/delete/' + this.current_message.id)
                .then(response => {
                    console.log(response.data.status)
                    if ( response.data.status )
                    {
                        this.conversations[key].messages.splice(this.message_key, 1)
                        toastr['success']('Message deleted')
                    }
                })
                .catch(function (error) { console.log(error) })
            },
        }
    });
</script>