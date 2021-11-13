<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="get_list">
    <div class="form-group row">
        <div class="col-md-9">
            <div class="input-group">
                <input
                    type="text" name="q" class="form-control"
                    placeholder="Buscar..." autofocus title="Search"
                    v-model="filters.q" v-on:change="get_list"
                    >
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block"><i class="fa fa-search"></i></button>
        </div>
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
</form>