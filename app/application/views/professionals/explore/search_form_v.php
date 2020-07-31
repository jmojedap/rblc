<form accept-charset="utf-8" method="POST" id="search_form" @submit.prevent="search">
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
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block">
                <i class="fa fa-search"></i>
                Search
            </button>
        </div>
    </div>
</form>