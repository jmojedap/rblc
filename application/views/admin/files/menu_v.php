<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['files_explore'] = '';
    $cl_nav_2['files_info'] = '';
    $cl_nav_2['files_tags'] = '';
    $cl_nav_2['files_cropping'] = '';
    $cl_nav_2['files_change'] = '';
    $cl_nav_2['files_edit'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'files/explore' ) { $cl_nav_2['files_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $file_id ?>';

    sections.info = {
        icon: '',
        text: 'Info',
        class: '<?= $cl_nav_2['files_info'] ?>',
        cf: 'files/info/' + element_id
    };

    sections.tags = {
        icon: 'fas fa-tags',
        text: 'Tags',
        class: '<?= $cl_nav_2['files_tags'] ?>',
        cf: 'files/tags/' + element_id
    };

    sections.cropping = {
        icon: 'fa fa-crop',
        text: 'Recortar',
        class: '<?= $cl_nav_2['files_cropping'] ?>',
        cf: 'files/cropping/' + element_id
    };

    sections.change = {
        icon: 'far fa-file',
        text: 'Cambiar',
        class: '<?= $cl_nav_2['files_change'] ?>',
        cf: 'files/change/' + element_id
    };

    sections.edit = {
        icon: '',
        text: 'Edit',
        class: '<?= $cl_nav_2['files_edit'] ?>',
        cf: 'files/edit/' + element_id,
        anchor: true
    };
    
    //Secciones para cada rol
    sections_role[1] = ['info', 'edit'];
    sections_role[2] = ['info', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[APP_RID]) 
    {
        //console.log(sections_role[rol][key_section]);
        var key = sections_role[APP_RID][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');