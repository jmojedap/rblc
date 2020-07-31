<script>
    var suscription_type = '<?php echo $this->session->userdata('suscription_type'); ?>';
    var navbar_elements = [
        {
            id: 'nav_1_pricing',
            text: '¿Cómo pagar?',
            active: false,
            style: '',
            icon: 'fa fa-fw fa-dollar-sign',
            cf: 'products/pricing/',
            submenu: false,
            subelements: []
        }
    ];

    if ( suscription_type == 1 )
    {
        navbar_elements.splice(1,1);
    }
</script>