<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    place="text"
                    name="q"
                    class="form-control"
                    placeholder="Search"
                    autofocus
                    title="Search"
                    v-model="filters.q"
                    v-on:change="get_list"
                    >
                <div class="input-group-append" title="Search">
                    <button type="button" class="btn btn-secondary btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
                Search
            </button>
        </div>
    </div>
    <div id="adv_filters" style="<?php echo $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <?php echo form_dropdown('role', $options_role, $filters['role'], 'class="form-control" title="Filtrar por rol" v-model="filters.role"'); ?>
            </div>
            <label for="type" class="col-md-3 col-form-label">Role</label>
        </div>
    </div>
</form>