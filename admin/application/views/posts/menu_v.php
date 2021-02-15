<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['posts_explore'] = '';
    $cl_nav_2['posts_info'] = '';
    $cl_nav_2['posts_edit'] = '';
    $cl_nav_2['posts_details'] = '';
    $cl_nav_2['posts_image'] = '';
    //$cl_nav_2['posts_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'posts_cropping' ) { $cl_nav_2['posts_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explore',
        class: '<?= $cl_nav_2['posts_explore'] ?>',
        cf: 'posts/explore/',
        anchor: true
    };

    sections.info = {
        icon: '',
        text: 'Information',
        class: '<?= $cl_nav_2['posts_info'] ?>',
        cf: 'posts/info/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Edit',
        class: '<?= $cl_nav_2['posts_edit'] ?>',
        cf: 'posts/edit/' + element_id,
        anchor: true
    };
    
    sections.image = {
        icon: 'fa fa-image',
        text: 'Imagen',
        class: '<?= $cl_nav_2['posts_image'] ?>',
        cf: 'posts/image/' + element_id
    };

    sections.images = {
        icon: 'fa fa-images',
        text: 'Images',
        class: '<?= $cl_nav_2['posts_images'] ?>',
        cf: 'posts/images/' + element_id
    };

    sections.details = {
        icon: 'fa fa-bars',
        text: 'Details',
        class: '<?= $cl_nav_2['posts_details'] ?>',
        cf: 'posts/details/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'images', 'details', 'edit'];
    sections_rol.admn = ['explore', 'info', 'images', 'details', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');