<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow();
});

// Vue App
//-----------------------------------------------------------------------------
var post_comments_app = new Vue({
    el: '#post_comments_app',
    created: function(){
        this.get_comments();
    },
    data: {
        table_id: '<?= $table_id ?>',
        element_id: '<?= $element_id ?>',
        comments: [],
        current: {id: 0},
        current_key: 0,
        answers: [],
        answer_key: 0,
        form_values: { comment_text: '', parent_id: 0 },
        APP_UID: APP_UID
    },
    methods: {
        get_comments: function(){
            axios.get(URL_API + 'comments/element_comments/' + this.table_id + '/' + this.element_id)
            .then(response => {
                this.comments = response.data.comments;
            })
            .catch(function (error) { console.log(error) })
        },
        send_form: function(){
            axios.post(URL_API + 'comments/save/' + this.table_id + '/' + this.element_id, $('#comment_form').serialize())
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
            axios.get(URL_API + 'comments/delete/' + this.current.id + '/' + this.element_id)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.get_comments();
                }
            })
            .catch(function (error) { console.log(error) })
        },
        clean_form: function(){
            this.current = {id: 0}
            this.form_values.comment_text = ''
            this.form_values.parent_id = 0
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
            axios.get(URL_API + 'comments/element_comments/' + this.table_id + '/' + this.element_id + '/' + parent_id )
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
            axios.get(URL_API + 'comments/delete/' + answer_id + '/' + this.element_id)
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