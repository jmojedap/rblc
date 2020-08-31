<?php
    $styles['main'] = "font-family: 'Trebuchet MS', Helvetica, sans-serif; text-align: center; background-color: #FAFAFA; padding: 20px;";
    $styles['h1'] = "color: #F37062; margin-top: 50px;";
    $styles['h3'] = "";
    $styles['p'] = "";
    $styles['a'] = "";
    $styles['button'] = "padding: 10px; background-color: #FDD327; color: #333; text-decoration: none; margin-top: 20px;";
    $styles['footer'] = "margin-top: 50px; color: #AAAAAA; font-size: 0.7em";
?>
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
<div style="<?= $styles['main'] ?>">
    <h1 style="<?= $styles['h1'] ?>"><?= $texts['title'] ?></h1>
    <h3><?= $row_user->first_name . ' ' . $row_user->last_name ?></h3>
    <p><?= $texts['paragraph'] ?></p>
    <a style="<?= $styles['button'] ?>" href="<?= URL_APP . $texts['link'] ?>" target="_blank">
        <?= $texts['button'] ?>
    </a>
    <footer style="<?= $styles['footer'] ?>">Created por Colibri House</footer>
</div>