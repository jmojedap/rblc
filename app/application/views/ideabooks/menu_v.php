<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['ideabooks_explore'] = '';
    $cl_nav_2['ideabooks_info'] = '';
    $cl_nav_2['ideabooks_edit'] = '';
    $cl_nav_2['ideabooks_images'] = '';
    //$cl_nav_2['ideabooks_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'ideabooks_cropping' ) { $cl_nav_2['ideabooks_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    var element_id = '<?php echo $row->id ?>';

    sections.info = {
        icon: 'fa fa-arrow-left',
        text: 'View ideabook',
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
    
    sections.images = {
        icon: '',
        text: 'Images',
        class: '<?php echo $cl_nav_2['ideabooks_images'] ?>',
        cf: 'ideabooks/images/' + element_id
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['info', 'images', 'edit'];
    sections_rol.admn = ['info', 'images', 'edit'];
    sections_rol.prof = ['info', 'images', 'edit'];
    sections_rol.hown = ['info', 'images', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');