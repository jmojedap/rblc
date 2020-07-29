<div class="center_box_750">
    <table class="table bg-white">
        <tbody>
            <tr>
                <td>ID</td>
                <td><?php echo $row->id ?></td>
            </tr>
            <tr>
                <td>Project name</td>
                <td><?php echo $row->post_name ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo $row->excerpt ?></td>
            </tr>
        </tbody>
    </table>

    <table class="table bg-white">
        <tbody>
            <tr>
                <td>Editor</td>
                <td>
                    <a href="<?php echo base_url("users/profile/{$row->updater_id}") ?>">
                        <?php echo $this->App_model->name_user($row->updater_id) ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Edited at</td>
                <td>
                    <?= $this->pml->date_format($row->updated_at, 'M d'); ?> &middot;
                    <?php echo $this->pml->ago($row->updated_at) ?>
                </td>
            </tr>
            <tr>
                <td>Creator</td>
                <td>
                    <a href="<?php echo base_url("users/profile/{$row->creator_id}") ?>">
                        <?php echo $this->App_model->name_user($row->creator_id) ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Created at</td>
                <td>
                    <?= $this->pml->date_format($row->created_at, 'M d'); ?> &middot;
                    <?php echo $this->pml->ago($row->created_at) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>