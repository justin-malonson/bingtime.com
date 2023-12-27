<?php

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if ($_SESSION["isAdmin"]) {
        if (isset($_POST["deviceID"])) {
            $shareWith = $_POST["shareWith"] ?? [];
            $shareToAll = $_POST["shareToAll"];
            MysqliDb::getInstance()->startTransaction();
            $device = new Device();
            $device->setID($_POST["deviceID"]);
            $device->setUserID($_SESSION["userID"]);
            if ($device->read()) {
                $device->setUseOwnerSettings(isset($_POST["useOwnerSettings"]) ? 1 : 0);
                $device->setSharedToAll($shareToAll);
                $device->save();
                if ($shareToAll == 1) {
                    $users = User::where('ID', $_SESSION["userID"], '!=')->read_all();
                    if ($users) {
                        $deviceUsers = [];
                        foreach ($users as $user) {
                            $deviceUsers[] = [
                                'deviceID' => $_POST["deviceID"],
                                'userID' => $user->getID()
                            ];
                        }
                        DeviceUser::insertMultiple($deviceUsers, ['active' => 1]);
                    }
                } else if ($shareToAll == 2) {
                    $users = User::where('ID', $_SESSION["userID"], '!=')->read_all();
                    if ($users) {
                        $subscriptions = Subscription::where("Subscription.expiryDate", date("Y-m-d H:i:s"), ">")
                            ->read_all();
                        $subscribedUsers = [];
                        foreach ($subscriptions as $subscription) {
                            if (!in_array($subscription->getUserID(), $subscribedUsers)) {
                                $subscribedUsers[] = $subscription->getUserID();
                            }
                        }
                        $deviceUsers = [];
                        foreach ($users as $user) {
                            if (!in_array($user->getID(), $subscribedUsers)) {
                                $deviceUsers[] = [
                                    'deviceID' => $_POST["deviceID"],
                                    'userID' => $user->getID()
                                ];
                            }
                        }
                        DeviceUser::insertMultiple($deviceUsers, ['active' => 1]);

                        if ($subscribedUsers) {
                            DeviceUser::where('deviceID', $_POST["deviceID"])
                                ->where('userID', $_SESSION["userID"], '!=')
                                ->where('userID', $subscribedUsers, 'IN')
                                ->update_all(['active' => 0]);
                        }
                    }
                } else {
                    $query = DeviceUser::where('deviceID', $_POST["deviceID"])
                        ->where('userID', $_SESSION["userID"], '!=');

                    if ($shareWith) {
                        $query->where('userID', $shareWith, 'NOT IN');
                    }

                    $query->update_all(['active' => 0]);

                    if ($shareWith) {
                        $deviceUsers = [];
                        foreach ($shareWith as $userID) {
                            $deviceUsers[] = [
                                'deviceID' => $_POST["deviceID"],
                                'userID' => $userID
                            ];
                        }
                        DeviceUser::insertMultiple($deviceUsers, ['active' => 1]);
                    }
                }
            }
            MysqliDb::getInstance()->commit();
            echo json_encode([
                'result' => __("success_device_shared")
            ]);
        }
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}
