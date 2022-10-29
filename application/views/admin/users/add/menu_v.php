<?php
    $cl_nav_3['person'] = '';
    $cl_nav_3['institution'] = '';

    $app_cf_index = $this->uri->segment(3);
    if ( strlen($app_cf_index) == 0 ) { $app_cf_index = 'person'; }
    
    $cl_nav_3[$app_cf_index] = 'active';
    if ( $app_cf_index == 'crop' ) { $cl_nav_3['image'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_3 = [];
    var sections_role = [];
    //var element_id = '<?php //echo $this->uri->segment(3) ?>';
    
    sections.person = {
        icon: '',
        text: 'Persona',
        class: '<?= $cl_nav_3['person'] ?>',
        cf: 'users/add/person'
    };

    sections.institution = {
        icon: '',
        text: 'Institución',
        class: '<?= $cl_nav_3['institution'] ?>',
        cf: 'users/add/institution'
    };
    
    //Secciones para cada rol
    sections_role[1] = ['person', 'institution'];
    sections_role[2] = ['person'];
    sections_role[3] = ['person'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[APP_RID]) 
    {
        var key = sections_role[APP_RID][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');