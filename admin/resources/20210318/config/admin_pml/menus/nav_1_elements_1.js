var nav_1_elements = [
    {
        text: 'Users',
        active: false,
        icon: 'fa fa-user',
        cf: 'users/explore',
        subelements: [],
        sections: ['users/explore', 'users/profile', 'users/import', 'users/add', 'users/notes']
    },
    {
        text: 'Projects / Products',
        active: false,
        icon: 'fa fa-project-diagram',
        cf: 'projects/explore',
        subelements: [],
        sections: ['projects/explore', 'projects/info', 'projects/image', 'projects/edit', 'projects/images', 'projects/descriptors']
    },
    {
        text: 'Ideabooks',
        active: false,
        icon: 'far fa-lightbulb',
        cf: 'ideabooks/explore',
        subelements: [],
        sections: ['ideabooks/explore', 'ideabooks/info', 'ideabooks/images', 'ideabooks/edit']
    },
    {
        text: 'Tags',
        active: false,
        icon: 'fa fa-tag',
        cf: 'tags/explore',
        subelements: [],
        sections: ['tags/explore', 'tags/info', 'tags/edit']
    },
    {
        text: 'Files',
        active: false,
        icon: 'far fa-file',
        cf: 'files/explore',
        subelements: [],
        sections: ['files/explore']
    },
    {
        text: 'Posts',
        active: false,
        icon: 'far fa-file',
        cf: 'posts/explore',
        subelements: [],
        sections: ['posts/explore']
    },
    {
        text: 'Settings',
        active: false,
        icon: 'fa fa-cog',
        cf: '',
        subelements: [
            {
                text: 'General',
                active: false,
                icon: 'fa fa-sliders-h',
                cf: 'admin/options',
                sections: ['admin/acl', 'admin/options', 'admin/colors']
            },
            {
                text: 'Items',
                active: false,
                icon: 'fa fa-bars',
                cf: 'items/manage',
                sections: ['items/manage', 'items/import', 'items/import_e']
            }
        ],
        sections: []
    },
    {
        text: 'Help',
        active: false,
        icon: 'fa fa-question-circle',
        cf: 'app/help',
        subelements: [],
        sections: ['app/help']
    }
];