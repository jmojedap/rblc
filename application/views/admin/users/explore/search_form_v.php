<?php
    $filters_style = ( strlen($str_filters) > 0 ) ? '' : 'display: none;' ;
?>

<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group mb-2">
                <input
                    type="text" name="q" class="form-control"
                    placeholder="Buscar..." autofocus
                    title="Search"
                    v-model="filters.q" v-on:change="get_list"
                    >
                <div class="input-group-append" title="Search">
                    <button type="button" class="btn btn-light btn-block" v-on:click="toggle_filters" title="Búsqueda avanzada">
                        <i class="fa fa-chevron-up" v-show="showing_filters"></i>
                        <i class="fa fa-chevron-down" v-show="!showing_filters"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div id="adv_filters" style="<?= $filters_style ?>">
        <div class="form-group row">
            <div class="col-md-9">
                <select name="role" v-model="filters.role" class="form-control" title="Filtrar por rol">
                    <option v-for="(option_role, key_role) in options_role" v-bind:value="key_role">{{ option_role }}</option>
                </select>
            </div>
            <label for="role" class="col-md-3 col-form-label">Rol</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="fe1" v-model="filters.fe1" class="form-control">
                    <option v-for="(option_invitation, key_invitation) in options_invitation_status" v-bind:value="key_invitation">{{ option_invitation }}</option>
                </select>
            </div>
            <label for="fe1" class="col-md-3 col-form-label">Invitaciones</label>
        </div>
        <div class="form-group row">
            <div class="col-md-9">
                <select name="status" v-model="filters.status" class="form-control">
                    <option v-for="(option_status, key_status) in options_status" v-bind:value="key_status">{{ option_status }}</option>
                </select>
            </div>
            <label for="status" class="col-md-3 col-form-label">Estado</label>
        </div>
    </div>
</form>