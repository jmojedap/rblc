<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="50px"></th>
            <th>Project</th>      
            <th width="35px"></th>      
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">    
                <td>
                    <a v-bind:href="`<?php echo base_url("projects/info/") ?>` + element.id">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded w50p"
                            alt="Project main image"
                            onerror="this.src='<?php echo URL_IMG ?>app/sm_coming_soon.jpg'"
                        >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?php echo base_url("projects/info/") ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= base_url("projects/edit/") ?>` + element.id" class="">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>