<?php

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/vendor/autoload.php";

try {
    if (empty($_REQUEST["number"]) || empty($_REQUEST["listID"])) {
        $message = __("error_missing_data");
    } else {
        $contact = new Contact();
        $contact->setNumber($_REQUEST["number"]);
        $contact->setContactsListID($_REQUEST["listID"]);
        if ($contact->read()) {
            if ($contact->getSubscribed()) {
                $contact->setSubscribed(false);
                $contact->save();
                $message = __("success_unsubscribed");
            } else {
                $message = __("error_already_unsubscribed");
            }
        } else {
            $message = __("error_invalid_number");
        }
    }
} catch (Throwable $t) {
    $message = $t->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unsubscribe</title>
</head>

<body>
<h1><?= $message; ?></h1>
</body>

</html>
