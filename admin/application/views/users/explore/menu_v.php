<?php
    $app_cf_index = $this->uri->segment(1) . '_' . $this->uri->segment(2);
    
    $cl_nav_2['users_explore'] = '';
    $cl_nav_2['users_import'] = '';
    $cl_nav_2['users_add'] = '';
    $cl_nav_2['users_newsletter_subscribers'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'users_import_e' ) { $cl_nav_2['users_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_rol = [];
    
    sections.explore = {
        'icon': 'fa fa-search',
        'text': 'Explore',
        'class': '<?php echo $cl_nav_2['users_explore'] ?>',
        'cf': 'users/explore'
    };

    sections.add = {
        'icon': 'fa fa-plus',
        'text': 'New',
        'class': '<?php echo $cl_nav_2['users_add'] ?>',
        'cf': 'users/add'
    };

    sections.newsletter_subscribers = {
        'icon': 'fas fa-newspaper',
        'text': 'Subscribers',
        'class': '<?php echo $cl_nav_2['users_newsletter_subscribers'] ?>',
        'cf': 'users/newsletter_subscribers'
    };
    
    //Secciones para cada rol
    sections_rol.dvlp = ['explore', 'add', 'newsletter_subscribers'];
    sections_rol.admn = ['explore', 'add', 'newsletter_subscribers'];
    
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