<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['ideabooks_explore'] = '';
    $cl_nav_2['ideabooks_add'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf_index == 'ideabooks_import_e' ) { $cl_nav_2['ideabooks_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explore',
        class: '<?= $cl_nav_2['ideabooks_explore'] ?>',
        cf: 'ideabooks/explore'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'New',
        class: '<?= $cl_nav_2['ideabooks_add'] ?>',
        cf: 'ideabooks/add'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['explore'];
    sections_role[2] = ['explore'];
    sections_role[3] = ['explore'];
    
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