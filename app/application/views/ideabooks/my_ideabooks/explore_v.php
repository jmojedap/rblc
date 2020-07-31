<div id="app_explore">
    <div class="row">
        <div class="col-md-6">
            <p class="text-muted">{{ search_num_rows }} results &middot; Page {{ num_page }}/{{ max_page }}</p>
        </div>
        <div class="col-md-3">
            
        </div>
        
        <div class="col mb-2">
            <?php $this->load->view('common/vue_pagination_2_v'); ?>
        </div>
    </div>

    <div id="elements_table">
        <?php $this->load->view($views_folder . 'list_v'); ?>
        <?php $this->load->view($views_folder . 'detail_v'); ?>
    </div>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>