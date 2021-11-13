<?php
    $app_cf_index = $this->uri->segment(2) . '_' . $this->uri->segment(3);
    
    $cl_nav_2['users_explore'] = '';
    $cl_nav_2['users_import'] = '';
    $cl_nav_2['users_add'] = '';
    $cl_nav_2['users_newsletter_subscribers'] = '';
    $cl_nav_2['users_invitations'] = '';
    
    $cl_nav_2[$app_cf_index] = 'active';
    if ( $app_cf_index == 'users_import_e' ) { $cl_nav_2['users_import'] = 'active'; }
?>

<script>
    var sections = [];
    var nav_2 = [];
    var sections_role = [];
    
    sections.explore = {
        icon: 'fa fa-search',
        text: 'Explore',
        class: '<?= $cl_nav_2['users_explore'] ?>',
        cf: 'users/explore'
    };

    sections.add = {
        icon: 'fa fa-plus',
        text: 'New',
        class: '<?= $cl_nav_2['users_add'] ?>',
        cf: 'users/add'
    };

    sections.newsletter_subscribers = {
        icon: 'fas fa-newspaper',
        text: 'Subscribers',
        class: '<?= $cl_nav_2['users_newsletter_subscribers'] ?>',
        cf: 'users/newsletter_subscribers'
    };
    
    sections.invitations = {
        icon: 'far fa-envelop-open',
        text: 'Invitations',
        class: '<?= $cl_nav_2['users_invitations'] ?>',
        cf: 'users/invitations/1/?status=00&role=13&fe1=00&',
        anchor: true
    };
    
    //Secciones para cada rol
    sections_role[1] = ['explore', 'newsletter_subscribers', 'invitations'];
    sections_role[2] = ['explore', 'newsletter_subscribers', 'invitations'];
    sections_role[3] = ['explore', 'newsletter_subscribers', 'invitations'];

    
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