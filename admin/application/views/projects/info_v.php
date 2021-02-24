<div class="center_box_750">
    <table class="table bg-white">
        <tbody>
            <tr>
                <td>ID</td>
                <td><?= $row->id ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?= $row->post_name ?></td>
            </tr>
            <tr>
                <td>Type</td>
                <td>
                    (<?= $row->related_2 ?>)
                    <?= $this->Item_model->name(722, $row->related_2); ?>
                </td>
            </tr>
            <tr>
                <td>Owner user</td>
                <td>
                    <a href="<?= base_url("users/profile/{$row->related_1}") ?>">
                        <?= $this->App_model->name_user($row->related_1) ?>
                    </a>
                    <span class="text-muted">(ID: <?= $row->related_1 ?>)</span>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?= $row->excerpt ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?= $this->pml->money($row->integer_1) ?></td>
            </tr>
            
            <tr>
                <td>Descriptors</td>
                <td>
                    <?php foreach ( $descriptors->result() as $row_descriptor ) { ?>
                        <span class="tag"><?= $row_descriptor->title ?></span>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>Styles</td>
                <td>
                    <?php foreach ( $styles->result() as $row_style ) { ?>
                        <span class="tag"><?= $row_style->title ?></span>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td>Feelings</td>
                <td>
                    <?php foreach ( $feelings->result() as $row_feeling ) { ?>
                        <span class="tag"><?= $row_feeling->title ?></span>
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table bg-white">
        <tbody>
            <tr>
                <td>Editor</td>
                <td>
                    <a href="<?= base_url("users/profile/{$row->updater_id}") ?>">
                        <?= $this->App_model->name_user($row->updater_id) ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Edited at</td>
                <td>
                    <?= $this->pml->date_format($row->updated_at, 'M d'); ?> &middot;
                    <?= $this->pml->ago($row->updated_at) ?>
                </td>
            </tr>
            <tr>
                <td>Creator</td>
                <td>
                    <a href="<?= base_url("users/profile/{$row->creator_id}") ?>">
                        <?= $this->App_model->name_user($row->creator_id) ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td>Created at</td>
                <td>
                    <?= $this->pml->date_format($row->created_at, 'M d'); ?> &middot;
                    <?= $this->pml->ago($row->created_at) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>