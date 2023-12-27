<?php
/**
 * @var User $logged_in_user
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    $user = $logged_in_user;
    if (isset($_GET["user"]) && $_GET["user"] != $_SESSION["userID"] && $_SESSION["isAdmin"]) {
        $user = User::getById($_GET["user"]);
    }
    DeviceUser::setPageLimit($_REQUEST["length"]);
    $page = 1;
    if ($_REQUEST["start"] != 0) {
        $page = (($_REQUEST["start"] - 1) / $_REQUEST["length"]) + 1;
    }
    $deviceUsers = DeviceUser::where("DeviceUser.userID", $user->getID());
    if (!empty($_REQUEST["search"]["value"])) {
        $searchTerm = $_REQUEST["search"]["value"];
        $deviceUsers->where('(DeviceUser.name LIKE ? OR Device.model LIKE ?)', ["%{$searchTerm}%", "%{$searchTerm}%"]);
    }
    if (isset($_GET["order"][0])) {
        $fields = [
            1 => "DeviceUser.name",
            2 => "Device.model",
            3 => "Device.androidVersion",
            4 => "Device.appVersion"
        ];
        if (isset($fields[$_GET["order"][0]["column"]])) {
            $deviceUsers->orderBy($fields[$_GET["order"][0]["column"]], strtoupper($_GET["order"][0]["dir"]));
        }
    }
    $deviceUsers = $deviceUsers->read_all(true, $page);
    $totalCount = DeviceUser::getTotalCount();
    $data = [];
    foreach ($deviceUsers as $deviceUser) {
        if ($deviceUser->isActive()) {
            $device = $deviceUser->getDevice();
            if ($_SESSION["isAdmin"] || $device->getUserID() == $logged_in_user->getID() || $device->getEnabled()) {
                $row = [];
                $disabled =  $device->getUserID() == $logged_in_user->getID() ? "" : "disabled";
                $row[] = "<label><input type='checkbox' name='devices[]' class='remove-devices' onchange='toggleRemove()' value='{$device->getID()}' {$disabled}></label>";
                $name = is_null($deviceUser->getName()) ? 'null' : "'" . htmlentities($deviceUser->getName(), ENT_QUOTES) . "'";
                if ($user->getID() == $logged_in_user->getID()) {
                    $row[] = "<a href=\"#devices\" onclick=\"editDevice({$name}, {$device->getID()})\">" . htmlentities(strval($deviceUser), ENT_QUOTES) . "&nbsp;<i class=\"fa fa-edit\"></i></a>";
                } else {
                    $row[] = htmlentities(strval($deviceUser), ENT_QUOTES);
                }
                $row[] = htmlentities($device->getModel(), ENT_QUOTES);
                $row[] = $device->getAndroidVersion() != null ? $device->getAndroidVersion() : __("null_value");
                $row[] = $device->getAppVersion() != null ? $device->getAppVersion() : __("null_value");
                $row[] = Message::where("Message.deviceID", $device->getID())
                    ->where("Message.userID", $user->getID())
                    ->count();
                $row[] = $deviceUser->isActive() && $device->getEnabled() ? '<label class="label label-success">' . __("connected") . '</label>' : '<label class="label label-danger">' . __("disconnected") . '</label>';
                if ($_SESSION["isAdmin"]) {
                    if ($device->getUserID() == $logged_in_user->getID()) {
                        $sharedWith = $device->sharedToAll ? [] : $device->getUsers();
                        if ($device->sharedToAll) {
                            $sharedWithCount = $device->sharedToAll == 1 ? __('all_users') : __('demo_users');
                        } else {
                            $count = count($sharedWith) - 1;
                            if ($count > 0) {
                                $sharedWithCount = $count > 1 ? $count . " " .__('users') : $count . " " . __('user');
                            } else {
                                $sharedWithCount = __('no_one');
                            }
                        }
                        $sharedWith = json_encode($sharedWith);
                        $row[] = $sharedWithCount . "<a href=\"#devices\" style=\"margin-left: 5px\" onclick=\"shareDevice({$sharedWith}, {$device->getID()}, {$device->getSharedToAll()}, {$device->getUseOwnerSettings()})\"><i class=\"fa fa-share\"></i></a>";
                    } else {
                        $row[] = __('unshareable');
                    }
                }
                $data[] = $row;
            }
        }
    }

    echo json_encode([
        'draw' => (int)$_REQUEST["draw"],
        'recordsFiltered' => $totalCount,
        'recordsTotal' => DeviceUser::where("DeviceUser.userID", $user->getID())->count(),
        'data' => $data
    ]);
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}

