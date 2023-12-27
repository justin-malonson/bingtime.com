<?php
/**
 * @var User $logged_in_user
 * @var Message[] $messages
 * @var array $data Multiline array of devices grouped by user ID.
 */

try {
    require_once __DIR__ . "/../includes/ajax_protect.php";
    require_once __DIR__ . "/../includes/login.php";

    if (isset($_POST["startDate"]) && isset($_POST["endDate"])) {
        $start_date = $_POST["startDate"];
        $end_date = $_POST["endDate"];

        $sims = $_SESSION["isAdmin"] ? Sim::getSims() : Sim::getSims($_SESSION["userID"]);

        require_once __DIR__ . "/../includes/user-devices.php";
        require_once __DIR__ . "/../includes/search.php";

        $timeLine = [];
        $numbers = [];
        $date = null;
        $totalCount = Message::getTotalCount();
        foreach ($messages as $message) {
            $numbers[] = $message->getNumber();
        }
        $contacts = $logged_in_user->getContacts($numbers);
        foreach ($messages as $message) {
            $sentDate = $message->getSentDate();
            if ($date !== $sentDate->format('D, d M Y')) {
                $date = $sentDate->format('D, d M Y');
            }

            if (!isset($timeLine[$date][$message->getUserID()][$message->getDeviceID()]["messages"])) {
                $timeLine[$date][$message->getUserID()][$message->getDeviceID()]["messages"] = [];
                if (!isset($_POST["device"]) || $_POST["device"] === 'null') {
                    $device = $data[$message->getUserID()][$message->getDeviceID()];
                    $link = "messages.php?device={$message->getDeviceID()}";
                    if ($_SESSION["isAdmin"]) {
                        if (!isset($_POST["user"]) || $_POST["user"] != $message->getUserID()) {
                            $userName = htmlentities($users[$message->getUserID()]->getName(), ENT_QUOTES);
                            $device = "{$userName} ({$device})";
                        }
                        $link = "messages.php?user={$message->getUserID()}&device={$message->getDeviceID()}";
                    }
                    $timeLine[$date][$message->getUserID()][$message->getDeviceID()]["header"] = ["title" => $device, "link" => $link];
                }
            }

            switch ($message->getStatus()) {
                case "Pending" :
                    $statusLabel = "warning";
                    $statusColor = "yellow";
                    break;
                case "Queued" :
                    $statusLabel = "info";
                    $statusColor = "aqua";
                    break;
                case "Delivered":
                case "Sent" :
                    $statusLabel = "success";
                    $statusColor = "green";
                    break;
                case "Scheduled" :
                case "Received" :
                    $statusLabel = "primary";
                    $statusColor = "light-blue";
                    break;
                case "Canceled" :
                    $statusLabel = "default";
                    $statusColor = "grey";
                    break;
                default :
                    $statusLabel = "danger";
                    $statusColor = "red";
            }

            $timeLineItem = [];
            $timeLineItem["statusLabel"] = $statusLabel;
            $timeLineItem["statusColor"] = $statusColor;
            $timeLineItem["time"] = $message->getSentDate()->format('H:i:s');
            if (isset($contacts[$message->getNumber()])) {
                $name = $contacts[$message->getNumber()];
                $numberText = "$name<span class=\"hidden-xs\">&nbsp;({$message->getNumber()})</span>";
            } else {
                $numberText = $message->getNumber();
            }
            $number = urlencode($message->getNumber());
            $timeLineItem["header"] = "<label><input type=\"checkbox\" name=\"messages\" class=\"resend-checkbox\" value=\"{$message->getID()}\">&nbsp;<a href=\"?mobileNumber={$number}\" style='color: inherit;'>{$numberText}</a></label>";
            if (in_array($message->getStatus(), ["Received", "Sent", "Delivered"]) && $_SESSION["userID"] == $message->getUserID() && $message->getDeviceID() != null && isValidMobileNumber($message->getNumber(), $message->type === "mms")) {
                $replyText = __("reply");
                $timeLineItem["header"] .= "&nbsp;<a href=\"#\" class=\"reply-message\" title=\"{$replyText}\" data-number=\"{$message->getNumber()}\" data-device=\"{$message->getDeviceID()}\" data-sim=\"{$message->getSimSlot()}\"><i class=\"fa fa-reply\"></i></a>";
            }
            $messageText = nl2br(htmlentities($message->getMessage()));
            $attachments = $message->getAttachments();
            if (count($attachments) > 0) {
                if (!empty($messageText)) {
                    $messageText .= "<br>";
                }

                foreach ($attachments as $attachment) {
                    $messageText .= "<br>";
                    $path = rawurldecode($attachment);
                    $fileName = substr(basename($path), 5);
                    if (filter_var($attachment, FILTER_VALIDATE_URL)) {
                        $messageText .= "<a href='{$attachment}' target='_blank'><i style='font-size: 20px' class='fa fa-paperclip'></i>&nbsp;{$attachment}</a>";
                    } else if (file_exists(__DIR__ . "/../{$path}")) {
                        $attr = '';
                        if (isset($_COOKIE["DEVICE_ID"])) {
                            $attr = 'download="' . $fileName . '"';
                        }
                        $messageText .= "<a href='{$attachment}' target='_blank' {$attr}><i style='font-size: 20px' class='fa fa-paperclip'></i>&nbsp;{$fileName}</a>";
                    } else {
                        $messageText .= "<i style='font-size: 20px' class='fa fa-paperclip'></i>&nbsp;{$fileName}";
                    }
                }
            }
            $timeLineItem["body"] = $messageText;

            $timeLineItem["footer"][] = __(strtolower($message->getStatus()));
            if ($message->getType() == 'mms') {
                $timeLineItem["footer"][] = 'MMS';
            }
            if (!is_null($message->getResultCode())) {
                $resultCode = $message->getResultCode();
                $errorDescription = $message->getErrorDescription();
                if ($errorDescription) {
                    $timeLineItem["footer"][] = [$resultCode, $errorDescription];
                } else {
                    $timeLineItem["footer"][] = $resultCode;
                }
            }
            if (!is_null($message->getSimSlot())) {
                if (isset($sims[$message->getDeviceID()][$message->getSimSlot()])) {
                    $timeLineItem["footer"][] = $sims[$message->getDeviceID()][$message->getSimSlot()];
                } else {
                    $timeLineItem["footer"][] = "SIM #" . ($message->getSimSlot() + 1);
                }
            }
            if (!is_null($message->getDeliveredDate())) {
                if ($message->getDeliveredDate()->format('Y-m-d') !== $sentDate->format('Y-m-d')) {
                    $timeLineItem["footer"][] = "<i class=\"fa fa-clock-o\"></i>&nbsp;{$message->getDeliveredDate()->format('D, d M Y H:i:s')}";
                } else {
                    $timeLineItem["footer"][] = "<i class=\"fa fa-clock-o\"></i>&nbsp;{$message->getDeliveredDate()->format('H:i:s')}";
                }
            }
            $scheduleTime = $message->getSchedule();
            if (!is_null($scheduleTime)) {
                if ($message->getSchedule()->format('Y-m-d') !== $sentDate->format('Y-m-d')) {
                    $timeLineItem["footer"][] = "<i class=\"fa fa-calendar-check-o\"></i>&nbsp;{$message->getSchedule()->format('D, d M Y H:i:s')}";
                } else {
                    $timeLineItem["footer"][] = "<i class=\"fa fa-calendar-check-o\"></i>&nbsp;{$message->getSchedule()->format('H:i:s')}";
                }
            }

            array_push($timeLine[$date][$message->getUserID()][$message->getDeviceID()]["messages"], $timeLineItem);
        }


        $htmlMessages = '<ul class="timeline">';
        foreach ($timeLine as $timeLineDate => $userMessages) {
            $htmlMessages .= <<<TIMELINELABEL
<li class="time-label"><span class="bg-red"><i class="fa fa-calendar"></i>&nbsp;{$timeLineDate}</span></li>
TIMELINELABEL;

            foreach ($userMessages as $userID => $deviceMessages) {
                foreach ($deviceMessages as $deviceID => $messages) {

                    if (isset($messages["header"])) {
                        $htmlMessages .= <<<TIMELINEHEADER
<li class="time-label"><span class="bg-blue"><i class="fa fa-user"></i>&nbsp;<a href="{$messages["header"]["link"]}" class="bg-blue">{$messages["header"]["title"]}</a></span></li>
TIMELINEHEADER;
                    }
                    foreach ($messages["messages"] as $timeLineItem) {
                        $htmlMessages .= <<<TIMELINEBODY
<li><i class="fa fa-envelope bg-{$timeLineItem["statusColor"]}"></i><div class="timeline-item"><span class="time"><i class="fa fa-clock-o"></i>&nbsp;{$timeLineItem["time"]}</span><h3 class="timeline-header">&nbsp;{$timeLineItem["header"]}</h3><div class="timeline-body">{$timeLineItem["body"]}</div>
TIMELINEBODY;

                        $htmlMessages .= "<div class=\"timeline-footer\">";

                        foreach ($timeLineItem["footer"] as $footer) {
                            if (is_array($footer)) {
                                $htmlMessages .= <<<TIMELINEFOOTER
<label class="label label-{$timeLineItem["statusLabel"]}" data-toggle="tooltip" title="{$footer[1]}">{$footer[0]}</label>&nbsp;
TIMELINEFOOTER;
                            } else {
                                $htmlMessages .= <<<TIMELINEFOOTER
<label class="label label-{$timeLineItem["statusLabel"]}">{$footer}</label>&nbsp;
TIMELINEFOOTER;
                            }
                        }
                    }
                }
            }
        }

        $htmlMessages .= '</div></div></li><li><i class="fa fa-clock-o bg-gray"></i></li></ul>';

        echo json_encode(["result" => ["messages" => $htmlMessages, "pageLimit" => MysqliDb::getInstance()->pageLimit, "totalCount" => $totalCount, "totalPages" => Message::getTotalPages()]]);
    }
} catch (Throwable $t) {
    echo json_encode(array(
        'error' => $t->getMessage()
    ));
}