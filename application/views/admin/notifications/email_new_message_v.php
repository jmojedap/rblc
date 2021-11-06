<?php
    $styles = $this->Notification_model->email_styles();

    $texts['link'] = URL_FRONT . "messages/conversation/";

    $sender_picture = URL_IMG . 'users/user.png';
    if ( $sender->image_id > 0 ) $sender_picture = $sender->url_thumbnail;
?>
<div style="<?= $styles['body'] ?>">
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>" target="_blank">
            <img src="<?= base_url() ?>resources/static/app/logo.png" alt="Colibri.House">
        </a>
    </p>
    <h1 style="<?= $styles['h1'] ?>">You have a new message from</h1>

    <div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
        <p>
            <a href="<?= $texts['link'] ?>" target="_blank">
                <img src="<?= $sender_picture ?>" alt="Message user picture" style="width: 80px; border-radius: 50px; margin-bottom: 0.5em;">
            </a>
            <br>
            <a href="<?= $texts['link'] ?>" style="<?= $styles['a'] ?>" target="_blank"> 
                <?= $sender->display_name ?>
            </a>
            
            <br>
            <small><?= $sender->username ?></small>
        </p>
    </div>

    <div style="background-color: #ffece6; padding: 15px; max-width: 70%; margin: 0 auto; margin-bottom: 1em; border-radius: 0.5em; font-style: italic;">
        <p>
            "<?= $row_message->text ?>"...
        </p>
    </div>

    <br>
    <div>
        <a href="<?= $texts['link'] ?>" style="<?= $styles['button'] ?> width: 120px;" target="_blank">
            Reply...
        </a>
    </div>
    
    <footer style="<?= $styles['footer'] ?>">Created by Colibri House</footer>
</div>