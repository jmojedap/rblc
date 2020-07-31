<div class="center_box_450">
    <div class="row">
        <div class="col col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center text-success"><i class="fa fa-check-circle"></i></h3>
                    <h1 class="text-center">Hello, <?= $row->first_name ?></h1>
                    <h2 class="text-center text-success">
                        Welcome to <?php echo APP_NAME ?>
                    </h2>
                    <ul>
                        <li>We send a message to your <b>email</b> with a link to activate your account.</li>
                        <li>It may take a few minutes for the message to be received in your email.</li>
                        <li>The message may be in the Spam folder.</li>
                    </ul>
                    <a class="btn btn-main btn-block mt-2" href="<?= base_url('accounts/login/') ?>">
                        LOG IN
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


