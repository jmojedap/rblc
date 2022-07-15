<?php
    $texts['link_account'] = URL_APP . "professionals/profile/";
    //$texts['link_profile'] = URL_APP . "accounts/recover/";
    $texts['link_profile'] = '#';
    $texts['link_delete'] = '#';
?>

<h1 style="<?= $styles['h1'] ?>">Hello, {{ current.display_name }}</h1>
<h2 style="<?= $styles['h2'] ?>">You are in colibri.house</h2>

<p>
    If you have a service-based business & are looking to reach hundreds, even thousands of local potential customers,
    Colibri may be exactly what you are looking for. Colibri is a directory, portfolio & social platform all wrapped into one
    slick interface, this is a great way to bring designers, architects and creative people together. You can publish your
    business details, past work and even communicate with potential customers.
</p>

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

<div style="margin-top: 1em; text-align: center;">
    <a v-bind:href="`<?= $texts['link_account'] ?>` + current.id + `/` + current.username" style="<?= $styles['button'] ?> margin-right: 1em; width: 240px;" target="_blank">
        Activate my Account
    </a>
    <a href="<?= $texts['link_profile'] ?>" style="<?= $styles['button'] ?> margin-right: 1em; width: 240px;" target="_blank">
        Visit my Profile
    </a>
    <a href="<?= $texts['link_delete'] ?>" style="<?= $styles['button'] ?> margin-right: 1em; width: 240px;" target="_blank">
        Delete my Profile
    </a>
</div>