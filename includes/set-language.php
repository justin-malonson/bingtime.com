<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$currentLanguage = $defaultLanguage = PRIMARY_LANGUAGE;
if (isset($_GET["language"])) {
    $currentLanguage = $_GET["language"];
} else if (isset($logged_in_user)) {
    $currentLanguage = $logged_in_user->getLanguage();
} else if (isset($_COOKIE["language"])) {
    $currentLanguage = $_COOKIE["language"];
} else if (defined("DB_SERVER") && Setting::get("default_language")) {
    $currentLanguage = Setting::get("default_language");
}

$currentLanguage = setLanguage($currentLanguage);

if (!$currentLanguage) {
    exit("Unable to find any language file.");
}

if (empty($_COOKIE["language"]) || $currentLanguage !== $_COOKIE["language"]) {
    if (PHP_VERSION_ID < 70300) {
        setcookie("language", $currentLanguage, strtotime("+1 year"), "/; SameSite=Lax", $_SERVER["HTTP_HOST"]);
    } else {
        setcookie("language", $currentLanguage, [
            'expires' => strtotime("+1 year"),
            'path' => '/',
            'domain' => $_SERVER["HTTP_HOST"],
            'samesite' => 'Lax'
        ]);
    }
}

if (defined("DB_SERVER")) {
    if (Setting::get("default_language")) {
        $defaultLanguage = Setting::get("default_language");
    }

    $emails = ["register_email_subject", "reset_password_email_subject", "reset_password_link_email_subject", "edit_user_subject", "register_email_body", "reset_password_email_body", "reset_password_link_email_body", "edit_user_email_body"];
    foreach (Setting::all() as $name => $value) {
        if ($currentLanguage !== $defaultLanguage && in_array($name, $emails)) {
            continue;
        }
        if (isset($lang[$name])) {
            $lang[$name] = $value;
        }
    }
}