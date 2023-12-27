<?php
/**
 * @var User $logged_in_user
 * @var string $currentLanguage
 */

use ReCaptcha\ReCaptcha;

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/session.php";

    if (Setting::get("registration_enabled") && !empty($_POST["name"]) && !empty($_POST["email"])) {
        $result = true;
        if (Setting::get("recaptcha_enabled")) {
            if (Setting::get("recaptcha_secret_key") && Setting::get("recaptcha_site_key")) {
                if (isset($_POST['g-recaptcha-response'])) {
                    $recaptcha = new ReCaptcha(Setting::get("recaptcha_secret_key"));
                    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    $result = $resp->isSuccess();
                } else {
                    $result = false;
                }
            }
        }
        if ($result) {
            if (User::where("email", $_POST["email"])->count() > 0) {
                throw new Exception(__("error_email_registered"));
            } else {
                $user = new User();
                $user->setEmail($_POST["email"]);
                $user->setName($_POST["name"]);
                $user->setDateAdded(date('Y-m-d H:i:s'));
                $user->setDevicesLimit(Setting::get("default_devices_limit"));
                $user->setContactsLimit(Setting::get("default_contacts_limit"));
                $user->setIsAdmin(false);
                $random_password = random_str(16);
                $user->setPassword($random_password);
                $user->setApiKey(generateAPIKey());
                $user->setLanguage($currentLanguage);
                if (isValidTimezone($_POST["timeZone"])) {
                    $user->setTimeZone($_POST["timeZone"]);
                }
                $user->setCredits(Setting::get("default_credits"));
                $user->setExpiryDate(Setting::get("default_expire_interval") ? date("Y-m-d H:i:s", time() + (int)Setting::get("default_expire_interval")) : null);
                $user->setDelay(Setting::get("default_delay") !== "" ? Setting::get("default_delay") : 2);
                $user->setUssdDelay(Setting::get("default_ussd_delay") !== "" ? Setting::get("default_ussd_delay") : 0);
                $user->setReportDelivery(Setting::get("default_delivery_reports_enabled") !== "" ? Setting::get("default_delivery_reports_enabled") : 0);
                $user->setUseProgressiveQueue(Setting::get("default_use_progressive_queue") ? 1 : 0);
                $user->setAutoRetry(Setting::get("default_auto_retry_enabled") !== "" ? Setting::get("default_auto_retry_enabled") : 0);
                MysqliDb::getInstance()->startTransaction();
                $user->save();
                $user->assignSharedDevices();
                $serverURL = getServerURL();
                $admin = User::getAdmin();
                $from = array($admin->getEmail(), $admin->getName());
                $to = array($user->getEmail(), $user->getName());
                $subject = __("register_email_subject", ["app" => __("application_title")]);
                $qrCodeFile = $user->getQRCode();
                $user->getLimits($devices, $contacts, $credits, $expiryDate);
                $body = __("register_email_body", ["app" => __("application_title"), "user" => htmlentities($user->getName(), ENT_QUOTES), "userEmail" => $user->getEmail(), "admin" => htmlentities($admin->getName(), ENT_QUOTES), "adminEmail" => $admin->getEmail(), "appUrl" => __("application_url"), "password" => $random_password, "server" => $serverURL, "devices" => $devices, "contacts" => $contacts, "expiryDate" => $expiryDate, "credits" => $credits]);
                try {
                    sendEmail($from, $to, $subject, $body, ["qr-code.png" => $qrCodeFile]);
                } catch (\PHPMailer\PHPMailer\Exception $e) {
                    throw new Exception(__("error_send_email_register", ["errorMessage" => $e->errorMessage()]));
                }
                MysqliDb::getInstance()->commit();
                echo json_encode([
                    'result' => __("success_register", ["app" => __("application_title")])
                ]);
            }
        } else {
            throw new Exception(__("error_captcha_failed"));
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
