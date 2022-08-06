<?php
    $texts['link_account'] = URL_APP . "accounts/recover/{$user->activation_key}";
    $texts['link_delete'] = URL_APP . "accounts/delete/{$user->activation_key}";
    $texts['link_profile'] = URL_APP . "professionals/profile/{$user->id}/{$user->username}";

    $styles['link_info'] = 'color: #E28F00; font-size: 1.1em; text-decoration: underline;';
?>
<h1 style="<?= $styles['h1'] ?>">Hello, <?= $user->display_name ?></h1>
<h2 style="<?= $styles['h2'] ?>">You are in colibri.house</h2>

<p>
    If you have a service-based business & are looking to reach hundreds, even thousands of local potential customers,
    Colibri may be exactly what you are looking for. Colibri is a directory, portfolio & social platform all wrapped into one
    slick interface, this is a great way to bring designers, architects and creative people together. You can publish your
    business details, past work and even communicate with potential customers.
</p>

<p class="text-center">
    <a href="<?= URL_APP ?>info/professionals" style="<?= $styles['link_info'] ?>">Get more info</a>
</p>

<p>
    <?php if ( $images->num_rows() > 0 ) : ?>
        <a href="<?= $texts['link_profile'] ?>" target="_blank">
            <img src="<?= $images->row(0)->url_thumbnail ?>" style="<?= $styles['thumbnail'] ?>">
        </a>
    <?php endif; ?>
    <?php if ( $images->num_rows() > 1 ) : ?>
        <a href="<?= $texts['link_profile'] ?>" target="_blank">
            <img src="<?= $images->row(1)->url_thumbnail ?>" style="<?= $styles['thumbnail'] ?>">
        </a>
    <?php endif; ?>
    <?php if ( $images->num_rows() > 2 ) : ?>
        <a href="<?= $texts['link_profile'] ?>" target="_blank">
            <img src="<?= $images->row(2)->url_thumbnail ?>" style="<?= $styles['thumbnail'] ?>">
        </a>
    <?php endif; ?>
</p>

<div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
    <p>
        <?= $text_message ?>
    </p>
</div>

<hr>

<div style="margin-top: 1em;">
    <a href="<?= $texts['link_account'] ?>" style="<?= $styles['button'] ?> margin-right: 0.5em;" target="_blank">
        Activate my Account
    </a>
    <a href="<?= $texts['link_profile'] ?>" style="<?= $styles['button'] ?> margin-right: 0.5em;" target="_blank">
        Visit my Profile
    </a>
    <a href="<?= $texts['link_delete'] ?>" style="<?= $styles['button'] ?> margin-right: 0.5em;" target="_blank">
        Delete my Profile
    </a>
</div>