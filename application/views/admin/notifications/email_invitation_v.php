<?php
    $texts['link_account'] = URL_APP . "accounts/recover/{$user->activation_key}";
    $texts['link_profile'] = URL_APP . "professionals/profile/{$user->id}/{$user->username}";
?>
<h1 style="<?= $styles['h1'] ?>">Hello, <?= $user->display_name ?></h1>
<h2 style="<?= $styles['h2'] ?>">You are in colibri.house</h2>

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
    <p style="margin-bottom: 1.5em;">
        <a href="<?= $texts['link_account'] ?>" style="<?= $styles['button'] ?> width: 120px; margin-right: 0.5em;" target="_blank">
            Activate my Account
        </a>
    </p>
    <p>
        <a href="<?= $texts['link_profile'] ?>" style="<?= $styles['button'] ?> width: 120px;" target="_blank">
            Visit my Profile
        </a>
    </p>
</div>