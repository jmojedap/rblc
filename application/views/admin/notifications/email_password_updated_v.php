<?php
    $texts['link_profile'] = URL_APP . "professionals/profile/{$user->id}/{$user->username}";
?>
<h1 style="<?= $styles['h1'] ?>">Hello, <?= $user->display_name ?></h1>
<h2 style="<?= $styles['h2'] ?>">Your password was successfully updated</h2>

<div style="<?= $styles['center_box_750'] . ' ' . $styles['text_center'] ?>">
    <p>
        Your password was successfully updated and your user account was activated
    </p>
</div>

<hr>

<div style="margin-top: 1em;">
    <p>
        <a href="<?= $texts['link_profile'] ?>" style="<?= $styles['button'] ?> width: 120px;" target="_blank">
            Visit my Profile
        </a>
    </p>
</div>