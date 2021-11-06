<?php 
    //Imagen
        $att_img['class'] = 'card-img-top';
?>

<div class="user_profile">
    <div class="row">
        
        <div class="col-md-9">
            <h1 class="mt-3"><?= $row->display_name ?></h1>
            <p class="text-muted">
                <i class="fa fa-map-marker-alt"></i>
                <?= $row->city ?>, <?= $row->province_stata ?>
            </p>
            <div class="text-muted">
                <i class="fa fa-phone mr-2"></i> <?= $row->phone_number ?>
                <br>
                <i class="fa fa-envelope mr-2"></i> <?= $row->email ?>
            </div>
            
        </div>
        <div class="col-md-3">
            <img
                src="<?= $row->url_image ?>"
                class="w100pc"
                v-bind:alt="user image"
                onerror="this.src='<?= URL_IMG ?>users/user.png'"
            >
        </div>
    </div>
    <div class="mt-2">
        <a href="<?= URL_FRONT . "accounts/edit/" ?>" class="btn btn-light">Edit</a>
    </div>
</div>
