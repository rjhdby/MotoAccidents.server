<?php
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');

function __autoload($class_name)
{
    /** @noinspection PhpIncludeInspection */
    require_once 'class/' . $class_name . '.php';
}

$_GET['test'] = 1;
switch ($_GET['m']) {
    case 'auth':
        $method = new Auth($_GET);
        break;
    case 'list':
        $method = new GetList($_GET);
        break;
    case 'acc':
        $method = new CreateAccident($_GET);
        break;
    case 'accStatus':
        $method = new ChangeAccidentStatus($_GET);
        break;
    case 'comment':
        $method = new CreateComment($_GET);
        break;
    case 'commentStatus':
        $method = new ChangeCommentStatus($_GET);
        break;
    case 'gcm':
        $method = new RegisterGCM($_GET);
        break;
    case 'apns':
        $method = new RegisterAPNS($_GET);
        break;
    case 'volunteerStatus':
        $method = new ChangeVolunteerStatus($_GET);
        break;
    case 'userRole':
        $method = new ChangeUserRole($_GET);
        break;
    case 'getAccident':
        $method = new GetAccident($_GET);
        break;
    default:
        $method = new WrongMethod();
}

print_r(json_encode($method->getResult()));
