<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q"
                    class="form-control"
                    placeholder="Search" autofocus title="Search"
                    v-model="filters.q" v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn btn-light btn-block" v-on:click="toggle_filters" title="Advanced search">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <?= form_dropdown('type', $options_project_type, $filters['type'], 'class="form-control" title="Filtrar por tipo"'); ?>
            </div>
            <label for="type" class="col-md-3 control-label col-form-label">Tipo</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="feeling" v-model="filters.feeling" class="form-control">
                    <option v-for="(option_feeling, key_feeling) in options_feeling" v-bind:value="key_feeling">{{ option_feeling }}</option>
                </select>
            </div>
            <label for="type" class="col-md-3 control-label col-form-label">Feeling</label>
        </div>

    </div>
</form>