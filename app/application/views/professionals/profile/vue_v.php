<script>
// Vue App
//-----------------------------------------------------------------------------

var user_profile = new Vue({
    el: '#user_profile',
    created: function(){
        this.get_images();
        this.get_projects();
        this.get_social_links();
    },
    data: {
        user_id: '<?= $row->id ?>',
        follow_status: '<?= $follow_status ?>',
        images: [],
        key: 0,
        picture: {
            row: {description: '', url: ''},
            tags: {}
        },
        social_links: {},
        section: 'images',
        projects: [],
    },
    methods: {
        get_images: function(){
            axios.get(url_api + 'professionals/get_images/' + this.user_id)
            .then(response => {
                this.images = response.data.images;
            })
            .catch(function (error) {
                console.log(error);
            });  
        },
        set_key: function(key){
            this.key = Pcrn.limit_between(key, 0, this.images.length - 1);
            this.get_details();
        },
        //Obtener detalles de Picture
        get_details: function(){
            axios.get(url_api + 'pictures/get_details/' + this.images[this.key].id)
            .then(response => {
                this.picture = response.data;
            })
            .catch(function (error) {
                console.log(error);
            });  
        },
        get_social_links: function(){
            axios.get(url_api + 'users/get_social_links/' + this.user_id)
            .then(response => {
                this.social_links = response.data.list;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        alt_follow: function(){
            axios.get(url_api + 'users/alt_follow/' + this.user_id)
            .then(response => {
                this.follow_status = response.data.status;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        create_conversation: function(){
            axios.get(url_api + 'messages/create_conversation/' + this.user_id)
            .then(response => {
                console.log(response.data);
                if ( response.data.conversation_id > 0 ) {
                    window.location = url_app + 'messages/conversation/' + response.data.conversation_id;
                }
            })
            .catch(function (error) {
                console.log(error);
            });  
        },
        set_section: function(new_section){
            this.section = new_section
        },
        get_projects: function(){
            let form_data = new FormData
            form_data.append('u', this.user_id)
            axios.post(url_api + 'projects/get/', form_data)
            .then(response => {
                this.projects = response.data.list 
            })
            .catch( function(error) {console.log(error)} )
        },
    }
});
</script>