<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['projects_explore'] = '';
    $cl_nav_2['projects_info'] = '';
    $cl_nav_2['projects_edit'] = '';
    $cl_nav_2['projects_image'] = '';
    $cl_nav_2['projects_images'] = '';
    $cl_nav_2['projects_comments'] = '';
    //$cl_nav_2['projects_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'projects_cropping' ) { $cl_nav_2['projects_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Info',
        class: '<?= $cl_nav_2['projects_info'] ?>',
        cf: 'projects/info/' + element_id
    };

    sections.comments = {
        icon: 'far fa-comment',
        text: 'Comments',
        class: '<?= $cl_nav_2['projects_comments'] ?>',
        cf: 'projects/comments/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Edit',
        class: '<?= $cl_nav_2['projects_edit'] ?>',
        cf: 'projects/edit/' + element_id,
        anchor: true
    };

    sections.images = {
        icon: 'fa fa-image',
        text: 'Images',
        class: '<?= $cl_nav_2['projects_images'] ?>',
        cf: 'projects/images/' + element_id
    };
    
    //Secciones para cada rol
    sections_role[1] = ['info', 'images', 'edit'];
    sections_role[2] = ['info', 'images', 'edit'];
    sections_role[3] = ['info', 'images', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[APP_RID]) 
    {
        var key = sections_role[APP_RID][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');