<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['comments_explore'] = '';
    $cl_nav_2['comments_add'] = '';
    $cl_nav_2['comments_import'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    //if ( $app_cf == 'comments/explore' ) { $cl_nav_2['comments_explore'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        'icon': 'fa fa-list-alt',
        'text': 'Explorar',
        'class': '<?php echo $cl_nav_2['comments_explore'] ?>',
        'cf': 'comments/explore'
    };

    sections.add = {
        'icon': 'fa fa-plus',
        'text': 'Nuevo',
        'class': '<?php echo $cl_nav_2['comments_add'] ?>',
        'cf': 'comments/add'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'add'];
    sections_rol.admn = ['explore'];
    
    //Recorrer el sections del rol actual y cargarlos en el menú
    for ( key_section in sections_rol[app_r]) 
    {
        //console.log(sections_rol[rol][key_section]);
        var key = sections_rol[app_r][key_section];   //Identificar elemento
        nav_2.push(sections[key]);    //Agregar el elemento correspondiente
    }
</script>

<?php
$this->load->view('common/nav_2_v');