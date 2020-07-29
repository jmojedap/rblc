<script>
    var form_values = {
        post_name: '<?= $row->post_name ?>',
        excerpt: '<?= $row->excerpt ?>'
    };
            
    new Vue({
        el: '#edit_ideabook',
        data: {
            form_values: form_values,
            ideabook_id: <?= $row->id ?>
        },
        methods: {
            send_form: function() {
                axios.post(url_api + 'ideabooks/update/' + this.ideabook_id, $('#edit_form').serialize())
                .then(response => {
                    if ( response.data.status == 1 )
                    {
                        toastr['success']('Ideabook updated');
                    }
                })
                .catch(function (error) {
                     console.log(error);
                });
            }
        }
    });
</script>