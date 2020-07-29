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
        'icon': '',
        'text': 'Persona',
        'class': '<?php echo $cl_nav_3['person'] ?>',
        'cf': 'users/add/person'
    };

    sections.institution = {
        'icon': '',
        'text': 'Institución',
        'class': '<?php echo $cl_nav_3['institution'] ?>',
        'cf': 'users/add/institution'
    };
    
    //Secciones para cada rol
    sections_role.dvlp = ['person', 'institution'];
    sections_role.admn = ['person'];
    sections_role.edtr = ['person'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_role[app_r]) 
    {
        var key = sections_role[app_r][key_section];   //Identificar elemento
        nav_3.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_3_v');