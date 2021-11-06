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
        row: <?= json_encode($row) ?>,
        user: {
            id: <?= $user->id ?>,
            display_name: '<?= $user->display_name ?>',
            url_thumbnail: '<?= $user->url_thumbnail ?>'
        },
        tags: <?= json_encode($tags->result) ?>,
        like_status: <?= $like_status ?>,
        picture_id: '<?= $row->id ?>',
        comments: [],
        current: {},
        current_key: 0,
        answers: [],
        answer_key: 0,
        form_values: {
            comment_text: '',
            parent_id: 0
        },
        app_uid: app_uid
    },
    methods: {
        alt_like: function(){
            axios.get(url_api + 'files/alt_like/' + this.row.id)
            .then(response => {
                this.like_status = response.data.like_status;
                if ( this.like_status == 1 ) {
                    this.row.qty_likes++;
                } else {
                    this.row.qty_likes--;
                }
            })
            .catch(function (error) { console.log(error) })
        },
        //Gestión de comentarios
        get_comments: function(){
            axios.get(url_api + 'comments/element_comments/1020/' + this.picture_id)
            .then(response => {
                this.comments = response.data.comments;
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function(){
            axios.post(url_api + 'comments/save/1020/' + this.picture_id, $('#comment_form').serialize())
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
            axios.get(url_api + 'comments/delete/' + this.current.id + '/' + this.picture_id)
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
            axios.get(url_api + 'comments/element_comments/1020/' + this.picture_id + '/' + parent_id )
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
            axios.get(url_api + 'comments/delete/' + answer_id + '/' + this.picture_id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.get_answers(this.current_key);
                }
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>