
<script>
// Variables
//-----------------------------------------------------------------------------

// Filters
//-----------------------------------------------------------------------------

// App
//-----------------------------------------------------------------------------

    new Vue({
        el: '#app_explore',
        created: function(){
            this.get_list();
        },
        data: {
            cf: '<?= $cf; ?>',
            controller: '<?= $controller; ?>',
            num_page: 1,
            max_page: 1,
            search_num_rows: 0,
            list: [],
            element: [],
            element_key: 0,
            filters: [],
            app_uid: app_uid
        },
        methods: {
            get_list: function(){
                axios.post(url_api + this.controller + '/get/' + this.num_page + '/?u=' + this.app_uid, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, url_app + this.cf + this.num_page +'/?' + response.data.str_filters);
                    this.all_selected = false;
                    this.selected = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            sum_page: function(sum){
                this.num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page);
                this.get_list();
            },
            set_current: function(key){
                this.element_key = key;
                this.element = this.list[key];
            },
            set_app_q: function(){
                app_q = this.filters.q;
                this.num_page = 1;
                $('#app_q').val(this.filters.q);
                this.get_list();  
            },
            delete_element: function(){
                axios.get(url_api + 'posts/delete/' + this.element.id)
                .then(response => {
                    console.log(response.data);
                    if ( response.data.qty_deleted >= 0 )
                    {
                        toastr['info']('Ideabook deleted');
                        $('#row_' + this.element.id).addClass('table-danger');
                        $('#row_' + this.element.id).hide('slow');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },  
        }
    });
</script>