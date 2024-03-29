<div id="app_explore">
    <div class="row">
        <div class="col-md-5">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>

        <div class="col-md-3">
            <a href="<?= URL_ADMIN . "{$controller}/export/?{$str_filters}" ?>" class="btn btn-light only-lg d-none" v-bind:title="`Exportar ` + search_num_rows + ` registros encontrados a Excel`">
                <i class="fa fa-download"></i>
            </a>
            <button class="btn btn-warning"
                title="Eliminar elementos seleccionados"
                data-toggle="modal" data-target="#modal_delete"
                v-show="selected.length > 0"
                >
                <i class="fa fa-trash"></i>
            </button>
        </div>
        
        <div class="col-md-4 mb-2 text-right">
            <a class="btn text-muted">
                <strong class="text-primary">{{ search_num_rows }}</strong> resultados &middot; Pág {{ num_page }} / {{ max_page }}
            </a>
            <?php $this->load->view('common/vue_pagination_v'); ?>
        </div>
    </div>

    <?php $this->load->view($views_folder . 'list_v'); ?>
    <?php $this->load->view($views_folder . 'detail_v'); ?>
    <?php $this->load->view('common/modal_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>