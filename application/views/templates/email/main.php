<div style="<?= $styles['body'] ?>">
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>" target="_blank" title="Go to Colibri.House">
            <img width="240px" src="<?= base_url() ?>resources/static/app/logo.png" alt="Colibri.House">
        </a>
    </p>
    <?php $this->load->view($view_a) ?>
    
    <footer style="<?= $styles['footer'] ?>">Created by Colibri House</footer>
</div>