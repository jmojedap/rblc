<div id="tags_app">
    <table class="table bg-white">
        <thead>
            <th>id</th>
            <th>tag name</th>
            <th>slug</th>
        </thead>
        <tbody>
            <tr v-for="(tag, key) in tags">
                <td>{{ tag.tag_id }}</td>
                <td>{{ tag.name }}</td>
                <td>{{ tag.slug }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#tags_app',
        created: function(){
            //this.get_list();
        },
        data: {
            tags: <?= json_encode($tags->result()) ?>
        },
        methods: {
            /*get_list: function(){
                
            },*/
        }
    });
</script>