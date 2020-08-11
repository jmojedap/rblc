<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>Type ID</td>
                    <td><?php echo $row->type_id ?></td>
                </tr>
                <tr>
                    <td>Post name</td>
                    <td><?php echo $row->post_name ?></td>
                </tr>
                <tr>
                    <td>status</td>
                    <td><?php echo $row->status ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?php echo $row->slug ?></td>
                </tr>
                <tr>
                    <td>Image ID</td>
                    <td><?php echo $row->image_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>count comments</td>
                    <td><?php echo $row->qty_comments ?></td>
                </tr>
                <tr>
                    <td>published at</td>
                    <td><?php echo $row->published_at ?></td>
                </tr>
                <tr>
                    <td>Updated by</td>
                    <td>
                        <a href="<?= base_url("users/profile/{$row->updater_id}") ?>">
                            <?= $this->App_model->name_user($row->updater_id) ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Updated at</td>
                    <td><?php echo $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>Created by</td>
                    <td>
                        <a href="<?= base_url("users/profile/{$row->creator_id}") ?>">
                            <?= $this->App_model->name_user($row->creator_id) ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Created at</td>
                    <td><?php echo $row->created_at ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $row->post_name ?></h2>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?php echo $row->excerpt ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?php echo $row->content ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?php echo $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?php echo $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>