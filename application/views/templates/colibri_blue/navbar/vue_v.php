<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

// VueApp
//-----------------------------------------------------------------------------
var navbar_app = new Vue({
    el: '#navbar_app',
    created: function(){
        this.get_qty_unread_notifications()
    },
    data: {
        qty_unread_notifications: 0,
        notifications: [],
        loading: false,
    },
    methods: {
        get_qty_unread_notifications: function(){
            axios.get(url_api + 'app/qty_unread_notifications/')
            .then(response => {
                this.qty_unread_notifications = response.data.qty_unread_notifications
            })
            .catch(function(error) { console.log(error) })
        },
        get_notifications: function(){
            axios.get(url_api + 'app/get_notifications/')
            .then(response => {
                this.notifications = response.data.notifications
            })
            .catch(function(error) { console.log(error) })
        },
        open_notification: function(notification_id){
            axios.get(url_api + 'app/open_notification/' + notification_id)
            .then(response => {
                console.log(response.data)
                if ( response.data.url_destination ) {
                    window.location = response.data.url_destination
                }
            })
            .catch(function(error) { console.log(error) })
        },
        //String, link al que debe dirigirse al hacer clic en la alerta de notificación
        /*alert_link: function(notification){
            var alert_link = '#'
            if ( notification.alert_type == 10 ) {
                alert_link = url_app + 'professionals/profile/' + notification.element_id;
            } else if ( notification.alert_type == 20 ) {
                alert_link = url_app + 'messages/conversation/';
            } else if ( notification.alert_type == 30 ) {
                //events.related_2 => ID elemento comentado
                alert_link = url_app + 'pictures/details/' + notification.related_2;
                //Verificar tabla_id (events.related_1), tabla posts (2000)
                if ( notification.related_1 == 2000 ) {
                    alert_link = url_app + 'projects/info/' + notification.related_2;
                }
            }
            return alert_link;
        },*/
        //String, clase de FowtAwesome para icono de notificación
        alert_icon_class: function(notification){
            var alert_icon_class = 'far fa-user'
            if ( notification.alert_type == 10 ) {
                alert_icon_class = 'far fa-user'
            } else if ( notification.alert_type == 20) {
                alert_icon_class = 'far fa-envelope'
            } else if ( notification.alert_type == 30) {
                alert_icon_class = 'far fa-comment'
            }
            return alert_icon_class;
        },
    }
})
</script>