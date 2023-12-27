<?php
/**
 * @var User $logged_in_user
 */

require_once __DIR__ . "/includes/login.php";

$user = $logged_in_user;
if (isset($_GET["user"]) && $_GET["user"] != $_SESSION["userID"] && $_SESSION["isAdmin"]) {
    $user = User::getById($_GET["user"]);
}
header('Content-type: image/png');
echo $user->getQRCode();