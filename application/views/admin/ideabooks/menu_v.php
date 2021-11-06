<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['ideabooks_explore'] = '';
    $cl_nav_2['ideabooks_info'] = '';
    $cl_nav_2['ideabooks_edit'] = '';
    $cl_nav_2['ideabooks_images'] = '';
    $cl_nav_2['ideabooks_projects'] = '';
    //$cl_nav_2['ideabooks_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'ideabooks_cropping' ) { $cl_nav_2['ideabooks_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $row->id ?>';

    sections.info = {
        icon: 'fa fa-info-circle',
        text: 'Info',
        class: '<?= $cl_nav_2['ideabooks_info'] ?>',
        cf: 'ideabooks/info/' + element_id
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Edit',
        class: '<?= $cl_nav_2['ideabooks_edit'] ?>',
        cf: 'ideabooks/edit/' + element_id
    };

    sections.projects = {
        icon: 'fa fa-project-diagram',
        text: 'Projects',
        class: '<?= $cl_nav_2['ideabooks_projects'] ?>',
        cf: 'ideabooks/projects/' + element_id
    };

    sections.images = {
        icon: 'fa fa-image',
        text: 'Images',
        class: '<?= $cl_nav_2['ideabooks_images'] ?>',
        cf: 'ideabooks/images/' + element_id
    };
    
    //Secciones para cada rol
    sections_role[1] = ['info', 'projects', 'edit'];
    sections_role[2] = ['info', 'projects', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');