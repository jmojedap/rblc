<?php
    //Clases columnas
        $cl_col['id'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['category'] = '';
        $cl_col['name'] = '';
        $cl_col['type'] = 'd-none d-md-table-cell d-lg-table-cell';
        $cl_col['excerpt'] = 'd-none d-md-table-cell d-lg-table-cell';
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="46px">
                <div class="form-check abc-checkbox abc-checkbox-primary">
                    <input class="form-check-input" type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
                    <label class="form-check-label" for="checkbox_all_selected"></label>
                </div>
            </th>
            <th class="<?php echo $cl_col['name'] ?>">Tag</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <div class="form-check abc-checkbox abc-checkbox-primary">
                        <input class="form-check-input" type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                        <label class="form-check-label" v-bind:for="`check_` + element.id"></label>
                    </div>
                </td>

                <td class="<?php echo $cl_col['category'] ?>">
                    {{ element.category }}
                </td>
                    
                <td class="<?php echo $cl_col['name'] ?>">
                    <a v-bind:href="`<?php echo base_url("{$controller}/info/") ?>` + element.id">
                        {{ element.name }}
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