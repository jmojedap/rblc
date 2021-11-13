<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['files_explore'] = '';
    $cl_nav_2['files_check'] = '';
    $cl_nav_2['files_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'files_check_e' ) { $cl_nav_2['files_check'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explore',
        class: '<?= $cl_nav_2['files_explore'] ?>',
        cf: 'files/explore'
    };

    sections.check = {
        icon: 'fa fa-upload',
        text: 'Check',
        class: '<?= $cl_nav_2['files_check'] ?>',
        cf: 'files/check'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'New',
        class: '<?= $cl_nav_2['files_add'] ?>',
        cf: 'files/add'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['explore', 'add', 'check'];
    sections_role[2] = ['explore', 'add', 'check'];
    sections_role[3] = ['explore', 'add', 'check'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_rid]) 
    {
        //console.log(sections_role[rol][key_section]);
        var key = sections_role[app_rid][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');