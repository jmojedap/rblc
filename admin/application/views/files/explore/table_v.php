<?php
    //Clases columnas
        $cl_col['thumbnail'] = '';
        $cl_col['info'] = '';
        $cl_col['link'] = ''
?>

<div class="table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="<?php echo $cl_col['thumbnail'] ?>" width="60px"></th>
            <th class="<?php echo $cl_col['info'] ?>">Información</th>
            
            <th class="<?php echo $cl_col['link'] ?>" width="40px"></th>
            <th width="95px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td class="<?php echo $cl_col['thumbnail'] ?>">
                    <a v-bind:href="`<?php echo URL_UPLOADS ?>` + element.folder + element.file_name" data-lightbox="image-1" v-bind:data-title="element.title">
                        <img
                            v-bind:src="`<?php echo URL_UPLOADS ?>` + element.folder + `sm_` + element.file_name"
                            class="rounded w50p"
                            alt="imagen miniatura"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_nd_square.png'"
                        >
                    </a>
                </td>
                
                <td class="<?php echo $cl_col['info'] ?>">
                    <a v-bind:href="`<?php echo base_url("files/info/") ?>` + element.id">{{ element.title }}</a>
                    <p>{{ element.description }}</p>
                </td>

                <td class="<?php echo $cl_col['link'] ?>">
                </td>

                <td>
                    <a class="btn btn-sm btn-light" v-bind:href="`<?php echo URL_UPLOADS ?>` + element.folder + element.file_name" target="_blank">
                        <i class="fa fa-external-link-alt"></i>
                    </a>
                    <button class="btn btn-light btn-sm btn-sm-square" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>