<script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>

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
                in <b class="text-colibri-1">{{ category.text }}</b> 
            </p>
        </div>
        
        <div class="col mb-2">
            <?php $this->load->view('common/vue_pagination_2_v'); ?>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-4">
            <select name="descriptor" v-model="filters.descriptor" class="form-control" v-on:change="get_list">
                <option v-for="(option_descriptor, key_descriptor) in options_descriptor" v-bind:value="key_descriptor">{{ option_descriptor }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="style" v-model="filters.style" class="form-control" v-on:change="get_list">
                <option v-for="(option_style, key_style) in options_style" v-bind:value="key_style">{{ option_style }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="feeling" v-model="filters.feeling" class="form-control" v-on:change="get_list">
                <option v-for="(option_feeling, key_feeling) in options_feeling" v-bind:value="key_feeling">{{ option_feeling }}</option>
            </select>
        </div>
    </div>

    <div id="elements_table">
        <?php $this->load->view($views_folder . 'list_v'); ?>
        <?php $this->load->view($views_folder . 'detail_v'); ?>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <p class="text-muted">{{ search_num_rows }} results &middot; Page {{ num_page }}/{{ max_page }}</p>
        </div>
        <div class="col-md-3">
            <p v-show="filters.q">
                Searching
                <b class="text-colibri-1" v-show="filters.q">{{ filters.q }}</b>
                in <b class="text-colibri-1">{{ category.text }}</b> 
            </p>
        </div>
        
        <div class="col-md-3 mb-2">
            <?php $this->load->view('common/vue_pagination_2_v'); ?>
        </div>
    </div>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>