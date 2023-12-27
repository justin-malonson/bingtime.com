<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (isset($_POST["application_title"])) {
            User::isValidDelay($_POST["default_delay"]);
            $uploads = ["logo_src", "favicon_src"];
            foreach ($uploads as $upload) {
                if (isset($_FILES[$upload]['tmp_name']) && is_uploaded_file($_FILES[$upload]['tmp_name'])) {
                    $tempPath = $_FILES[$upload]['tmp_name'];
                    $filename = basename($_FILES[$upload]['name']);
                    $fileExtension = pathinfo($_FILES[$upload]['name'], PATHINFO_EXTENSION);
                    $allowed_extensions = ["png", "jpg", "jpeg", 'ico'];
                    if (!in_array($fileExtension, $allowed_extensions)) {
                        throw new Exception(__("error_blocked_file_extension"));
                    }
                    $uploadDirectory = __DIR__ . "/../uploads";
                    if (is_dir($uploadDirectory) || mkdir($uploadDirectory, 0755)) {
                        if (move_uploaded_file($tempPath, "{$uploadDirectory}/{$filename}")) {
                            $_POST[$upload] = "uploads/{$filename}";
                        } else {
                            throw new Exception(__("error_uploading_logo"));
                        }
                    } else {
                        throw new Exception(__("error_creating_directory", ["name" => "upload"]));
                    }
                }
            }
            if ($json = getFirebaseServiceAccountJson()) {
                $_POST["firebase_service_account_json"] = $json;
            }
            $oldPaypalClientID = Setting::get("paypal_client_id");
            $oldPaypalSecret = Setting::get("paypal_secret");
            MysqliDb::getInstance()->startTransaction();
            if (!isset($_POST["default_credits"])) {
                $_POST["default_credits"] = null;
            }
            if (!isset($_POST["default_devices_limit"])) {
                $_POST["default_devices_limit"] = null;
            }
            if (!isset($_POST["default_contacts_limit"])) {
                $_POST["default_contacts_limit"] = null;
            }
            Setting::apply($_POST);
            if (!empty($_POST["paypal_enabled"]) && !empty($_POST["paypal_client_id"]) && !empty($_POST["paypal_secret"])) {
                if ($oldPaypalClientID != $_POST["paypal_client_id"] || $oldPaypalSecret != $_POST["paypal_secret"]) {
                    $serverUrl = getServerURL();
                    if (str_starts_with($serverUrl, "https")) {
                        PayPal::createProduct($serverUrl);
                        PayPal::createWebHook(["PAYMENT.SALE.COMPLETED", "PAYMENT.SALE.REVERSED", "PAYMENT.SALE.REFUNDED"], sprintf("%s/webhooks/payment-sale-completed.php", $serverUrl));
                        PayPal::createWebHook(["BILLING.SUBSCRIPTION.CANCELLED", "BILLING.SUBSCRIPTION.PAYMENT.FAILED"], sprintf("%s/webhooks/billing-subscription-cancelled.php", $serverUrl));
                        $plans = Plan::read_all();
                        foreach ($plans as $plan) {
                            $plan->setPaypalPlanID(PayPal::createPlan($serverUrl, $plan));
                            $plan->save();
                        }
                    } else {
                        throw new Exception(__("error_paypal_requires_ssl"));
                    }
                }
            }
            MysqliDb::getInstance()->commit();
            echo json_encode([
                "result" => __("success_save_settings")
            ]);
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
