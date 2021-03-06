<?php
/*
 * jQuery File Upload Plugin PHP Example 5.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

// Make sure Silex' session is instantiated
require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../bootstrap.php';

session_start();

// Don't do anything if we're not logged in..
if (!isset($_SESSION['_sf2_attributes']['user']['id'])) {
    echo "Not logged in.";
    die();
}                    

// Make sure the folder exists.
makeDir(__DIR__.'/../../../files/'.date('Y-m'));

require('upload.class.php');

$upload_handler = new UploadHandler(array(
    'upload_dir' => dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))).'/files/'.date('Y-m')."/",
    'upload_url' => '/files/'.date('Y-m')."/",
    'accept_file_types' => '/\.(gif|jpe?g|png|zip|tgz|txt|md|docx?|pdf|xlsx?|pptx?)$/i'
));

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Disposition: inline; filename="files.json"');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
        break;
    case 'HEAD':
    case 'GET':
        $upload_handler->get();
        break;
    case 'POST':
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            $upload_handler->delete();
        } else {
            $upload_handler->post();
        }
        break;
    case 'DELETE':
        $upload_handler->delete();
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
}
