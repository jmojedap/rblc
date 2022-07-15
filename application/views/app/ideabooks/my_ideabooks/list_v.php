<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="100px"></th>
            <th>Ideabook</th>      
            <th width="100px"></th>      
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">    
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "ideabooks/info/" ?>` + element.id">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded w100p"
                            alt="Project main image"
                            onerror="this.src='<?= URL_IMG ?>app/sm_coming_soon.png'"
                        >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "ideabooks/info/" ?>` + element.id">
                        {{ element.name }}
                    </a>
                    <br>
                    <p>{{ element.description }}</p>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_FRONT . "ideabooks/edit/" ?>` + element.id" class="btn btn-sm btn-light"><i class="fa fa-pencil-alt"></i></a>
                    <button class="btn btn-sm btn-light" data-toggle="modal" data-target="#delete_modal" v-on:click="set_current(key)"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>