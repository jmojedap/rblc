<div id="app_explore">
    <?php $this->load->view($views_folder . 'menu_v') ?>
    <div class="row">
        <div class="col-md-6">
            <p class="text-muted">{{ search_num_rows }} results &middot; Page {{ num_page }}/{{ max_page }}</p>
        </div>
        <div class="col-md-3">
            <p v-show="filters.q">
                Searching
                <b class="text-colibri-1" v-show="filters.q">{{ filters.q }}</b>
                in <b class="text-colibri-1">{{ tag.text }}</b> 
            </p>
        </div>
        
        <div class="col-md-3 mb-2">
            <?php $this->load->view('common/vue_pagination_2_v'); ?>
        </div>
    </div>

    <div>
        <?php $this->load->view($views_folder . 'list_v'); ?>
    </div>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>