<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
});

// VueApp
//-----------------------------------------------------------------------------
var picture_app = new Vue({
    el: '#picture_app',
    created: function(){
        this.get_comments();
    },
    data: {
        picture: {id: '0', qty_likes: 0},
        user: {
            id: 0,
            display_name: '',
            url_thumbnail: ''
        },
        tags: [],
        like_status: 0,
        comments: [],
        current: {},
        current_key: 0,
        answers: [],
        answer_key: 0,
        form_values: {
            comment_text: '',
            parent_id: 0
        },
        APP_UID: APP_UID
    },
    methods: {
        alt_like: function(){
            axios.get(URL_API + 'files/alt_like/' + this.picture.id)
            .then(response => {
                this.like_status = response.data.like_status;
                if ( this.like_status == 1 ) {
                    this.picture.qty_likes++;
                } else {
                    this.picture.qty_likes--;
                }
            })
            .catch(function (error) { console.log(error) })
        },
        delete_element: function(){            
            axios.get(URL_API + 'files/delete/' + this.picture.id)
            .then(response => {
                this.hide_deleted_picture()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Imagen eliminada')
                }
            })
            .catch(function (error) { console.log(error) })
        },
        hide_deleted_picture: function(){
            $('#picture_' + this.picture.id).hide('slow');
        },
        //Gestión de comentarios
        get_comments: function(){
            axios.get(URL_API + 'comments/element_comments/1020/' + this.picture.id)
            .then(response => {
                this.comments = response.data.comments;
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function(){
            axios.post(URL_API + 'comments/save/1020/' + this.picture.id, $('#comment_form').serialize())
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    $('#modal_form').modal('hide');
                    if ( this.form_values.parent_id == 0 )
                    {
                        this.get_comments();
                    } else {
                        this.get_answers(this.current_key);
                    }
                    this.clean_form();
                }
            })
            .catch(function (error) { console.log(error) })
        },
        set_current: function(key){
            this.current_key = key;
            this.current = this.comments[key];
        },
        delete_comment: function(){
            axios.get(URL_API + 'comments/delete/' + this.current.id + '/' + this.picture.id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.get_comments();
                }
            })
            .catch(function (error) { console.log(error) })
        },
        clean_form: function(){
            this.form_values.comment_text = '';
            this.form_values.parent_id = 0;
        },
        set_parent: function(key){
            this.form_values.parent_id = this.comments[key].id;
        },
        //Preparar formulario para responder un comentario
        reply_comment: function(key){
            this.set_current(key);
            this.set_parent(key);
            $('#modal_form').modal('show');
        },
        get_answers: function(key){
            this.set_current(key);
            var parent_id = this.current.id;
            axios.get(URL_API + 'comments/element_comments/1020/' + this.picture.id + '/' + parent_id )
            .then(response => {
                this.answers = response.data.comments;
                this.comments[key].answers = response.data.comments;
                console.log(this.comments[key].answers);
            })
            .catch(function (error) { console.log(error) })
        },
        set_current_answer: function(key, answer_key){
            this.set_current(key);
            this.current_answer_key = answer_key;
        },
        delete_answer: function(){
            var answer_id = this.current.answers[this.current_answer_key].id;
            axios.get(URL_API + 'comments/delete/' + answer_id + '/' + this.picture.id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.get_answers(this.current_key);
                }
            })
            .catch(function (error) { console.log(error) })
        }
    }
});
</script>