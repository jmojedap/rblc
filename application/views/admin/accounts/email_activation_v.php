<?php
    $texts['title'] = 'Welcome to ' . APP_NAME;
    $texts['paragraph'] = 'To activate your account click on the following link:';
    $texts['button'] = 'Activate';
    $texts['link'] = "accounts/activation/{$row_user->activation_key}";
    
    if ( $activation_type == 'recovery' ) 
    {
        $texts['title'] = APP_NAME;
        $texts['paragraph'] = 'To reset your password click on the following link:';
        $texts['button'] = 'Reset my password';
        $texts['link'] = "accounts/recover/{$row_user->activation_key}";
    }
?>
<h1 style="<?= $styles['h1'] ?>"><?= $texts['title'] ?></h1>
<h2 style="<?= $styles['h2'] ?>"><?= $row_user->display_name ?></h2>
<p><?= $texts['paragraph'] ?></p>
<a style="<?= $styles['button'] ?>" href="<?= URL_APP . $texts['link'] ?>" target="_blank">
    <?= $texts['button'] ?>
</a>