<div id="post_comments_app">
    <?php $this->load->view('posts/comments/content_v') ?>
    <?php $this->load->view('posts/comments/modal_form_v') ?>
    <?php $this->load->view('posts/comments/modal_delete_comment_v') ?>
    <?php $this->load->view('posts/comments/modal_delete_answer_v') ?>
</div>
<?php $this->load->view('posts/comments/vue_v') ?>