<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="50px"></th>
            <th>Project</th>      
            <th width="35px"></th>
            <th width="35px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">    
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "projects/info/" ?>` + element.id">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded w50p"
                            alt="Project main image"
                            onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
                        >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "projects/info/" ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "projects/edit/" ?>` + element.id" class="">Edit</a>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" title="Delete project..." data-toggle="modal" data-target="#delete_modal"
                        v-on:click="setCurrent(element.id)"
                        >
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>