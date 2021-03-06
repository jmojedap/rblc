<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['tags_explore'] = '';
    $cl_nav_2['tags_info'] = '';
    $cl_nav_2['tags_edit'] = '';
    //$cl_nav_2['tags_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'tags_cropping' ) { $cl_nav_2['tags_image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $row->id ?>';
    
    sections.explore = {
        icon: 'fa fa-arrow-left',
        text: 'Explore',
        class: '<?= $cl_nav_2['tags_explore'] ?>',
        cf: 'tags/explore/',
        'anchor': true
    };

    sections.info = {
        icon: '',
        text: 'Info',
        class: '<?= $cl_nav_2['tags_info'] ?>',
        cf: 'tags/info/' + element_id
    };

    sections.edit = {
        icon: '',
        text: 'Edit',
        class: '<?= $cl_nav_2['tags_edit'] ?>',
        cf: 'tags/edit/' + element_id
    };
    
    //Secciones para cada rol
    sections_role[0] = ['explore', 'info', 'edit'];
    sections_role[1] = ['explore', 'info', 'edit'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
    
</script>

<?php
$this->load->view('common/nav_2_v');