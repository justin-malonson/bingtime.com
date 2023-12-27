<?php
/**
 * @var string $currentLanguage
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/session.php";

    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $user = User::login($_POST["email"], $_POST["password"]);
        if ($user) {
            $user->setLastLogin(date('Y-m-d H:i:s'));
            $user->setLastLoginIP(getUserIpAddress());
            $user->setLanguage($currentLanguage);
            $user->save();
            $_SESSION["userID"] = $user->getID();
            $_SESSION["email"] = $user->getEmail();
            $_SESSION["name"] = $user->getName();
            $_SESSION["isAdmin"] = $user->getisAdmin();
            $_SESSION["timeZone"] = $user->getTimeZone();
            $totalDevices = Device::where('userID', $user->getID())->count();
            if ($totalDevices <= 0) {
                $_SESSION["showTutorial"] = true;
            }

            echo json_encode([
                "result" => true
            ]);
        } else {
            throw new Exception(__("error_incorrect_credentials"));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}