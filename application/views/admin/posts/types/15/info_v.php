<?php
    $user = json_decode($row->content_json);
?>

<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>Info type</td>
                    <td>Información sobre eliminación de cuenta</td>
                </tr>
                <tr>
                    <td>User</td>
                    <td><?= $user->display_name ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Deleted at</td>
                    <td>
                        <?= $row->created_at ?> &middot;
                        <span class="text-muted">
                            <?= $this->pml->ago($row->created_at); ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?= $row->post_name ?></h2>
                <div>
                    <h4 class="text-muted">Deletion reason:</h4>
                    <p>
                        <?= $row->text_1 ?>
                    </p>
                </div>
                <hr>
                <div class="mb-2">
                    <p>
                        <span class="text-primary">User ID</span>: <?= $user->id ?> &middot;
                        <span class="text-primary">Name</span>: <?= $user->display_name ?> &middot;
                        <span class="text-primary">E-mail</span>: <?= $user->email ?> &middot;
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>