<?php
    $styles = $this->Notification_model->email_styles();
    $texts['link'] = URL_FRONT . "professionals/profile/{$follower->id}/{$follower->username}";
?>
<div style="<?= $styles['body'] ?>">
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>" target="_blank">
            <img src="<?= base_url() ?>resources/static/app/logo.png" alt="Colibri.House">
        </a>
    </p>
    <h1 style="<?= $styles['h1'] ?>">You have a new follower</h1>

    <div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
        <p>
            <a href="<?= $texts['link'] ?>" target="_blank">
                <img src="<?= $follower->url_thumbnail ?>" alt="Follower picture" style="width: 80px; border-radius: 50px; margin-bottom: 0.5em;">
            </a>
            <br>
            <a href="<?= $texts['link'] ?>" style="<?= $styles['a'] ?>" target="_blank"> 
                <?= $follower->display_name ?>
            </a>
            
            <br>
            <small><?= $follower->username ?></small>
        </p>
    </div>

    <p>has started to follow you</p>
    <br>
    <div>
        <a href="<?= $texts['link'] ?>" style="<?= $styles['button'] ?>" target="_blank">
            View more...
        </a>
    </div>
    
    <footer style="<?= $styles['footer'] ?>">Created by Colibri House</footer>
</div>