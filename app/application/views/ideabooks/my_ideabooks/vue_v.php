
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
            cf: '<?php echo $cf; ?>',
            controller: '<?php echo $controller; ?>',
            num_page: 1,
            max_page: 1,
            search_num_rows: 0,
            list: [],
            element: [],
            filters: [],
            su_id: su_id
        },
        methods: {
            get_list: function(){
                axios.post(url_api + this.controller + '/get/' + this.num_page + '/?u=' + this.su_id, $('#search_form').serialize())
                .then(response => {
                    this.list = response.data.list;
                    this.max_page = response.data.max_page;
                    this.search_num_rows = response.data.search_num_rows;
                    history.pushState(null, null, app_url + this.cf + this.num_page +'/?' + response.data.str_filters);
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
                this.element = this.list[key];
            },
            set_app_q: function(){
                app_q = this.filters.q;
                this.num_page = 1;
                $('#app_q').val(this.filters.q);
                this.get_list();  
            },
        }
    });
</script>