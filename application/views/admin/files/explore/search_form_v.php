<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q" class="form-control"
                    placeholder="Buscar" title="Buscar"
                    autofocus
                    v-model="filters.q" v-on:change="get_list"
                    >
                <div class="input-group-append" title="Buscar">
                    <button type="button" class="btn" title="Mostrar filtros para búsqueda avanzada"
                        v-on:click="toggle_filters"
                        v-bind:class="{'btn-primary': display_filters, 'btn-light': !display_filters }"
                        >
                        <i class="fas fa-chevron-down" v-show="!display_filters"></i>
                        <i class="fas fa-chevron-up" v-show="display_filters"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>" class="mb-2">
        <div class="form-group row">
            <div class="col-md-9">
                <input name="fe2" type="text" class="form-control" v-model="filters.fe2">
            </div>
            <label for="fe2" class="col-md-3 col-form-label">ID File</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="cat_1" v-model="filters.cat_1" class="form-control">
                    <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
                </select>
            </div>
            <label for="cat_1" class="col-md-3 col-form-label">Categoría</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="fe1" v-model="filters.fe1" class="form-control">
                    <option v-for="(option_checked, key_checked) in options_checked" v-bind:value="key_checked">{{ option_checked }}</option>
                </select>
            </div>
            <label for="fe1" class="col-md-3 col-form-label">Estado revisión</label>
        </div>
        <div class="form-group row">
            <div class="col-md-5">
                <input name="d1" type="date" class="form-control" v-model="filters.d1">
            </div>
            <div class="col-md-4">
                <input name="d2" type="date" class="form-control" v-model="filters.d2">
            </div>
            <label for="fe1" class="col-md-3 col-form-label">Revisado entre fechas</label>
        </div>
        <div class="mb-3 row">
            <div class="col-md-9">
                <select name="fe3" v-model="filters.fe3 " class="form-control">
                    <option value="">[ Todos ]</option>
                    <option v-for="optionGroup1 in arrGroup1" v-bind:value="optionGroup1.value">{{ optionGroup1.name }}</option>
                </select>
            </div>
            <label for="fe3" class="col-md-3 col-form-label">Seleccionada para Home</label>
        </div>

        <!-- Botón ejecutar y limpiar filtros -->
        <div class="form-group row">
            <div class="col-md-9 text-right">
                <button class="btn btn-warning w120p" v-on:click="remove_filters" type="button" v-show="active_filters">Quitar filtros</button>
                <button class="btn btn-primary w120p" type="submit">Buscar</button>
            </div>
        </div>
    </div>
</form>