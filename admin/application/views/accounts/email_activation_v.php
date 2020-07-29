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
<div style="font-family: 'Trebuchet MS', Helvetica, sans-serif; text-align: center; background-color: #FAFAFA; padding: 20px;">
    <h1 style="color: #E2061D; margin-top: 50px;"><?php echo $texts['title'] ?></h1>
    <h3><?php echo $row_user->first_name . ' ' . $row_user->last_name ?></h3>
    <p><?php echo $texts['paragraph'] ?></p>
    <a href="<?= base_url($texts['link']) ?>" target="_blank">
        <?= $texts['button'] ?>
    </a>
    <footer style="margin-top: 50px; color: #AAAAAA; font-size: 0.7em">
        Creado por Colibri
    </footer>
</div>