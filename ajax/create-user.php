<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (!empty($_POST["name"]) && !empty($_POST["email"])) {
            if (User::where("email", $_POST["email"])->count() > 0) {
                throw new Exception(__("error_email_registered"));
            } else {
                $user = new User();
                if (isset($_POST["devicesLimit"])) {
                    if (!ctype_digit($_POST["devicesLimit"])) {
                        throw new Exception(__("error_max_devices_not_number"));
                    }
                    $user->setDevicesLimit($_POST["devicesLimit"]);
                }
                if (isset($_POST["contactsLimit"])) {
                    if (ctype_digit($_POST["contactsLimit"])) {
                        $user->setContactsLimit($_POST["contactsLimit"]);
                    } else {
                        throw new Exception(__("error_max_contacts_not_number"));
                    }
                }
                if (isset($_POST["credits"])) {
                    if (!ctype_digit($_POST["credits"])) {
                        throw new Exception(__("error_credits_not_number"));
                    }
                    $user->setCredits($_POST["credits"]);
                }
                if (isset($_POST["expiryDate"])) {
                    $user->setExpiryDate(getDatabaseTime($_POST["expiryDate"])->format("Y-m-d H:i:s"));
                }
                $user->setName($_POST["name"]);
                $user->setEmail($_POST["email"]);
                $user->setIsAdmin(false);
                $random_password = random_str(16);
                $user->setPassword($random_password);
                $user->setApiKey(generateAPIKey());
                $user->setDelay(Setting::get("default_delay") !== "" ? Setting::get("default_delay") : 2);
                $user->setUssdDelay(Setting::get("default_ussd_delay") !== "" ? Setting::get("default_ussd_delay") : 0);
                $user->setReportDelivery(Setting::get("default_delivery_reports_enabled") !== "" ? Setting::get("default_delivery_reports_enabled") : 0);
                $user->setUseProgressiveQueue(Setting::get("default_use_progressive_queue") ? 1 : 0);
                $user->setAutoRetry(Setting::get("default_auto_retry_enabled") !== "" ? Setting::get("default_auto_retry_enabled") : 0);
                $user->setDateAdded(date('Y-m-d H:i:s'));
                MysqliDb::getInstance()->startTransaction();
                $user->save();
                $user->assignSharedDevices();
                $serverURL = getServerURL();
                $from = array($_SESSION["email"], $_SESSION["name"]);
                $to = array($user->getEmail(), $user->getName());
                $subject = __("register_email_subject", ["app" => __("application_title")]);
                $qrCodeFile = $user->getQRCode();
                $user->getLimits($devices, $contacts, $credits, $expiryDate);
                $body = __("register_email_body", ["app" => __("application_title"), "user" => htmlentities($user->getName(), ENT_QUOTES), "userEmail" => $user->getEmail(), "admin" => htmlentities($_SESSION["name"], ENT_QUOTES), "adminEmail" => $_SESSION["email"], "appUrl" => __("application_url"), "password" => $random_password, "server" => $serverURL, "devices" => $devices, "contacts" => $contacts, "expiryDate" => $expiryDate, "credits" => $credits]);
                try {
                    sendEmail($from, $to, $subject, $body, ["qr-code.png" => $qrCodeFile]);
                } catch (\PHPMailer\PHPMailer\Exception $e) {
                    throw new Exception(__("error_send_email_register", ["errorMessage" => $e->errorMessage()]));
                }
                MysqliDb::getInstance()->commit();
                echo json_encode([
                    "result" => __("success_registration")
                ]);
            }
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
