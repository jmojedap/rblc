<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th>Name</th>
            <th>Type</th>

            <th>Price</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                    
                <td>
                    <a v-bind:href="`<?= base_url("projects/info/") ?>` + element.id">
                        {{ element.name }}
                    </a>
                    <p>{{ element.excerpt }}</p>
                </td>

                <td>{{ element.related_2 | project_type_name }}</td>

                <td>
                    {{ element.price | currency }}
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