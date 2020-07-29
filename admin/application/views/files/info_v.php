<?php $arr_meta = json_decode($row->meta); ?>

<div class="row">
    <div class="col-md-4">
        <?php if ( $row->is_image ) { ?>
            <img class="rounded mb-2" alt="imagen archivo" src="<?= $row->url_thumbnail ?>" style="max-width: 100%;">   
        <?php } ?>

        

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Updated by</td>
                    <td><?php echo $this->App_model->name_user($row->updater_id) ?></td>
                </tr>
                <tr>
                    <td>Updated at</td>
                    <td><?php echo $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>Creator</td>
                    <td><?php echo $this->App_model->name_user($row->creator_id) ?></td>
                </tr>
                <tr>
                    <td>Created at</td>
                    <td><?php echo $row->created_at ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td><?php echo $row->type_id ?></td>
                </tr>
                <tr>
                    <td>Folder</td>
                    <td><?php echo $row->folder ?></td>
                </tr>
                <tr>
                    <td>File name</td>
                    <td><?php echo $row->file_name ?></td>
                </tr>
                <tr>
                    <td>Is image</td>
                    <td><?php echo $row->is_image ?></td>
                </tr>
                <tr>
                    <td>Size</td>
                    <td><?php echo $row->size ?> KB</td>
                </tr>
                <tr>
                    <td>Dimensions</td>
                    <td><?php echo $row->width ?> x <?= $row->height ?></td>
                </tr>
            </tbody>
        </table>

        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?php echo $row->title ?></h3>
                <div class="row">
                    <div class="col-md-4 text-right">
                        subtitle
                    </div>
                    <div class="col-md-8">
                        <?php echo $row->subtitle ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        description
                    </div>
                    <div class="col-md-8">
                        <?php echo $row->description ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        keywords
                    </div>
                    <div class="col-md-8">
                        <?php echo $row->keywords ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <table class="table bg-white">
            <thead>
                <th>Variable</th>
                <th>Value</th>
            </thead>
            <tbody>
                <?php if ( ! is_null($arr_meta) ) { ?>    
                    <?php foreach ( $arr_meta as $meta_field => $meta_value ) { ?>
                        <tr>
                            <td><?php echo $meta_field ?></td>
                            <td><?php echo $meta_value ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>