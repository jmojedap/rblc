<?php
    $cl_nav_3['preview'] = '';
    $cl_nav_3['basic'] = '';
    $cl_nav_3['social_links'] = '';
    $cl_nav_3['image'] = '';
    $cl_nav_3['images'] = '';
    $cl_nav_3['password'] = '';
    $cl_nav_3['business_profile'] = '';

    $app_cf_index = $this->uri->segment(3);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'basic'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'crop' ) { $cl_nav_3['image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_role = [];
    var element_id = '<?= $this->session->userdata('user_id') ?>';
    
    sections.preview = {
        icon: 'fa fa-arrow-left',
        text: 'Preview',
        class: '<?php echo $cl_nav_3['preview'] ?>',
        cf: 'professionals/profile/' + element_id,
        anchor: true
    };

    sections.basic = {
        icon: '',
        text: 'Basic',
        class: '<?php echo $cl_nav_3['basic'] ?>',
        cf: 'accounts/edit/basic'
    };

    sections.business_profile = {
        icon: '',
        text: 'Basic',
        class: '<?php echo $cl_nav_3['business_profile'] ?>',
        cf: 'accounts/edit/business_profile'
    };

    sections.social_links = {
        icon: '',
        text: 'Social links',
        class: '<?php echo $cl_nav_3['social_links'] ?>',
        cf: 'accounts/edit/social_links'
    };
    
    sections.image = {
        icon: 'fa fa-user-circle',
        text: 'Profile image',
        class: '<?php echo $cl_nav_3['image'] ?>',
        cf: 'accounts/edit/image'
    };

    sections.images = {
        icon: 'far fa-images',
        text: 'Images',
        class: '<?php echo $cl_nav_3['professionals_images'] ?>',
        cf: 'professionals/images'
    };
    
    sections.password = {
        icon: 'fa fa-lock',
        text: 'Password',
        class: '<?php echo $cl_nav_3['password'] ?>',
        cf: 'accounts/edit/password'
    };
    
    //Secciones para cada rol
    sections_role.dvlp = ['basic', 'social_links', 'image', 'password'];
    sections_role.admn = ['basic', 'social_links', 'image', 'password'];
    sections_role.prof = ['preview', 'business_profile', 'social_links', 'image', 'images', 'password'];
    sections_role.hown = ['basic', 'social_links', 'image', 'password'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_r]) 
    {
        var key = sections_role[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');