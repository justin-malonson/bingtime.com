<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $userID = $logged_in_user->getID();
    if (isset($_GET["user"]) && $_GET["user"] != $_SESSION["userID"] && $_SESSION["isAdmin"]) {
        $userID = $_GET["user"];
    }

    $page = 1;
    if ($_REQUEST["start"] != 0) {
        $page = (($_REQUEST["start"] - 1) / $_REQUEST["length"]) + 1;
    }

    if ($userID) {
        $sims = Sim::getSims($userID);
        $requests = Ussd::where('Ussd.userID', $userID);
    } else {
        $sims = Sim::getSims();
        $requests = new Ussd();
    }
    if (!empty($_REQUEST["search"]["value"])) {
        $searchTerm = $_REQUEST["search"]["value"];
        $requests->Where('(Ussd.request LIKE ? OR Ussd.response LIKE ? OR Ussd.deviceID = ?)', ["%{$searchTerm}%", "%{$searchTerm}%", $searchTerm]);
    }
    if (isset($_GET["order"][0])) {
        $fields = [
            1 => "request",
            3 => "sentDate",
            4 => "responseDate",
            5 => "deviceID",
            6 => "simSlot"
        ];
        if (isset($fields[$_GET["order"][0]["column"]])) {
            $requests->orderBy("Ussd.{$fields[$_GET["order"][0]["column"]]}", strtoupper($_GET["order"][0]["dir"]));
        }
    }
    Ussd::setPageLimit($_REQUEST["length"]);
    $requests = $requests->read_all(true, $page);
    $totalCount = Ussd::getTotalCount();
    $users = [$logged_in_user->getID() => strval($logged_in_user)];
    $devices = [];
    $data = [];
    foreach ($requests as $request) {
        $row = [];
        $row[] = "<label><input type='checkbox' name='requests[]' class='remove-requests' onchange='toggleOptions()' value='{$request->getID()}'></label>";
        $row[] = htmlentities($request->getRequest(), ENT_QUOTES);
        $row[] = nl2br(htmlentities($request->getResponse() ?? '', ENT_QUOTES));
        $row[] = $request->getSentDate()->format("Y-m-d H:i:s");
        $row[] = $request->getResponseDate() != null ? $request->getResponseDate()->format("Y-m-d H:i:s") : __("pending");
        if (is_null($request->getDeviceID())) {
            $row[] = __("unknown_device");
        } else {
            if (!isset($devices[$request->getDeviceID()])) {
                $deviceUser = DeviceUser::getById($request->getDeviceID(), $request->getUserID());
                $devices[$request->getDeviceID()] = htmlentities(strval($deviceUser), ENT_QUOTES);
                $users[$request->getUserID()] = strval($deviceUser->getUser());
             }

            $row[] = $devices[$request->getDeviceID()];
        }
        if ($request->getSimSlot() === null) {
            $row[] = __("default");
        } else {
            if (isset($sims[$request->getDeviceID()][$request->getSimSlot()])) {
                $row[] = $sims[$request->getDeviceID()][$request->getSimSlot()];
            } else {
                $row[] = "SIM #" . ($request->getSimSlot() + 1);;
            }
        }
        if ($_SESSION["isAdmin"]) {
            if (!isset($users[$request->getUserID()])) {
                $user = User::getById($request->getUserID());
                $users[$request->getUserID()] = strval($user);
            }

            $row[] = $users[$request->getUserID()];
        }
        $data[] = $row;
    }

    echo json_encode([
        'draw' => (int)$_REQUEST["draw"],
        'recordsFiltered' => $totalCount,
        'recordsTotal' => $userID ? Ussd::where('Ussd.userID', $userID)->count() : Ussd::count(),
        'data' => $data
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
