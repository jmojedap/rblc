<?php
    $styles = $this->Notification_model->email_styles();

    $texts['link'] = URL_FRONT . "professionals/profile/{$sender->id}/{$sender->username}";
?>
<div style="<?= $styles['body'] ?>">
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>" target="_blank">
            <img src="https://www.colibri.house/app/resources/images/app/logo.png" alt="Colibri.House">
        </a>
    </p>
    <h1 style="<?= $styles['h1'] ?>">You have a new message from</h1>

    <div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
        <p>
            <a href="<?= $texts['link'] ?>" target="_blank">
                <img src="<?= $sender->url_thumbnail ?>" alt="Imagen sender" style="width: 80px; border-radius: 50px; margin-bottom: 0.5em;">
            </a>
            <br>
            <a href="<?= $texts['link'] ?>" style="<?= $styles['a'] ?>" target="_blank"> 
                <?= $sender->display_name ?>
            </a>
            
            <br>
            <small><?= $sender->username ?></small>
        </p>
    </div>

    
    <p><?= $row_message->text ?></p>
    <br>
    <div>
        <a href="<?= $texts['link'] ?>" style="<?= $styles['button'] ?> width: 120px;" target="_blank">
            Reply...
        </a>
    </div>
    
    <footer style="<?= $styles['footer'] ?>">Created by Colibri House</footer>
</div>