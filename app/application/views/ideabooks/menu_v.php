<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['ideabooks_explore'] = '';
    $cl_nav_2['ideabooks_info'] = '';
    $cl_nav_2['ideabooks_edit'] = '';
    $cl_nav_2['ideabooks_projects'] = '';
    $cl_nav_2['ideabooks_images'] = '';
    //$cl_nav_2['ideabooks_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'ideabooks_cropping' ) { $cl_nav_2['ideabooks_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    const element_id = '<?php echo $row->id ?>';

    sections.my_ideabooks = {
        icon: 'fa fa-arrow-left',
        text: 'My ideabooks',
        class: '<?php echo $cl_nav_2['ideabooks_my_ideabooks'] ?>',
        cf: 'ideabooks/my_ideabooks/',
        anchor: true,
    };

    sections.info = {
        icon: '',
        text: 'Preview',
        class: '<?php echo $cl_nav_2['ideabooks_info'] ?>',
        cf: 'ideabooks/info/' + element_id,
        anchor: true,
    };

    sections.edit = {
        icon: '',
        text: 'Edit',
        class: '<?php echo $cl_nav_2['ideabooks_edit'] ?>',
        cf: 'ideabooks/edit/' + element_id
    };
    
    sections.projects = {
        icon: '',
        text: 'Projects',
        class: '<?php echo $cl_nav_2['ideabooks_projects'] ?>',
        cf: 'ideabooks/projects/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['my_ideabooks', 'projects', 'edit', 'info'];
    sections_rol.admn = ['my_ideabooks', 'projects', 'edit', 'info'];
    sections_rol.prof = ['my_ideabooks', 'projects', 'edit', 'info'];
    sections_rol.hown = ['my_ideabooks', 'projects', 'edit', 'info'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');