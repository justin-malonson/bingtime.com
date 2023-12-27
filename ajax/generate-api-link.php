<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (empty($_POST["mobileNumber"]) || (empty($_POST["option"]) && empty($_POST["devices"])) || ($_POST["type"] === 'sms' && empty($_POST["message"]))) {
        throw new Exception(__("error_missing_fields"));
    } else {
        $mobileNumbers = explode(",", $_POST["mobileNumber"]);
        $number = "";
        $totalNumbers = count($mobileNumbers);
        foreach ($mobileNumbers as $mobileNumber) {
            if (isValidMobileNumber($mobileNumber, $_POST["type"] === 'mms')) {
                if ($totalNumbers > 1) {
                    $number .= "&number[]=" . urlencode($mobileNumber);
                } else {
                    $number = "&number=" . urlencode($mobileNumber);
                }
            } else {
                throw new Exception(__("error_use_valid_number"));
            }
        }
        $message = urlencode($_POST["message"]);
        $queryString = "key={$logged_in_user->getApiKey()}{$number}&message={$message}";
        if (!empty($_POST["option"]) && ($_POST["option"] == 1 || $_POST["option"] == 2)) {
            $queryString .= "&option={$_POST["option"]}";
        } else {
            if (count($_POST["devices"]) > 1) {
                $devices = urlencode(json_encode($_POST["devices"]));
            } else {
                $devices = $_POST["devices"][0];
            }
            $queryString .= "&devices={$devices}";
        }
        if (isset($_POST["type"]) && ($_POST["type"] === "sms" || $_POST["type"] === "mms")) {
            $queryString .= "&type={$_POST["type"]}";
            if ($_POST["type"] === "mms") {
                if (empty($_POST["attachments"])) {
                    if (empty($_POST["message"])) {
                        throw new Exception(__("error_missing_fields"));
                    }
                } else {
                    $attachments = Message::isValidAttachments($_POST["attachments"]);
                    $attachments = urlencode($attachments);
                    $queryString .= "&attachments={$attachments}";
                }
            }
        }
        if (!empty($_POST["useRandomDevice"])) {
            $queryString .= "&useRandomDevice=1";
        }
        $queryString .= "&prioritize={$_POST["prioritize"]}";

        echo json_encode([
            "result" => getServerURL() . "/services/send.php?{$queryString}"
        ]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
