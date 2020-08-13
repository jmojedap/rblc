<div id="projects_app">
    <table class="table bg-white">
        <thead>
            <th width="50px"></th>
            <th>Project/Product</th>
            <th width="10px"></th>
        </thead>
        <tbody>
            <tr v-for="(project, key) in list">
                <th><img
                    v-bind:src="project.url_thumbnail"
                    class="rounded w75p"
                    alt="Project image"
                    onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                ></th>
                <td>{{ project.title }}</td>
                <td>
                    <button class="a4" type="button" @click="set_current(key)" data-toggle="modal" data-target="#delete_modal">
                        <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
    new Vue({
        el: '#projects_app',
        created: function(){
            this.get_list();
        },
        data: {
            ideabook_id: <?= $row->id ?>,
            list: [],
            current: {},
            key: 0,
        },
        methods: {
            get_list: function(){
                axios.get(url_api + 'ideabooks/get_projects/' + this.ideabook_id)
                .then(response => {
                    this.list = response.data.list;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            set_current: function(key){
                this.key = key;
                this.current = this.list[key];
            },
            delete_element: function(){
                //this.list.splice(this.key,1);
                axios.get(url_api + 'posts/delete_meta/' + this.ideabook_id + '/' + this.current.meta_id)
                .then(response => {
                    this.list.splice(this.key,1);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    });
</script>