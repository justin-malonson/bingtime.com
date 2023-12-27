<?php
/**
 * @noinspection BadExpressionStatementJS
 * @noinspection CommaExpressionJS
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        $db = MysqliDb::getInstance();
        $users = $db->rawQuery("SELECT User.name as UserName, User.email as UserEmail, User.delay as UserDelay, User.dateAdded as UserDateAdded, User.lastLogin as UserLastLogin, User.lastLoginIP as UserLastLoginIP, User.devicesLimit as UserDevicesLimit, User.contactsLimit as UserContactsLimit, User.credits as UserCredits, User.expiryDate as UserExpiryDate, User.timeZone as UserTimeZone, User.ID as UserID, (SELECT COUNT(Message.ID) FROM Message WHERE userID = User.ID) as TotalMessages, (SELECT COUNT(Device.ID) FROM Device WHERE userID = User.ID AND enabled = TRUE) as DevicesConnected FROM User WHERE User.isAdmin = 0");
        $data = [];
        foreach ($users as $user) {
            $row = [];
            $row[] = "<label><input type=\"checkbox\" name=\"users[]\" class=\"remove-users\" onchange=\"toggleRemove()\" value=\"{$user["UserID"]}\"></label>";
            $row[] = htmlentities($user["UserName"], ENT_QUOTES);
            $devicesLimit = is_null($user["UserDevicesLimit"]) ? 'null' : $user["UserDevicesLimit"];
            $contactsLimit = is_null($user["UserContactsLimit"]) ? 'null' : $user["UserContactsLimit"];
            $credits = is_null($user["UserCredits"]) ? 'null' : $user["UserCredits"];
            $expiryDateLocal = is_null($user["UserExpiryDate"]) ? null : getDisplayTime($user["UserExpiryDate"])->format("Y-m-d\TH:i");
            $expiryDate = is_null($expiryDateLocal) ? 'null' : "'{$expiryDateLocal}'";
            $row[] = "<a href=\"#users\" onclick=\"editUser({$expiryDate}, {$credits}, {$devicesLimit}, {$contactsLimit}, {$user["UserID"]})\">{$user["UserEmail"]}</a>";
            $row[] = $user["TotalMessages"];
            $row[] = $user["DevicesConnected"];
            $row[] = is_null($user["UserDevicesLimit"]) ? "&infin;" : $user["UserDevicesLimit"];
            $row[] = is_null($user["UserContactsLimit"]) ? "&infin;" : $user["UserContactsLimit"];
            $row[] = is_null($user["UserCredits"]) ? "&infin;" : $user["UserCredits"];
            $row[] = is_null($user["UserExpiryDate"]) ? "&infin;" : getDisplayTime($user["UserExpiryDate"])->format("Y-m-d H:i:s");
            $row[] = $user["UserDelay"];
            $row[] = getDisplayTime($user["UserDateAdded"])->format("Y-m-d H:i:s");
            $row[] = $user["UserLastLogin"] == null ? __("never") : getDisplayTime($user["UserLastLogin"])->format("Y-m-d H:i:s");
            $row[] = $user["UserLastLoginIP"] ?? __("no_data") ;
            $data[] = $row;
        }

        echo json_encode([
            "data" => $data
        ]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
