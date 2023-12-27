<?php
/**
 * @var User $logged_in_user
 */
require_once __DIR__ . "/includes/login.php";

$title = __("application_title") . " | " . __("api");

require_once __DIR__ . "/includes/header.php";
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= __("api"); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("api_key"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <a href="#" id="regenerateApiKey" style="text-decoration: none; color: inherit"><i class="fa fa-refresh"></i></a>
                                </div>
                                <input title="<?= __("api_key"); ?>" type="text" class="form-control"
                                       value="<?php echo $logged_in_user->getAPIKey(); ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("api_test"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="generateApiLinkForm" method="post">
                        <div class="box-body">
                            <div id="ajaxResult"></div>
                            <div class="form-group">
                                <label for="mobileNumberInput"><?= __("mobile_numbers"); ?></label>
                                <input type="text" class="form-control" name="mobileNumber" id="mobileNumberInput"
                                       placeholder="<?= __("mobile_numbers_placeholder"); ?>"
                                       required="required">
                            </div>
                            <div class="form-group">
                                <label for="optionInput"><?= __("option"); ?></label>
                                <select class="form-control select2" id="optionInput" name="option"
                                        style="width: 100%;">
                                    <option value="0"><?= __("use_selected_devices") ?></option>
                                    <option value="1"><?= __("use_all_devices") ?></option>
                                    <option value="2"><?= __("use_all_sims") ?></option>
                                </select>
                            </div>
                            <div class="form-group" id="deviceInputFormGroup">
                                <label for="devicesInput"><?= __("devices"); ?>
                                    <a href="#generateApiLinkForm" class="selectAllDevices" data-target="#devicesInput">
                                        <i class="fa fa-plus-square"></i>
                                    </a>
                                    <a href="#generateApiLinkForm" class="clearAllDevices" data-target="#devicesInput">
                                        <i class="fa fa-minus-square"></i>
                                    </a>
                                </label>
                                <select class="form-control select2" id="devicesInput" name="devices[]"
                                        multiple="multiple"
                                        style="width: 100%;">
                                    <?php
                                    $selectedDevice = $logged_in_user->getPrimaryDeviceID();
                                    $logged_in_user->generateDeviceSimsList([$selectedDevice]);
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="useRandomDeviceInput">
                                    <input type="checkbox" name="useRandomDevice" value="1" id="useRandomDeviceInput">
                                    <?= __("use_random_device"); ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="prioritizeInput"><?= __("prioritize"); ?></label>
                                <select name="prioritize" class="form-control select2" id="prioritizeInput" style="width: 100%;">
                                    <option value="0"><?= __("no") ?></option>
                                    <option value="1"><?= __("yes") ?></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="typeInput"><?= __("type"); ?></label>
                                <select class="form-control select2 type-input" id="typeInput" name="type"
                                        data-target="#file-input" style="width: 100%;">
                                    <option value="sms">SMS</option>
                                    <option value="mms">MMS</option>
                                </select>
                            </div>
                            <div class="form-group" id="file-input" hidden>
                                <label for="attachmentsInput">
                                    <?= __("attachments_links"); ?>
                                    <i class="fa fa-info-circle" data-toggle="tooltip"
                                       title="<?= __('tooltip_attachments_links'); ?>"></i>
                                </label>
                                <input type="text" class="form-control" name="attachments" id="attachmentsInput"
                                       placeholder="https://example.com/example.png,https://example.com/example.jpg">
                            </div>
                            <div class="form-group">
                                <label for="messageInput"><?= __("message"); ?></label>
                                <textarea class="form-control" id="messageInput" name="message" rows="4"
                                          placeholder="<?= __("message"); ?>"></textarea>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" id="generateApiButton" name="generate"
                                    class="btn btn-primary"><i class="fa fa-link"></i>&nbsp;<?= __("generate_link"); ?>
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->

                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("add_webhook"); ?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="webHookURLInput"><?= __("webhook_url"); ?></label>
                            <input type="url" name="webHookURL" class="form-control" id="webHookURLInput"
                                   value="<?= $logged_in_user->getWebHook() ?>" required="required">
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="button" id="addWebHook"
                                class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;<?= __("save"); ?></button>&nbsp;

                        <button type="button" id="removeWebHook"
                                class="btn btn-danger"><i class="fa fa-remove"></i>&nbsp;<?= __("remove"); ?>
                        </button>
                    </div>
                </div>
                <!-- /.box -->

                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("webhook_example"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("webhook_instruction"); ?></h4>
                        <pre class="prettyprint">
define(&#x22;API_KEY&#x22;, &#x22;<?= $logged_in_user->getApiKey() ?>&#x22;);

<?= htmlentities('try {
    if (isset($_SERVER["HTTP_X_SG_SIGNATURE"])) {
        if (isset($_POST["messages"])) {
            $hash = base64_encode(hash_hmac(\'sha256\', $_POST["messages"], API_KEY, true));
            if ($hash === $_SERVER["HTTP_X_SG_SIGNATURE"]) {
                $messages = json_decode($_POST["messages"], true);
    
                /**
                 * For example :-
                 * $messages = [
                 *                 0 => [
                 *                          "ID" => "1",
                 *                          "number" => "+11234567890",
                 *                          "message" => "This is a test message.",
                 *                          "deviceID" => "1",
                 *                          "simSlot" => "0",
                 *                          "userID" => "1",
                 *                          "status" => "Received",
                 *                          "sentDate" => "2018-10-20T00:00:00+02:00",
                 *                          "deliveredDate" => "2018-10-20T00:00:00+02:00"
                 *                          "groupID" => null
                 *                      ]
                 *             ]
                 *
                 * senDate represents the date and time when the message was received on the device.
                 * deliveredDate represents the date and time when the message was received by the server.
                 */
    
                foreach ($messages as $message) {
                    if(strtolower($message["message"]) === "hi") {
                        // Reply to message using API or execute some commands. Possibilities are limitless.
                    }
                }
            } else {
                throw new Exception("Signature don\'t match!");
            }
        } else if (isset($_POST["ussdRequest"])) {
            $hash = base64_encode(hash_hmac(\'sha256\', $_POST["ussdRequest"], API_KEY, true));
            if ($hash === $_SERVER["HTTP_X_SG_SIGNATURE"]) {
                $ussdRequest = json_decode($_POST["ussdRequest"]);
                $deviceID = $ussdRequest->deviceID;
                $simSlot = $ussdRequest->simSlot;
                $request = $ussdRequest->request;
                $response = $ussdRequest->response;
                
                // Do whatever you want with data you received.
            } else {
                throw new Exception("Signature don\'t match!");
            }
        }
    } else {
        http_response_code(400);
        error_log("Signature not found!");
    }
} catch (Exception $e) {
    http_response_code(401);
    error_log($e->getMessage());
}') ?></pre>
                    </div>
                </div>

                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("php_integration"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("php_integration_instruction"); ?></h4>
                        <pre class="prettyprint">
define(&#x22;SERVER&#x22;, &#x22;<?= getServerURL() ?>&#x22;);
define(&#x22;API_KEY&#x22;, &#x22;<?= $logged_in_user->getApiKey() ?>&#x22;);

<?=
htmlentities('define("USE_SPECIFIED", 0);
define("USE_ALL_DEVICES", 1);
define("USE_ALL_SIMS", 2);

/**
 * @param string     $number      The mobile number where you want to send message.
 * @param string     $message     The message you want to send.
 * @param int|string $device      The ID of a device you want to use to send this message.
 * @param int        $schedule    Set it to timestamp when you want to send this message.
 * @param bool       $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string     $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 * @param bool       $prioritize  Set it to true if you want to prioritize this message.
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
function sendSingleMessage($number, $message, $device = 0, $schedule = null, $isMMS = false, $attachments = null, $prioritize = false)
{
    $url = SERVER . "/services/send.php";
    $postData = array(
        \'number\' => $number,
        \'message\' => $message,
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => $device,
        \'type\' => $isMMS ? "mms" : "sms",
        \'attachments\' => $attachments,
        \'prioritize\' => $prioritize ? 1 : 0
    );
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param array  $messages        The array containing numbers and messages.
 * @param int    $option          Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                                Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                                Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices         The array of ID of devices you want to use to send these messages.
 * @param int    $schedule        Set it to timestamp when you want to send these messages.
 * @param bool   $useRandomDevice Set it to true if you want to send messages using only one random device from selected devices.
 *
 * @return array     Returns The array containing messages.
 *                   For example :-
 *                   [
 *                      0 => [
 *                              "ID" => "1",
 *                              "number" => "+11234567890",
 *                              "message" => "This is a test message.",
 *                              "deviceID" => "1",
 *                              "simSlot" => "0",
 *                              "userID" => "1",
 *                              "status" => "Pending",
 *                              "type" => "sms",
 *                              "attachments" => null,
 *                              "sentDate" => "2018-10-20T00:00:00+02:00",
 *                              "deliveredDate" => null
 *                              "groupID" => ")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871"
 *                           ]
 *                   ]
 * @throws Exception If there is an error while sending messages.
 */
function sendMessages($messages, $option = USE_SPECIFIED, $devices = [], $schedule = null, $useRandomDevice = false)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'messages\' => json_encode($messages),
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => json_encode($devices),
        \'option\' => $option,
        \'useRandomDevice\' => $useRandomDevice
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to send this message.
 * @param string $message     The message you want to send.
 * @param int    $option      Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                            Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                            Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices     The array of ID of devices you want to use to send the message.
 * @param int    $schedule    Set it to timestamp when you want to send this message.
 * @param bool   $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 *
 * @return array     Returns The array containing messages.
 * @throws Exception If there is an error while sending messages.
 */
function sendMessageToContactsList($listID, $message, $option = USE_SPECIFIED, $devices = [], $schedule = null, $isMMS = false, $attachments = null)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'listID\' => $listID,
        \'message\' => $message,
        \'schedule\' => $schedule,
        \'key\' => API_KEY,
        \'devices\' => json_encode($devices),
        \'option\' => $option,
        \'type\' => $isMMS ? "mms" : "sms",
        \'attachments\' => $attachments
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 */
function getMessageByID($id)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'id\' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to retrieve.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByGroupID($groupID)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'groupId\' => $groupID
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to retrieve.
 * @param int    $deviceID       The deviceID of the device which messages you want to retrieve.
 * @param int    $simSlot        Sim slot of the device which messages you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Search for messages sent or received after this time.
 * @param int    $endTimestamp   Search for messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        \'key\' => API_KEY,
        \'status\' => $status,
        \'deviceID\' => $deviceID,
        \'simSlot\' => $simSlot,
        \'startTimestamp\' => $startTimestamp,
        \'endTimestamp\' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to resend.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while resending a message.
 */
function resendMessageByID($id)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'id\' => $id
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to resend.
 * @param string $status  The status of messages you want to resend.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByGroupID($groupID, $status = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'groupId\' => $groupID,
        \'status\' => $status
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to resend.
 * @param int    $deviceID       The deviceID of the device which messages you want to resend.
 * @param int    $simSlot        Sim slot of the device which messages you want to resend. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Resend messages sent or received after this time.
 * @param int    $endTimestamp   Resend messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        \'key\' => API_KEY,
        \'status\' => $status,
        \'deviceID\' => $deviceID,
        \'simSlot\' => $simSlot,
        \'startTimestamp\' => $startTimestamp,
        \'endTimestamp\' => $endTimestamp
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to add this contact.
 * @param string $number      The mobile number of the contact.
 * @param string $name        The name of the contact.
 * @param bool   $resubscribe Set it to true if you want to resubscribe this contact if it already exists.
 *
 * @return array     The array containing a newly added contact.
 * @throws Exception If there is an error while adding a new contact.
 */
function addContact($listID, $number, $name = null, $resubscribe = false)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        \'key\' => API_KEY,
        \'listID\' => $listID,
        \'number\' => $number,
        \'name\' => $name,
        \'resubscribe\' => $resubscribe
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @param int    $listID The ID of the contacts list from which you want to unsubscribe this contact.
 * @param string $number The mobile number of the contact.
 *
 * @return array     The array containing the unsubscribed contact.
 * @throws Exception If there is an error while setting subscription to false.
 */
function unsubscribeContact($listID, $number)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        \'key\' => API_KEY,
        \'listID\' => $listID,
        \'number\' => $number,
        \'unsubscribe\' => true
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @return string    The amount of message credits left.
 * @throws Exception If there is an error while getting message credits.
 */
function getBalance()
{
    $url = SERVER . "/services/send.php";
    $postData = [
        \'key\' => API_KEY
    ];
    $credits = sendRequest($url, $postData)["credits"];
    return is_null($credits) ? "Unlimited" : $credits;
}

/**
 * @param string $request   USSD request you want to execute. e.g. *150#
 * @param int $device       The ID of a device you want to use to send this message.
 * @param int|null $simSlot Sim you want to use for this USSD request. Similar to array index. 1st slot is 0 and 2nd is 1.
 *
 * @return array     The array containing details about USSD request that was sent.
 * @throws Exception If there is an error while sending a USSD request.
 */
function sendUssdRequest($request, $device, $simSlot = null)
{
    $url = SERVER . "/services/send-ussd-request.php";
    $postData = [
        \'key\' => API_KEY,
        \'request\' => $request,
        \'device\' => $device,
        \'sim\' => $simSlot
    ];
    return sendRequest($url, $postData)["request"];
}

/**
 * @param int $id The ID of a USSD request you want to retrieve.
 *
 * @return array     The array containing details about USSD request you requested.
 * @throws Exception If there is an error while getting a USSD request.
 */
function getUssdRequestByID($id)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        \'key\' => API_KEY,
        \'id\' => $id
    ];
    return sendRequest($url, $postData)["requests"][0];
}

/**
 * @param string   $request        The request text you want to look for.
 * @param int      $deviceID       The deviceID of the device which USSD requests you want to retrieve.
 * @param int      $simSlot        Sim slot of the device which USSD requests you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int|null $startTimestamp Search for USSD requests sent after this time.
 * @param int|null $endTimestamp   Search for USSD requests sent before this time.
 *
 * @return array     The array containing USSD requests.
 * @throws Exception If there is an error while getting USSD requests.
 */
function getUssdRequests($request, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        \'key\' => API_KEY,
        \'request\' => $request,
        \'deviceID\' => $deviceID,
        \'simSlot\' => $simSlot,
        \'startTimestamp\' => $startTimestamp,
        \'endTimestamp\' => $endTimestamp
    ];
    return sendRequest($url, $postData)["requests"];
}

/**
 * @return array     The array containing all enabled devices
 * @throws Exception If there is an error while getting devices.
 */
function getDevices() {
    $url = SERVER . "/services/get-devices.php";
    $postData = [
        \'key\' => API_KEY
    ];
    return sendRequest($url, $postData)["devices"];
}

function sendRequest($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json == false) {
            if (empty($response)) {
                throw new Exception("Missing data in request. Please provide all the required information to send messages.");
            } else {
                throw new Exception($response);
            }
        } else {
            if ($json["success"]) {
                return $json["data"];
            } else {
                throw new Exception($json["error"]["message"]);
            }
        }
    } else {
        throw new Exception("HTTP Error Code : {$httpCode}");
    }
}');
?></pre>
                        <h4><?= __("send_single_message"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Send a message using the primary device.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.");

    // Send a message using the Device ID 1.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1);
    
    // Send a prioritize message using Device ID 1 for purpose of sending OTP, message reply etc…
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1, null, false, null, true);
    
    // Send a MMS message with image using the Device ID 1.
    $attachments = "https://example.com/images/footer-logo.png,https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png";
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", 1, null, true, $attachments);
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    $msg = sendSingleMessage("+11234567890", "This is a test of single message.", "1|0");

    // Send scheduled message using the primary device.
    $msg = sendSingleMessage("+11234567890", "This is a test of schedule feature.", null, strtotime("+2 minutes"));
    print_r($msg);

    echo "Successfully sent a message.";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("send_bulk_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('$messages = array();

for ($i = 1; $i <= 12; $i++) {
    array_push($messages,
        [
            "number" => "+11234567890",
            "message" => "This is a test #{$i} of PHP version. Testing bulk message functionality."
        ]);
}

try {
    // Send messages using the primary device.
    sendMessages($messages);

    // Send messages using default SIM of all available devices. Messages will be split between all devices.
    sendMessages($messages, USE_ALL_DEVICES);
	
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    sendMessages($messages, USE_ALL_SIMS);

    // Send messages using only specified devices. Messages will be split between devices or SIMs you specified.
    // If you send 12 messages using this code then 4 messages will be sent by Device ID 1, other 4 by SIM in slot 1 of 
    // Device ID 2 (Represendted as "2|0") and remaining 4 by SIM in slot 2 of Device ID 2 (Represendted as "2|1").
    sendMessages($messages, USE_SPECIFIED, [1, "2|0", "2|1"]);
    
    // Send messages on schedule using the primary device.
    sendMessages($messages, null, null, strtotime("+2 minutes"));
    
    // Send a message to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", USE_SPECIFIED, 1);
    
    // Send a message on schedule to contacts in contacts list with ID of 1.
    sendMessageToContactsList(1, "Test", null, null, strtotime("+2 minutes"));
    
    // Array of image links to attach to MMS message;
    $attachments = [
        "https://example.com/images/footer-logo.png",
        "https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png"
    ];
    $attachments = implode(\',\', $attachments);
    
    $mmsMessages = [];
    for ($i = 1; $i <= 12; $i++) {
        array_push($mmsMessages,
            [
                "number" => "+11234567890",
                "message" => "This is a test #{$i} of PHP version. Testing bulk MMS message functionality.",
                "type" => "mms",
                "attachments" => $attachments
            ]);
    }
    // Send MMS messages using all SIMs of all available devices. Messages will be split between all SIMs.
    $msgs = sendMessages($mmsMessages, USE_ALL_SIMS);
    
    print_r($msgs);

    echo "Successfully sent bulk messages.";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_balance"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    $credits = getBalance();
    echo "Message Credits Remaining: {$credits}";
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get a message using the ID.
    $msg = getMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID.
    $msgs = getMessagesByGroupID(\')V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871\');
    print_r($msgs);
    
    // Get messages received in last 24 hours.
    $msgs = getMessagesByStatus("Received", null, null, time() - 86400);
    
    // Get messages received on SIM 1 of device ID 8 in last 24 hours.
    $msgs = getMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("resend_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Resend a message using the ID.
    $msg = resendMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID and Status.
    $msgs = resendMessagesByGroupID(\'LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871\', \'Failed\');
    print_r($msgs);
    
    // Resend pending messages in last 24 hours.
    $msgs = resendMessagesByStatus("Pending", null, null, time() - 86400);
    
    // Resend pending messages sent using SIM 1 of device ID 8 in last 24 hours.
    $msgs = resendMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("manage_contacts"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    $contact = addContact(1, "+11234567890", "Test", true);
    print_r($contact);
    
    // Unsubscribe a contact using the mobile number.
    $contact = unsubscribeContact(1, "+11234567890");
    print_r($contact);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("send_ussd_request"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Send a USSD request using default SIM of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1);
    print_r($ussdRequest);
    
    // Send a USSD request using SIM in slot 1 of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1, 0);
    print_r($ussdRequest);
    
    // Send a USSD request using SIM in slot 2 of Device ID 1.
    $ussdRequest = sendUssdRequest("*150#", 1, 1);
    print_r($ussdRequest);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_ussd_requests"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get a USSD request using the ID.
    $ussdRequest = getUssdRequestByID(1);
    print_r($ussdRequest);
    
    // Get USSD requests with request text "*150#" sent in last 24 hours.
    $ussdRequests = getUssdRequests("*150#", null, null, time() - 86400);
    print_r($ussdRequests);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                        <h4><?= __("get_devices"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get all enabled devices for sending messages.
    $devices = getDevices()
    print_r($devices);
} catch (Exception $e) {
    echo $e->getMessage();
}');
?></pre>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="box box-primary collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("c#_integration"); ?></h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-plus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <h4><?= __("c#_integration_instruction"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('using System;
using System.Collections.Generic;
using System.IO;
using System.Net;
using System.Text;
using System.Web;
using Gateway_Sample_Application.Properties;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;

namespace SMS
{
    static class API
    {
        private static readonly string Server = "' . getServerURL() . '";
        private static readonly string Key = "' . $logged_in_user->getApiKey() . '";

        public enum Option
        {
            USE_SPECIFIED = 0,
            USE_ALL_DEVICES = 1,
            USE_ALL_SIMS = 2
        }

        /// <summary>
        /// Send single message to specific mobile number.
        /// </summary>
        /// <param name="number">The mobile number where you want to send message.</param>
        /// <param name="message">The message you want to send.</param>
        /// <param name="device">The ID of a device you want to use to send this message.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="isMMS">Set it to true if you want to send MMS message instead of SMS.</param>
        /// <param name="attachments">Comma separated list of image links you want to attach to the message. Only works for MMS messages.</param>
        /// <param name="prioritize">Set it to true if you want to prioritize this message.</param>
        /// <exception>If there is an error while sending a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> SendSingleMessage(string number, string message, string device = "0",
            long? schedule = null, bool isMMS = false, string attachments = null, bool prioritize = false)
        {
            var values = new Dictionary<string, object>
            {
                { "number", number },
                { "message", message },
                { "schedule", schedule },
                { "key", Key },
                { "devices", device },
                { "type", isMMS ? "mms" : "sms" },
                { "attachments", attachments },
                { "prioritize", prioritize ? 1 : 0 }
            };

            return GetObjects(GetResponse($"{Server}/services/send.php", values)["messages"])[0];
        }

        /// <summary>
        /// Send multiple messages to different mobile numbers.
        /// </summary>
        /// <param name="messages">The array containing numbers and messages.</param>
        /// <param name="option">Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
        /// Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
        /// Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.</param>
        /// <param name="devices">The array of ID of devices you want to use to send these messages.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="useRandomDevice">Set it to true if you want to send messages using only one random device from selected devices.</param>
        /// <exception>If there is an error while sending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] SendMessages(List<Dictionary<string, string>> messages,
            Option option = Option.USE_SPECIFIED, string[] devices = null, long? schedule = null,
            bool useRandomDevice = false)
        {
            var values = new Dictionary<string, object>
            {
                { "messages", JsonConvert.SerializeObject(messages) },
                { "schedule", schedule },
                { "key", Key },
                { "devices", devices },
                { "option", (int)option },
                { "useRandomDevice", useRandomDevice }
            };

            return GetObjects(GetResponse($"{Server}/services/send.php", values)["messages"]);
        }

        /// <summary>
        /// Send a message to contacts in specified contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list where you want to send this message.</param>
        /// <param name="message">The message you want to send.</param>
        /// <param name="option">Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
        /// Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
        /// Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.</param>
        /// <param name="devices">The array of ID of devices you want to use to send these messages.</param>
        /// <param name="schedule">Set it to timestamp when you want to send this message.</param>
        /// <param name="isMMS">Set it to true if you want to send MMS message instead of SMS.</param>
        /// <param name="attachments">Comma separated list of image links you want to attach to the message. Only works for MMS messages.</param>
        /// <exception>If there is an error while sending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] SendMessageToContactsList(int listID, string message,
            Option option = Option.USE_SPECIFIED, string[] devices = null, long? schedule = null, bool isMMS = false,
            string attachments = null)
        {
            var values = new Dictionary<string, object>
            {
                { "listID", listID },
                { "message", message },
                { "schedule", schedule },
                { "key", Key },
                { "devices", devices },
                { "option", (int)option },
                { "type", isMMS ? "mms" : "sms" },
                { "attachments", attachments }
            };

            return GetObjects(GetResponse($"{Server}/services/send.php", values)["messages"]);
        }

        /// <summary>
        /// Get a message using the ID.
        /// </summary>
        /// <param name="id">The ID of a message you want to retrieve.</param>
        /// <exception>If there is an error while getting a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> GetMessageByID(int id)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "id", id }
            };

            return GetObjects(GetResponse($"{Server}/services/read-messages.php", values)["messages"])[0];
        }

        /// <summary>
        /// Get messages using the Group ID.
        /// </summary>
        /// <param name="groupID">The group ID of messages you want to retrieve.</param>
        /// <exception>If there is an error while getting messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] GetMessagesByGroupID(string groupID)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "groupId", groupID }
            };

            return GetObjects(GetResponse($"{Server}/services/read-messages.php", values)["messages"]);
        }

        /// <summary>
        /// Get messages using the status.
        /// </summary>
        /// <param name="status">The status of messages you want to retrieve.</param>
        /// <param name="deviceID">The deviceID of the device which messages you want to retrieve.</param>
        /// <param name="simSlot">Sim slot of the device which messages you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.</param>
        /// <param name="startTimestamp">Search for messages sent or received after this time.</param>
        /// <param name="endTimestamp">Search for messages sent or received before this time.</param>
        /// <exception>If there is an error while getting messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] GetMessagesByStatus(string status, int? deviceID = null,
            int? simSlot = null, long? startTimestamp = null,
            long? endTimestamp = null)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "status", status },
                { "deviceID", deviceID },
                { "simSlot", simSlot },
                { "startTimestamp", startTimestamp },
                { "endTimestamp", endTimestamp }
            };

            return GetObjects(GetResponse($"{Server}/services/read-messages.php", values)["messages"]);
        }

        /// <summary>
        /// Resend a message using the ID.
        /// </summary>
        /// <param name="id">The ID of a message you want to resend.</param>
        /// <exception>If there is an error while resending a message.</exception>
        /// <returns>The dictionary containing information about the message.</returns>
        public static Dictionary<string, object> ResendMessageByID(int id)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "id", id }
            };

            return GetObjects(GetResponse($"{Server}/services/resend.php", values)["messages"])[0];
        }

        /// <summary>
        /// Resend messages using the Group ID.
        /// </summary>
        /// <param name="groupID">The group ID of messages you want to resend.</param>
        /// <param name="status">The status of messages you want to resend.</param>
        /// <exception>If there is an error while resending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] ResendMessagesByGroupID(string groupID, string status = null)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "groupId", groupID },
                { "status", status }
            };

            return GetObjects(GetResponse($"{Server}/services/resend.php", values)["messages"]);
        }

        /// <summary>
        /// Resend messages using the status.
        /// </summary>
        /// <param name="status">The status of messages you want to resend.</param>
        /// <param name="deviceID">The deviceID of the device which messages you want to resend.</param>
        /// <param name="simSlot">Sim slot of the device which messages you want to resend. Similar to array index. 1st slot is 0 and 2nd is 1.</param>
        /// <param name="startTimestamp">Resend messages sent or received after this time.</param>
        /// <param name="endTimestamp">Resend messages sent or received before this time.</param>
        /// <exception>If there is an error while resending messages.</exception>
        /// <returns>The array containing messages.</returns>
        public static Dictionary<string, object>[] ResendMessagesByStatus(string status, int? deviceID = null,
            int? simSlot = null, long? startTimestamp = null,
            long? endTimestamp = null)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "status", status },
                { "deviceID", deviceID },
                { "simSlot", simSlot },
                { "startTimestamp", startTimestamp },
                { "endTimestamp", endTimestamp }
            };

            return GetObjects(GetResponse($"{Server}/services/resend.php", values)["messages"]);
        }

        /// <summary>
        /// Add a new contact to contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list where you want to add this contact.</param>
        /// <param name="number">The mobile number of the contact.</param>
        /// <param name="name">The name of the contact.</param>
        /// <param name="resubscribe">Set it to true if you want to resubscribe this contact if it already exists.</param>
        /// <returns>A dictionary containing details about a newly added contact.</returns>
        public static Dictionary<string, object> AddContact(int listID, string number, string name = null,
            bool resubscribe = false)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "listID", listID },
                { "number", number },
                { "name", name },
                { "resubscribe", resubscribe ? \'1\' : \'0\' },
            };
            JObject jObject = (JObject)GetResponse($"{Server}/services/manage-contacts.php", values)["contact"];
            return jObject.ToObject<Dictionary<string, object>>();
        }

        /// <summary>
        /// Unsubscribe a contact from the contacts list.
        /// </summary>
        /// <param name="listID">The ID of the contacts list from which you want to unsubscribe this contact.</param>
        /// <param name="number">The mobile number of the contact.</param>
        /// <returns>A dictionary containing details about the unsubscribed contact.</returns>
        public static Dictionary<string, object> UnsubscribeContact(int listID, string number)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "listID", listID },
                { "number", number },
                { "unsubscribe", \'1\' }
            };
            JObject jObject = (JObject)GetResponse($"{Server}/services/manage-contacts.php", values)["contact"];
            return jObject.ToObject<Dictionary<string, object>>();
        }

        /// <summary>
        /// Get remaining message credits.
        /// </summary>
        /// <exception>If there is an error while getting message credits.</exception>
        /// <returns>The amount of message credits left.</returns>
        public static string GetBalance()
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key }
            };
            JToken credits = GetResponse($"{Server}/services/send.php", values)["credits"];
            if (credits.Type != JTokenType.Null)
            {
                return credits.ToString();
            }

            return "Unlimited";
        }

        /// <summary>
        /// Send USSD request.
        /// </summary>
        /// <param name="request">USSD request you want to execute. e.g. *150#</param>
        /// <param name="device">The ID of a device you want to use to send this message.</param>
        /// <param name="simSlot">Sim you want to use for this USSD request. Similar to array index. 1st slot is 0 and 2nd is 1.</param>
        /// <exception>If there is an error while sending a USSD request.</exception>
        /// <returns>A dictionary containing details about USSD request that was sent.</returns>
        public static Dictionary<string, object> SendUssdRequest(string request, int device, int? simSlot = null)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "request", request },
                { "device", device },
                { "sim", simSlot }
            };

            JObject jObject = (JObject)GetResponse($"{Server}/services/send-ussd-request.php", values)["request"];
            return jObject.ToObject<Dictionary<string, object>>();
        }

        /// <summary>
        /// Get a USSD request using the ID.
        /// </summary>
        /// <param name="id">The ID of a USSD request you want to retrieve.</param>
        /// <exception>If there is an error while getting a USSD request.</exception>
        /// <returns>A dictionary containing details about USSD request you requested.</returns>
        public static Dictionary<string, object> GetUssdRequestByID(int id)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "id", id }
            };

            return GetObjects(GetResponse($"{Server}/services/read-ussd-requests.php", values)["requests"])[0];
        }

        /// <summary>
        /// Get USSD requests using the request text.
        /// </summary>
        /// <param name="request">The request text you want to look for.</param>
        /// <param name="deviceID">The deviceID of the device which USSD requests you want to retrieve.</param>
        /// <param name="simSlot">Sim slot of the device which USSD requests you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.</param>
        /// <param name="startTimestamp">Search for USSD requests sent after this time.</param>
        /// <param name="endTimestamp">Search for USSD requests sent before this time.</param>
        /// <exception>If there is an error while getting USSD requests.</exception>
        /// <returns>The array containing USSD requests.</returns>
        public static Dictionary<string, object>[] GetUssdRequests(string request, int? deviceID = null,
            int? simSlot = null, int? startTimestamp = null, int? endTimestamp = null)
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key },
                { "request", request },
                { "deviceID", deviceID },
                { "simSlot", simSlot },
                { "startTimestamp", startTimestamp },
                { "endTimestamp", endTimestamp }
            };

            return GetObjects(GetResponse($"{Server}/services/read-ussd-requests.php", values)["requests"]);
        }

        /// <summary>
        /// Get all enabled devices.
        /// </summary>
        /// <exception>If there is an error while getting devices.</exception>
        /// <returns>The array containing all enabled devices</returns>
        public static Dictionary<string, object>[] GetDevices()
        {
            var values = new Dictionary<string, object>
            {
                { "key", Key }
            };

            return GetObjects(GetResponse($"{Server}/services/get-devices.php", values)["devices"]);
        }

        private static Dictionary<string, object>[] GetObjects(JToken messagesJToken)
        {
            JArray jArray = (JArray)messagesJToken;
            var messages = new Dictionary<string, object>[jArray.Count];
            for (var index = 0; index < jArray.Count; index++)
            {
                messages[index] = jArray[index].ToObject<Dictionary<string, object>>();
            }

            return messages;
        }

        private static JToken GetResponse(string url, Dictionary<string, object> postData)
        {
            var request = (HttpWebRequest)WebRequest.Create(url);
            var dataString = CreateDataString(postData);
            var data = Encoding.UTF8.GetBytes(dataString);

            request.Method = "POST";
            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = data.Length;
            ServicePointManager.Expect100Continue = true;
            ServicePointManager.SecurityProtocol = SecurityProtocolType.Tls12;
            using (var stream = request.GetRequestStream())
            {
                stream.Write(data, 0, data.Length);
            }

            var response = (HttpWebResponse)request.GetResponse();

            if (response.StatusCode == HttpStatusCode.OK)
            {
                using (StreamReader streamReader = new StreamReader(response.GetResponseStream()))
                {
                    var jsonResponse = streamReader.ReadToEnd();
                    try
                    {
                        JObject jObject = JObject.Parse(jsonResponse);
                        if ((bool)jObject["success"])
                        {
                            return jObject["data"];
                        }

                        throw new Exception(jObject["error"]["message"].ToString());
                    }
                    catch (JsonReaderException)
                    {
                        if (string.IsNullOrEmpty(jsonResponse))
                        {
                            throw new InvalidDataException(
                                "Missing data in request. Please provide all the required information to send messages.");
                        }

                        throw new Exception(jsonResponse);
                    }
                }
            }

            throw new WebException($"HTTP Error : {(int)response.StatusCode} {response.StatusCode}");
        }

        private static string CreateDataString(Dictionary<string, object> data)
        {
            StringBuilder dataString = new StringBuilder();
            bool first = true;
            foreach (var obj in data)
            {
                if (obj.Value != null)
                {
                    if (first)
                    {
                        first = false;
                    }
                    else
                    {
                        dataString.Append("&");
                    }

                    dataString.Append(HttpUtility.UrlEncode(obj.Key));
                    dataString.Append("=");
                    dataString.Append(obj.Value is string[]
                        ? HttpUtility.UrlEncode(JsonConvert.SerializeObject(obj.Value))
                        : HttpUtility.UrlEncode(obj.Value.ToString()));
                }
            }

            return dataString.ToString();
        }
    }
}');
                            ?></pre>
                        <h4><?= __("send_single_message"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('try
{
    // Send a message using the primary device.
    SMS.API.SendSingleMessage("+11234567890", "This is a test of single message.");

    // Send a message using the Device ID 1.
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+11234567890", "This is a test of single message.", "1");
    
    // Send a prioritize message using Device ID 1 for purpose of sending OTP, message reply etc…
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+11234567890", "This is a test of single message.", "1", null, false, null, true);
    
    // Send a MMS message using the Device ID 1.
    string attachments = "https://example.com/images/footer-logo.png,https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png";
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+11234567890", "This is a test of single message.", "1", null, true, attachments);
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    Dictionary<string, object> message = SMS.API.SendSingleMessage("+11234567890", "This is a test of single message.", "1|0");

    // Send scheduled message using the primary device.
    long timestamp = (long) DateTime.UtcNow.AddMinutes(2).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    Dictionary<string, object> message = SendSingleMessage(textBoxNumber.Text, textBoxMessage.Text, null, timestamp);
    
    MessageBox.Show("Successfully sent a message.");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
                            ?></pre>
                        <h4><?= __("send_bulk_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('List<Dictionary<string, string>> messages = new List<Dictionary<string, string>>();
for (int i = 1; i <= 12; i++)
{
    var message = new Dictionary<string, string>
    {
        { "number", "+11234567890" },
        { "message", "This is a test #{$i} of C# version. Testing bulk message functionality." }
    };
    messages.Add(message);
}

try
{
    // Send messages using the primary device.
    SMS.API.SendMessages(messages);

    // Send messages using default SIM of all available devices. Messages will be split between all devices.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_DEVICES);

    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_SIMS);

    // Send messages using only specified devices. Messages will be split between devices or SIMs you specified.
    // If you send 12 messages using this code then 4 messages will be sent by Device ID 1, other 4 by SIM in slot 1 of 
    // Device ID 2 (Represendted as "2|0") and remaining 4 by SIM in slot 2 of Device ID 2 (Represendted as "2|1").
    SMS.API.SendMessages(messages, SMS.API.Option.USE_SPECIFIED, new [] {"1", "2|0", "2|1"});
    
    // Send messages on schedule using the primary device.
    long timestamp = (long) DateTime.UtcNow.AddMinutes(2).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    Dictionary<string, object>[] messages = SMS.API.SendMessages(messages, Option.USE_SPECIFIED, null, timestamp);
    
    // Send a message to contacts in contacts list with ID of 1.
    Dictionary<string, object>[] messages = SMS.API.SendMessageToContactsList(1, "Test", SMS.API.Option.USE_SPECIFIED, new [] {"1"});

    // Send a message on schedule to contacts in contacts list with ID of 1.
    Dictionary<string, object>[] messages = SMS.API.SendMessageToContactsList(1, "Test #1", Option.USE_SPECIFIED, null, timestamp);
    
    string attachments = "https://example.com/images/footer-logo.png,https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png";
    List<Dictionary<string, string>> mmsMessages = new List<Dictionary<string, string>>();
    for (int i = 1; i <= 12; i++)
    {
        var message = new Dictionary<string, string>
        {
            { "number", "+11234567890" },
            { "message", "This is a test #{$i} of C# version. Testing bulk MMS message functionality." },
            { "type", "mms" },
            { "attachments", attachments }
        };
        mmsMessages.Add(message);
    }
    
    // Send messages using all SIMs of all available devices. Messages will be split between all SIMs.
    SMS.API.SendMessages(messages, SMS.API.Option.USE_ALL_SIMS);
    
    MessageBox.Show("Success");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("get_balance"); ?></h4>
                        <pre class="prettyprint"><?=
                            htmlentities('try
{
    string credits = SMS.API.GetBalance();
    MessageBox.Show($"Message Credits Remaining: {credits}");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
                            ?></pre>
                        <h4><?= __("get_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try 
{
    // Get a message using the ID.
    Dictionary<string, object> message = SMS.API.GetMessageByID(1);

    // Get messages using the Group ID.
    Dictionary<string, object>[] messages = SMS.API.GetMessagesByGroupID(")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871");
    
    // Get messages received in last 24 hours.
    long timestamp = (long) DateTime.UtcNow.AddHours(-24).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    messages = GetMessagesByStatus("Received", null, null, timestamp);
    
    // Get messages received on SIM 1 of device ID 8 in last 24 hours.
    messages = GetMessagesByStatus("Received", 8, 0, timestamp);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("resend_messages"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try 
{
    // Resend a message using the ID.
    Dictionary<string, object> message = SMS.API.ResendMessageByID(1);

    // Resend messages using the Group ID and Status.
    Dictionary<string, object>[] messages = SMS.API.ResendMessagesByGroupID("LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871", "Failed");
    
    // Resend pending messages in last 24 hours.
    long timestamp = (long) DateTime.UtcNow.AddHours(-24).Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    messages = ResendMessagesByStatus("Pending", null, null, timestamp);
    
    // Resend pending messages sent using SIM 1 of device ID 8 in last 24 hours.
    messages = ResendMessagesByStatus("Pending", 8, 0, timestamp);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("manage_contacts"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    Dictionary<string, object> contact = SMS.API.AddContact(1, "+11234567890", "Test C#", true);
    
    // Unsubscribe a contact using the mobile number.
    Dictionary<string, object> contact = UnsubscribeContact(1, "+11234567890");
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("send_ussd_request"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Send a USSD request using default SIM of Device ID 1.
    Dictionary<string, object> ussdRequest = SendUssdRequest("*150#", 1);
    
    // Send a USSD request using SIM in slot 1 of Device ID 1.
    Dictionary<string, object> ussdRequest = SendUssdRequest("*150#", 1, 0);
    
    // Send a USSD request using SIM in slot 2 of Device ID 1.
    Dictionary<string, object> ussdRequest = SendUssdRequest("*150#", 1, 1);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("get_ussd_requests"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get a USSD request using the ID.
    Dictionary<string, object> ussdRequest = GetUssdRequestByID(1);
    
    // Get USSD requests with request text "*150#" sent in last 24 hours.
    Dictionary<string, object>[] ussdRequests = GetUssdRequests("*150#", null, null, time() - 86400);
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                        <h4><?= __("get_devices"); ?></h4>
                        <pre class="prettyprint">
<?=
htmlentities('try {
    // Get all enabled devices for sending messages.
    Dictionary<string, object> devices = GetDevices();
}
catch (Exception exception)
{
    MessageBox.Show(exception.Message, "!Error", MessageBoxButtons.OK, MessageBoxIcon.Error);
}');
?></pre>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once __DIR__ . "/includes/footer.php"; ?>
<?php require_once __DIR__ . '/includes/user-sims.php'; ?>
<?php require_once __DIR__ . "/includes/select-all.php"; ?>
<script>
    $(function () {
        const webHookURLInput = $('#webHookURLInput');
        const removeWebHookButton = $('#removeWebHook');
        const generateApiLinkForm = $('#generateApiLinkForm');
        const generateApiButton = $('#generateApiButton');

        <?php if (empty($logged_in_user->getWebHook())) { ?>
        removeWebHookButton.hide();
        <?php } ?>

        $('.select2').select2();

        $('#regenerateApiKey').click(function (e) {
            e.preventDefault();
            let result = confirm("<?=__("regenerate_api_key_confirmation");?>");
            if (result) {
                $(this).prop('disabled', true);
                ajaxRequest("ajax/regenerate-api-key.php", null).then(result => {
                    toastr.success(result);
                    setTimeout(() => location.reload(), 1000);
                }).catch(reason => {
                    toastr.error(reason);
                    $(this).prop('disabled', false);
                });
            }
        });

        $('#optionInput').change(function () {
            if ($(this).val() != 0) {
                $('#devicesInput').prop('disabled', true);
                $('#deviceInputFormGroup').prop('hidden', true);
            } else {
                $('#devicesInput').prop('disabled', false);
                $('#deviceInputFormGroup').prop('hidden', false);
            }
        });

        $('.type-input').change(function () {
            let fileInput = $($(this).data('target'));
            if ($(this).val() === "sms") {
                fileInput.prop("hidden", true);
            } else {
                fileInput.prop("hidden", false);
            }
        });

        $('#addWebHook').click(function (event) {
            event.preventDefault();
            $(this).prop('disabled', true);
            ajaxRequest("ajax/add-webhook.php", { webHookURL: webHookURLInput.val() }).then(result => {
                toastr.success(result);
                removeWebHookButton.show();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                $(this).prop('disabled', false);
            });
        });

        removeWebHookButton.click(function (event) {
            event.preventDefault();
            $(this).prop('disabled', true);
            ajaxRequest("ajax/remove-webhook.php").then(result => {
                toastr.success(result);
                webHookURLInput.val("");
                removeWebHookButton.hide();
            }).catch(reason => {
                toastr.error(reason);
            }).finally(() => {
                $(this).prop('disabled', false);
            });
        });

        generateApiLinkForm.submit(function (event) {
            event.preventDefault();
            generateApiButton.prop('disabled', true);
            ajaxRequest("ajax/generate-api-link.php", $(this).serialize()).then(result => {
                $('#ajaxResult').html(
                    `<div class="alert alert-success alert-dismissible" id="alertSuccess">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>
                        <h4><i class="icon fa fa-check"></i>&nbsp;<?= __("success_dialog_title"); ?></h4>
                        <a href="${result}" target="_blank">${result}</a>
                    </div>`
                );
            }).catch(reason => {
                $('#ajaxResult').html(
                    `<div class="alert alert-danger alert-dismissible" id="alertDanger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            &times;
                        </button>
                        <h4><i class="icon fa fa-ban"></i>&nbsp;<?= __("error_dialog_title"); ?></h4>
                        ${reason}
                    </div>`
                );
            }).finally(() => {
                generateApiButton.prop('disabled', false);
            });
        });
    });
</script>
</body>
</html>