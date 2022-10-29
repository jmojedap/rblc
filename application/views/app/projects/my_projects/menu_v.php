<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['projects_my_projects'] = '';
    $cl_nav_2['projects_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'projects_import_e' ) { $cl_nav_2['projects_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.explore = {
        icon: '',
        text: 'My projects',
        class: '<?= $cl_nav_2['projects_my_projects'] ?>',
        cf: 'projects/my_projects'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'New',
        class: '<?= $cl_nav_2['projects_add'] ?>',
        cf: 'projects/add'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['explore', 'add'];
    sections_role[2] = ['explore', 'add'];
    sections_role[3] = ['explore', 'add'];
    sections_role[13] = ['explore', 'add'];
    
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