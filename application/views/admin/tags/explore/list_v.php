<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"><input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected"></th>
            <th>Tag name</th>
            <th></th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                    
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "{$controller}/info/" ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>

                <td>
                    <a v-bind:href="`<?= URL_APP ?>pictures/explore/1/?q=` + element.slug" class="btn btn-light btn-sm" target="_blank">
                        <i class="fa fa-external-link-alt"></i>
                        Images
                    </a>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>