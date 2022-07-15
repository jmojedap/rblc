<?php
    $cf = $this->uri->segment(2) . '/' .  $this->uri->segment(3);   //Controller / Function
    $search_form_action = 'pictures/explore';
    if ( $cf == 'professionals/explore' ) { $search_form_action = $cf; }
    if ( $cf == 'projects/explore' ) { $search_form_action = $cf; }
?>

<form accept-charset="utf-8" method="GET" class="form-inline my-2 my-md-0" action="<?= URL_FRONT . $search_form_action ?>">
    <input type="text" aria-label="Search"
        id="app_q" name="q" value="<?= $this->input->get('q') ?>">
</form>
