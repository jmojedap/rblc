<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['public_functions'] = array(
	'/',
	'accounts/',
	'accounts/index',
	'accounts/login',
	'accounts/validate_login',
	'accounts/logout',

	'accounts/signup',
	'accounts/validate_signup',
	'accounts/register',
	'accounts/registered',

	'accounts/activation',
	'accounts/activate',
	'accounts/recovery',
	'accounts/recover',
	'accounts/reset_password',

	'accounts/g_callback',
	'accounts/g_signup',

	'accounts/fb_login',

	'app/logged',
	'app/denied',
	'app/test',
	'app/subscribe',

	'sync/tables_status',
	'sync/get_rows',
	'sync/quan_rows',

	'projects/info',

	'professionals/explore',
	'professionals/profile',

	'home/',
	'pictures/home',
	'pictures/explore',
	'pictures/get',
	'pictures/details',

	'projects/explore',

	'ideabooks/info',
);

//Funciones permitidas para cualquier usuario con sesión iniciada
$config['logged_functions'] = array(
	'accounts/edit',
	
	'users/following',
	'users/followers',

	'projects/favorites',

	'ideabooks/info',
	'ideabooks/my_ideabooks',
	'ideabooks/edit',
	'ideabooks/add',

	'messages/conversation',

	'professionals/images',
	'ideabooks/projects',
);

//Roles permitidos según controlador/función (cf)
$config['function_roles'] = array(
	'projects/edit' => array(2,13),
	'projects/images' => array(2,13),
	'projects/my_projects' => array(2,13),
	'projects/add' => array(2,13),
	'professionals/categories' => array(2,13),
	'pictures/edit' => array(2,13),
);