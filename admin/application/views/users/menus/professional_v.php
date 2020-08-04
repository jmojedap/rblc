<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['users_profile'] = '';
    $cl_nav_2['professionals_images'] = '';
    $cl_nav_2['users_content'] = '';
    $cl_nav_2['professionals_categories'] = '';
    $cl_nav_2['users_social_links'] = '';
    $cl_nav_2['users_notes'] = '';
    $cl_nav_2['users_edit'] = '';
    //$cl_nav_2['users_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'users/explore' ) { $cl_nav_2['users_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';

    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explore',
        class: '<?php echo $cl_nav_2['users_explore'] ?>',
        cf: 'users/explore/',
        'anchor': true
    };
    
    sections.profile = {
        icon: 'fa fa-user',
        text: 'Profile',
        class: '<?php echo $cl_nav_2['users_profile'] ?>',
        cf: 'users/profile/' + element_id
    };

    sections.images = {
        icon: 'far fa-address-card',
        text: 'Images',
        class: '<?php echo $cl_nav_2['professionals_images'] ?>',
        cf: 'professionals/images/' + element_id
    };

    sections.content = {
        icon: 'far fa-address-card',
        text: 'Content',
        class: '<?php echo $cl_nav_2['users_content'] ?>',
        cf: 'users/content/' + element_id
    };

    sections.categories = {
        icon: 'far fa-sticky-note',
        text: 'Categories',
        class: '<?php echo $cl_nav_2['professionals_categories'] ?>',
        cf: 'professionals/categories/' + element_id
    };

    sections.social_links = {
        icon: 'fa fa-link',
        text: 'Social',
        class: '<?php echo $cl_nav_2['users_social_links'] ?>',
        cf: 'users/social_links/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Edit',
        class: '<?php echo $cl_nav_2['users_edit'] ?>',
        cf: 'users/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'profile', 'images', 'categories', 'social_links', 'edit'];
    sections_rol.admn = ['explore', 'profile', 'images', 'categories', 'social_links', 'edit'];
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');