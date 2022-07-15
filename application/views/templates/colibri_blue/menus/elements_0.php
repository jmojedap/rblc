<script>
    var suscription_type = '<?= $this->session->userdata('suscription_type'); ?>';
    var navbar_elements = [
        {
            id: 'nav_1_girls',
            text: 'Bonitas',
            active: false,
            style: '',
            icon: 'fa fa-fw fa-heart',
            cf: 'girls/explore/',
            submenu: false,
            subelements: []
        },
        {
            id: 'nav_1_pricing',
            text: 'Precios',
            active: false,
            style: '',
            icon: 'fa fa-fw fa-dollar-sign',
            cf: 'products/pricing/',
            submenu: false,
            subelements: []
        }
    ];


    navbar_elements.splice(1,1);
    if ( suscription_type == 1 )
    {
        
    }
</script>