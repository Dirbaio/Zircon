<?php

$startTime = microtime(true);
$queryCount = 0;

// Error handling
//==============================

error_reporting(E_ALL ^ E_NOTICE | E_STRICT);


class FailException extends Exception
{
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}

function fail($why) {
    throw new FailException($why);
}

function my_error_handler()
{
    $last_error = error_get_last();
    if ($last_error && ($last_error['type']==E_ERROR || $last_error['type']==E_USER_ERROR))
        header('HTTP/1.1 500 Internal Server Error');
}
register_shutdown_function('my_error_handler');


// Load main module
//============================

require(__DIR__.'/ModuleHandler.php');
require(__DIR__.'/vendor/autoload.php');
ModuleHandler::init();
ModuleHandler::loadModule('/modules/main');
ModuleHandler::loadModule('/url_styles/rewrite');
ModuleHandler::loadModule('/themes/cheese');

if(isset($_COOKIE['mobileversion']) && $_COOKIE['mobileversion'] && $_COOKIE['mobileversion'] != 'false')
    ModuleHandler::loadModule('/layouts/mobile');
else
    ModuleHandler::loadModule('/layouts/nsmbhd');

// Run the page
//============================


function getPages()
{
    $totalUrls = [];
    foreach(ModuleHandler::getFilesMatching("/Urls.php") as $file) {
        require($file);
        foreach($urls as $key => &$val) {
            $val = substr($file, 0, strlen($file)-8) . 'pages/' . $val . '.php';
        }
        $totalUrls = array_merge($totalUrls, $urls);
    }

    return $totalUrls;
}

function getBase() {
    $base = $_SERVER["SCRIPT_NAME"];
    $idx = strrpos($base, '/');
    if($idx !== false)
        $base = substr($base, 0, $idx+1);
    return $base;
}

function renderPage($template, $vars)
{
    global $config;

    $navigation = array(
        array('url' => Url::format('/'), 'title' => __('Main')),
        array('url' => Url::format('/members'), 'title' => __('Members')),
        array('url' => Url::format('/online'), 'title' => __('Online users')),
        array('url' => Url::format('/search'), 'title' => __('Search')),
        array('url' => Url::format('/lastposts'), 'title' => __('Last posts')),
        array('url' => Url::format('/faq'), 'title' => __('FAQ/Rules')),
    );

    $user = Session::get();

    if($user)
        $userpanel = array(
            array('user' => $user),
            array('url' => Url::format('/u/#-:/edit', $user['id'], $user['name']), 'title' => __('Edit profile')),
            array('url' => Url::format('/u/#-:/messages', $user['id'], $user['name']), 'title' => __('Messages')),
            array('js' => "logout()", 'title' => __('Log out')),
        );
    else
        $userpanel = array(
            array('url' => Url::format('/register'), 'title' => __('Register')),
            array('url' => Url::format('/login'), 'title' => __('Log in')),
        );

     $onlineFid = 0;
     if(isset($vars['forum']))
         $onlineFid = $vars['forum']['id'];

     global $is404;
     if($is404) {
        header('HTTP/1.0 404 Not Found');
        header('Status: 404 Not Found');
         $onlineFid = -1;
     }

    $layout = array(
        'template' => $template,
        'css' => ModuleHandler::toWebPath(ModuleHandler::getFilesMatching('/css/**.css')),
        'js' => ModuleHandler::toWebPath(ModuleHandler::getFilesMatching('/js/**.js')),
        'title' => Config::get('title'),
        'pora' => Config::get('pora'),
        'poratext' => Config::get('poratext'),
        'poratitle' => Config::get('poratitle'),
        'views' => Records::getViewCounter(),
        'user' => $user,
        'navigation' => $navigation,
        'userpanel' => $userpanel,
        'onlineUsers' => OnlineUsers::update($onlineFid),
        'base' => getBase(),
        'csrftoken' => Csrf::get(),
    );
    $vars['layout'] = $layout;
    $vars['loguser'] = Session::get();

    if(!isset($vars['breadcrumbs']) || !is_array($vars['breadcrumbs']))
        throw new Exception('breadcrumbs not found in vars, must be there and be an array');
    if(!isset($vars['actionlinks']) || !is_array($vars['actionlinks']))
        throw new Exception('actionlinks not found in vars, must be there and be an array');

    array_unshift($vars['breadcrumbs'],
        array('url' => Url::format('/'), 'title' => __('Main')));

    Template::render('layout/main.html', $vars);

}

function matchPage($method, $path) {
    $pages = getPages();

    $path = "$method $path";
    foreach($pages as $page => $pagefile)
    {
        //match $path against $page
        $names = array();
        $makenumber = array();
        $pattern = preg_replace_callback('/(:|#|\$)([a-zA-Z][a-zA-Z0-9]*|)/',
            function($matches) use (&$names, &$makenumber) {
                if($matches[1] == '#')
                    $regex = '-?[0-9]+';
                else if($matches[1] == '$')
                    $regex = '[^/]+';
                else
                    $regex = '[a-zA-Z0-9-_]+';
                if($matches[2]) {
                    $name = $matches[2];
                    $names[] = $name;
                    $makenumber[$name] = $matches[1] == '#';
                    return '(?P<'.$name.'>'.$regex.')';
                }
                else
                    return $regex;
            },
            $page
        );
        if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
            $input = array();
            foreach($names as $name) {
                $input[$name] = $matches[$name];
                if($makenumber[$name])
                    $input[$name] = (int) $input[$name];
            }
            return [$pagefile, $input];
        }
    }
    return null;
}

function cleanUpPath($path) {
    //Kill trailing and extra slashes.
    $origpath = $path;
    $path = preg_replace('#/+$#', '', $path);
    $path = preg_replace('#//+#', '/', $path);
    if($path == '') $path = '/';
    if($path != $origpath)
        Url::redirect($path);
    return $path;
}

function run() {
    $path = UrlStyle::getPath();
    $path = cleanUpPath($path);

    $match = matchPage($_SERVER['REQUEST_METHOD'], $path);
    if($match) {
        $pagefile = $match[0];
        $pageparams = $match[1];

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_FILES['file'])) {
                $input = array();
                Csrf::check($_POST['csrftoken']);
            } else {
                $input = json_decode(file_get_contents('php://input'), true);
                if(!is_array($input) || json_last_error() !== JSON_ERROR_NONE)
                    die("Invalid JSON data");
                Csrf::check($input['csrftoken']);
            }
        }
        else
            $input = $_GET;

        $input = array_merge($input, $pageparams);
        $input['input'] = $input;
    } else {
        $pagefile = __DIR__.'/modules/main/pages/404.php';
        $input = array();
        global $is404;
        $is404 = true;
    }

    require($pagefile);

    //Calculate parameters
    $params = array();
    $refFunc = new ReflectionFunction('request');
    foreach($refFunc->getParameters() as $param)
    {
        if(isset($input[$param->name]))
            $params[] = $input[$param->name];
        else if($param->isDefaultValueAvailable())
            $params[] = $param->getDefaultValue();
        else
            fail('Missing parameter: '.$param->name);
    }

    //Call the thing
    call_user_func_array('request', $params);
}

try {
    run();
}
catch(FailException $e) {
    http_response_code(400);
    json(array(
        "message" => $e->getMessage(),
    ));
}
