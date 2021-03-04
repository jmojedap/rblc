<div id="app_explore">
    <?php $this->load->view($views_folder . 'menu_v') ?>
    <div class="row">
        <div class="col-md-6">
            <p v-show="filters.q">
                Searching
                "<b class="text-colibri-1" v-show="filters.q">{{ filters.q }}</b>"
                in <span class="badge-colibri">{{ category.text }}</span> 
            </p>
        </div>
        <div class="col-md-6">
            <p class="text-muted text-right">{{ search_num_rows }} results</p>
        </div>
    </div>

    <div id="elements_table">
        <?php $this->load->view($views_folder . 'list_v'); ?>
        <?php $this->load->view($views_folder . 'detail_v'); ?>
    </div>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>