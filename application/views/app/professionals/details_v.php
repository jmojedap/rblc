<div id="app_details">
    <table class="table bg-white">
        <tbody>
            <tr>
                <td width="25%" class="text-right">Tags</td>
                <td>
                    <span class="tag" v-for="(tag, tag_key) in tags">{{ tag.name }}</span>
                </td>
            </tr>
            <tr>
                <td width="25%" class="text-right">Professional services</td>
                <td>
                    <span class="tag" v-for="(service, service_key) in services">{{ service.name }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    new Vue({
        el: '#app_details',
        created: function(){
            //this.get_list();
        },
        data: {
            tags: <?= json_encode($tags->result()); ?>,
            services: <?= json_encode($professional_services->result()); ?>
        },
        methods: {
            
        }
    });
</script>