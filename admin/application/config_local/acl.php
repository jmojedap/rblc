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
	'accounts/check_email',
	'accounts/validate_signup',
	'accounts/register',
	'accounts/registered',

	'accounts/activation',
	'accounts/activate',
	'accounts/recovery',
	'accounts/recovery_email',
	'accounts/recover',
	'accounts/reset_password',

	'accounts/g_callback',
	'accounts/g_signup',

	'accounts/validate_facebook_login',

	'app/logged',
	'app/denied',
	'app/test',
	'app/save_subscription',//Colibri

	'sync/tables_status',
	'sync/get_rows',
	'sync/quan_rows',

	'comments/element_comments',

	'accounts/fb_login',

	'users/get_social_links',

	'professionals/get_info',
	'professionals/get',
	'professionals/get_images',

	'projects/get',//Colibri

	'pictures/get',//Colibri
	'pictures/get_random',//Colibri
	'pictures/get_details',//Colibri

	'ideabooks/get_images',//Colibri
);

//Funciones permitidas para cualquier usuario con sesión iniciada
$config['logged_functions'] = array(
	'accounts/change_password',
	'accounts/edit',
	'accounts/password',
	'accounts/profile',
	'accounts/remove_image',
	'accounts/save_social_links',
	'accounts/set_image',
	'accounts/update',
	'accounts/validate',

	'app/help',

	'comments/delete',
	'comments/save',

	'files/alt_like',
	'files/crop',

	'messages/conversation',
	'messages/conversations',
	'messages/create_conversation',
	'messages/delete',
	'messages/get',
	'messages/send_message',

	'posts/alt_like',
	'posts/delete',
	'posts/delete_meta',

	'users/alt_follow',
	'users/get_following',
	'users/get_social_links',
	'users/username',

	'ideabooks/add_image',
	'ideabooks/add_project',
	'ideabooks/get',
	'ideabooks/get_images',
	'ideabooks/get_projects',
	'ideabooks/insert',
	'ideabooks/update'
);

//Roles permitidos según controlador/función (cf)
$config['function_roles'] = array(
	'users/explore' => array(1,2,13),
	'users/insert' => array(1,2,13),
	'users/profile' => array(2,13),
	'users/edit' => array(2,13),
	'users/add' => array(2,13),
	'users/validate_row' => array(2,13),
	'users/explore_table' => array(2,13),
	'users/import_e' => array(2,13),
	'users/import' => array(2,13),
	'users/update' => array(2,13),
	'users/set_image' => array(2,13),
	'users/remove_image' => array(2,13),
	'projects/update' => array(2,13),
	'projects/get_images' => array(2,13),
	'projects/set_main_image' => array(2,13),
	'projects/add_image' => array(2,13),
	'projects/delete_image' => array(2,13),
	'projects/insert' => array(2,13),
	'projects/update_full' => array(2,13),
	'files/delete' => array(2,13),
	'files/upload' => array(2,13),
	'files/update' => array(2,13),
	'files/update_full' => array(2,13),
	'professionals/update_categories' => array(2,13)
);