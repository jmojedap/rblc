<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
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
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';
    
    sections.explore = {
        'icon': 'fa fa-arrow-left',
        'text': 'Explore',
        'class': '<?php echo $cl_nav_2['projects_explore'] ?>',
        'cf': 'projects/explore/',
        'anchor': true
    };

    sections.info = {
        'icon': 'fa fa-info-circle',
        'text': 'Info',
        'class': '<?php echo $cl_nav_2['projects_info'] ?>',
        'cf': 'projects/info/' + element_id
    };

    sections.comments = {
        'icon': 'far fa-comment',
        'text': 'Comments',
        'class': '<?php echo $cl_nav_2['projects_comments'] ?>',
        'cf': 'projects/comments/' + element_id
    };

    sections.edit = {
        'icon': 'fa fa-pencil-alt',
        'text': 'Edit',
        'class': '<?php echo $cl_nav_2['projects_edit'] ?>',
        'cf': 'projects/edit/' + element_id
    };
    
    sections.image = {
        'icon': 'fa fa-image',
        'text': 'Imagen',
        'class': '<?php echo $cl_nav_2['projects_image'] ?>',
        'cf': 'projects/image/' + element_id
    };

    sections.image = {
        'icon': 'fa fa-image',
        'text': 'Images',
        'class': '<?php echo $cl_nav_2['projects_images'] ?>',
        'cf': 'projects/images/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'info', 'comments', 'image', 'edit'];
    sections_rol.admn = ['explore', 'info', 'comments', 'image', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');