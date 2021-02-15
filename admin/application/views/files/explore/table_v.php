<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th width="60px"></th>
            <th>Information</th>
            
            <th width="40px"></th>
            <th width="95px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>

                <td>
                    <a v-bind:href="`<?= URL_UPLOADS ?>` + element.folder + element.file_name" data-lightbox="image-1" v-bind:data-title="element.title">
                        <img
                            v-bind:src="`<?= URL_UPLOADS ?>` + element.folder + `sm_` + element.file_name"
                            class="rounded w50p"
                            alt="imagen miniatura"
                            onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'"
                        >
                    </a>
                </td>
                
                <td>
                    <a v-bind:href="`<?= base_url("files/info/") ?>` + element.id">{{ element.title }}</a>
                    <p>{{ element.description }}</p>
                </td>

                <td>
                </td>

                <td>
                    <a class="a4" v-bind:href="`<?= URL_UPLOADS ?>` + element.folder + element.file_name" target="_blank">
                        <i class="fa fa-external-link-alt"></i>
                    </a>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>