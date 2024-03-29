<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
|Defined by Pacarina
*/

define('APP_NAME', 'Colibri.House');
define('APP_DOMAIN', 'pacarina.net');

define('URL_SYNC', '');
define('URL_APP', 'https://www.pacarina.net/projects/colibri/app/');
define('URL_API', 'https://www.pacarina.net/projects/colibri/admin/');
define('URL_ADMIN', 'https://www.pacarina.net/projects/colibri/admin/');
define('URL_FRONT', 'https://www.pacarina.net/projects/colibri/app/');
define('URL_UPLOADS', 'https://www.pacarina.net/projects/colibri/content/uploads/');
define('URL_RESOURCES', 'https://www.pacarina.net/projects/colibri/resources/20220628/');
define('URL_IMG', 'https://www.pacarina.net/projects/colibri/resources/20220628/images/');
define('URL_BRAND', 'https://www.pacarina.net/projects/colibri/resources/20220628/brands/colibri_blue/');
define('URL_CONTENT', 'https://www.pacarina.net/projects/colibri/content/');

define('BG_DARK', '#ff6529');

define('ENV', 'production');

define('PATH_CONTENT', 'content/');
define('PATH_UPLOADS', 'content/uploads/');
define('PATH_RESOURCES', 'resources/20220628/');

define('TPL_ADMIN', 'templates/admin_pml/main');          //View template for administration
define('TPL_FRONT', 'templates/colibri_blue/main');          //View template for front end

define('K_RCSK', '6Ldrc6wZAAAAAOWbBZwZU2fQSH8_wIji5YO3CHgq');   //ReCaptcha V3 SiteKey
define('K_RCSC', '6Ldrc6wZAAAAALsfGIiWChHDIx8ELDLvMGricJgE');   //ReCaptcha V3 Clave Secreta

define('K_MAXPIXELS', 1400);   //Tamaño máximo del lado más largo de  imagen uploaded
define('K_QUALITY', 90);   //Porcentaje calidad de imagen upoloaded

define('K_FBAI', '320932112435562');                            //Facebook App ID
define('K_FBAK', '0940edf1b1bb3c315a8e3c848e85d909');           //Facebook App Secret Key