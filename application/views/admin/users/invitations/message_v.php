<?php
    $texts['link_account'] = URL_APP . "professionals/profile/";
    //$texts['link_profile'] = URL_APP . "accounts/recover/abcdefghijklmno";
    $texts['link_profile'] = '#';
?>

<div style="<?= $styles['body'] ?>" class="border">
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>" target="_blank">
            <img src="<?= base_url() ?>resources/static/app/logo.png" alt="Colibri.House">
        </a>
    </p>
    <h1 style="<?= $styles['h1'] ?>">Hello, {{ current.display_name }}</h1>
    <h2 style="<?= $styles['h2'] ?>">You are in colibri.house</h2>

    <p>
        <a v-if="images.length > 0">
            <img v-bind:src="images[0].url_thumbnail" style="<?= $styles['thumbnail'] ?>">
        </a>
        <a v-if="images.length > 1">
            <img v-bind:src="images[1].url_thumbnail" style="<?= $styles['thumbnail'] ?>">
        </a>
        <a v-if="images.length > 2">
            <img v-bind:src="images[2].url_thumbnail" style="<?= $styles['thumbnail'] ?>">
        </a>
    </p>

    <div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
        <p>
            {{ form_values.text_message }}
        </p>
    </div>

    <hr>

    <div style="margin-top: 1em;">
        <p style="margin-bottom: 1.5em;">
            <a v-bind:href="`<?= $texts['link_account'] ?>` + current.id + `/` + current.username" style="<?= $styles['button'] ?> width: 140px; margin-right: 0.5em;" target="_blank">
                Activate my Account
            </a>
        </p>
        <p>
            <a href="<?= $texts['link_profile'] ?>" style="<?= $styles['button'] ?> width: 140px;" target="_blank">
                Visit my Profile
            </a>
        </p>
    </div>
    
    <footer style="<?= $styles['footer'] ?>">Created by Colibri House</footer>
</div>