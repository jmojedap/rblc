<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['accounts_profile'] = '';
    $cl_nav_2['accounts_edit'] = '';
    //$cl_nav_2['accounts_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'accounts/explore' ) { $cl_nav_2['accounts_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    var element_id = '<?= $row->id ?>';
    
    sections.profile = {
        icon: 'fa fa-user',
        text: 'Profile',
        class: '<?= $cl_nav_2['accounts_profile'] ?>',
        cf: 'accounts/profile/'
    };

    sections.edit = {
        icon: 'fa fa-pencil-alt',
        text: 'Edit',
        class: '<?= $cl_nav_2['accounts_edit'] ?>',
        cf: 'accounts/edit/basic'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['profile', 'edit'];
    sections_role[2] = ['profile', 'edit'];
    sections_role[3] = ['profile', 'edit'];
    sections_role[13] = ['profile', 'edit'];
    sections_role[23] = ['profile', 'edit'];
    
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