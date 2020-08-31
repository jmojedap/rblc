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
        text: 'Projects',
        active: false,
        icon: 'fa fa-project-diagram',
        cf: 'projects/explore',
        subelements: [],
        sections: ['projects/explore', 'projects/info', 'projects/image', 'projects/edit', 'projects/images', 'projects/descriptors']
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
        text: 'Data',
        active: false,
        icon: 'fa fa-table',
        cf: '',
        subelements: [
            {
                text: 'Posts',
                active: false,
                icon: 'fa fa-newspaper',
                cf: 'posts/explore',
                sections: ['posts/explore', 'posts/add', 'posts/info', 'posts/edit', 'posts/import', 'posts/comments', 'posts/image']
            },
            {
                text: 'Files',
                active: false,
                icon: 'fa fa-file',
                cf: 'files/explore',
                sections: ['files/explore', 'files/add', 'files/edit', 'files/tags', 'files/cropping']
            }
        ],
        sections: []
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
                cf: 'admin/acl',
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
    }
];