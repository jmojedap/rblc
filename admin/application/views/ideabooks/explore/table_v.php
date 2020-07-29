<?php
    //Clases columnas
        $cl_col['id'] = '';
        $cl_col['title'] = '';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th>
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="<?php echo $cl_col['title'] ?>">Name</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                    
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("ideabooks/info/") ?>` + element.id">
                        {{ element.post_name }}
                    </a>
                </td>
                
                <td>
                    <button class="btn btn-light btn-sm btn-sm-square" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>