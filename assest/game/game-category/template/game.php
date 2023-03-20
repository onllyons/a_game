<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../../../../backend/db.php";
require_once __DIR__ . "/GameClass.php";
require_once __DIR__ . "/functions.php";

$method = null;

if (isset($_REQUEST['method'])) {
    $method = $_REQUEST['method'];
}
$data = [];

$game = new Game($con, $ex);

switch ($method) {
    case 'start':
        $data = $game->start();
        break;

    case 'info':
        $data = $game->info();
        break;

    case 'help':
        $data = $game->help();
        break;

    case 'reload':
        $data = $game->reload();
        break;

    default:
        $data = ['status' => 0, 'messages' => 'Method not found.'];
}
echo json_encode($data);
