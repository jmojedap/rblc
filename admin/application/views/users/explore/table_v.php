<?php
    $cl_col['title'] = '';
    $cl_col['image'] = 'only-lg';
    $cl_col['city'] = 'only-lg';
    $cl_col['role'] = 'only-lg';
    $cl_col['email'] = 'only-lg';
?>

<div class="table table-responsive">
    <table class="table table-hover bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" id="checkbox_all_selected" @change="select_all" v-model="all_selected">
            </th>
            <th class="<?php echo $cl_col['image'] ?>" width="60px"></th>
            <th class="<?php echo $cl_col['title'] ?>">Name</th>
            <th class="<?php echo $cl_col['city'] ?>">City</th>
            <th class="<?php echo $cl_col['role'] ?>">Role</th>
            <th class="<?php echo $cl_col['role'] ?>">E-mail</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                
                <td class="<?php echo $cl_col['image'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/profile/") ?>` + element.id" class="">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded-circle w50p"
                            v-bind:alt="element.id"
                            onerror="this.src='<?php echo URL_IMG ?>users/sm_user.png'"
                        >
                    </a>
                </td>
                <td class="<?php echo $cl_col['title'] ?>">
                    <a v-bind:href="`<?php echo base_url("users/profile/") ?>` + element.id + `/` + element.username">
                        {{ element.display_name }}
                    </a>
                </td>
                <td class="<?php echo $cl_col['city'] ?>">
                    {{ element.city_name }}
                </td>
                <td class="<?php echo $cl_col['role'] ?>">
                    <i class="fa fa-check-circle text-success" v-if="element.status == 1"></i>
                    <i class="fa fa-check-circle text-warning" v-if="element.status == 2"></i>
                    <i class="far fa-circle text-danger" v-if="element.status == 0"></i>
                    {{ element.role | role_name }}
                </td>
                <td class="<?php echo $cl_col['email'] ?>">{{ element.email }}</td>
                <td>
                    <button class="btn btn-light btn-sm w31p" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>