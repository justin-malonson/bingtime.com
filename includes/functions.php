<?php

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Common\Creator\WriterFactory;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Opis\JsonSchema\Validator;
use PHPMailer\PHPMailer\PHPMailer;

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

/**
 * Recursively deletes a directory and all of its contents - e.g.the equivalent of `rm -r` on the command-line.
 * Consistent with `rmdir()` and `unlink()`. An E_WARNING level error will be generated on failure.
 *
 * @param string $dir The path of a directory you want to delete
 * @return bool TRUE on success or FALSE on failure
 * @link https://gist.github.com/mindplay-dk/a4aad91f5a4f1283a5e2
 */
function rmdir_recursive(string $dir): bool
{
    if (!file_exists($dir)) {
        return true;
    }

    /** @var SplFileInfo[] $files */
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($files as $fileInfo) {
        if ($fileInfo->isDir()) {
            if (!rmdir($fileInfo->getRealPath())) {
                return false;
            }
        } else {
            if (!unlink($fileInfo->getRealPath())) {
                return false;
            }
        }
    }
    return rmdir($dir);
}

/**
 * @param string $data
 * @param string $key
 * @param string $iv
 * @param bool $decrypt
 * @return bool|string
 * @link https://gist.github.com/joashp/a1ae9cb30fa533f4ad94
 */
function encrypt(string $data, string $key, string $iv, bool $decrypt = false)
{
    $method = "AES-256-CBC";

    $key = hash('sha256', $key);

    $iv = substr(hash('sha256', $iv), 0, 16);
    if ($decrypt) {
        $output = openssl_decrypt(base64_decode($data), $method, $key, 0, $iv);
    } else {
        $output = openssl_encrypt($data, $method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    return $output;
}

/**
 * @param string $data
 * @param string $key
 * @param string $iv
 * @return bool|string
 */
function decrypt(string $data, string $key, string $iv)
{
    return encrypt($data, $key, $iv, true);
}

/**
 * @return string
 * @throws Exception
 */
function generateAPIKey(): string
{
    return sha1(random_str(25) . uniqid("", true));
}

function isHttps(): bool
{
    if (isset($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"]) === 'on') {
        return true;
    }

    if (isset($_SERVER["HTTP_X_FORWARDED_SSL"]) && strtolower($_SERVER["HTTP_X_FORWARDED_SSL"]) === 'on') {
        return true;
    }

    if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strtolower($_SERVER["HTTP_X_FORWARDED_PROTO"]) === 'https') {
        return true;
    }

    if (isset($_SERVER["SERVER_PORT"]) && (int)$_SERVER["SERVER_PORT"] === 443) {
        return true;
    }

    return false;
}

/**
 * @return string
 */
function getServerURL(): string
{
    $request_uri = $_SERVER['REQUEST_URI'];
    $pos = strripos($request_uri, "/");
    if ($pos !== FALSE) {
        $request_uri = substr($request_uri, 0, $pos);
    }
    $request_uri = str_replace('/ajax', '', $request_uri);
    $protocol = (defined("FORCE_HTTPS") && FORCE_HTTPS) || isHttps() ? "https" : "http";
    return "{$protocol}://{$_SERVER['HTTP_HOST']}{$request_uri}";
}

/**
 * @param int $length
 * @param string $keySpace
 * @return string
 * @throws Exception
 * @link https://stackoverflow.com/a/31284266/1273550
 */
function random_str(
    int $length,
    string $keySpace = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
): string
{
    $str = '';
    if (function_exists('mb_strlen')) {
        $max = mb_strlen($keySpace, '8bit') - 1;
    } else {
        $max = strlen($keySpace) - 1;
    }
    if ($max < 1) {
        throw new Exception("{$keySpace} must be at least two characters long");
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keySpace[random_int(0, $max)];
    }
    return $str;
}

/**
 * @param Entity[] $arrayOfObjects
 * @param string $fileName
 * @param array|null $headers
 * @param array|null $exclude
 * @throws IOException
 * @throws UnsupportedTypeException
 * @throws WriterNotOpenedException
 */
function objectsToExcel(array $arrayOfObjects, string $fileName = "Export.csv", ?array $headers = [], ?array $exclude = null)
{
    if (count($arrayOfObjects) > 0) {
        $writer = WriterFactory::createFromFile($fileName);
        $writer->openToBrowser($fileName);
        $props = get_object_vars($arrayOfObjects[0]);
        $header = array();
        foreach ($props as $prop => $prop_value) {
            if (!in_array($prop, $exclude)) {
                if (isset($headers[$prop])) {
                    array_push($header, $headers[$prop]);
                } else {
                    array_push($header, ucwords(implode(" ", preg_split('/(?=[A-Z])/', $prop))));
                }
            }
        }
        $headerValues = Row::fromValues($header);
        $writer->addRow($headerValues);
        foreach ($arrayOfObjects as $object) {
            $props = get_object_vars($object);
            $fields = array();
            foreach ($props as $prop => $prop_value) {
                if (!in_array($prop, $exclude)) {
                    array_push($fields, $prop_value);
                }
            }
            $rowFromValues = Row::fromValues($fields);
            $writer->addRow($rowFromValues);
        }
        $writer->close();
    }
}

/**
 * @param string $name
 * @return string
 * @link https://stackoverflow.com/a/34465594/1273550
 * @link http://tools.ietf.org/html/rfc6265#section-4.1.1
 */
function get_cookie(string $name): string
{
    $cookies = [];
    $headers = headers_list();
    foreach ($headers as $header) {
        if (strpos($header, 'Set-Cookie: ') === 0) {
            $value = str_replace('&', urlencode('&'), substr($header, 12));
            parse_str(current(explode(';', $value, 1)), $pair);
            $cookies = array_merge_recursive($cookies, $pair);
        }
    }
    return $cookies[$name];
}

/**
 * @param array $from
 * @param array $to
 * @param string $subject
 * @param string $body
 * @param array $attachments
 * @throws \PHPMailer\PHPMailer\Exception
 */
function sendEmail(array $from, array $to, string $subject, string $body, array $attachments = [])
{
    try {
        $mail = new PHPMailer(true);
        if (Setting::get("smtp_enabled")) {
            if (Setting::get("smtp_hostname") && Setting::get("smtp_port")) {
                $mail->isSMTP();
                $mail->Host = Setting::get("smtp_hostname");
                $mail->Port = Setting::get("smtp_port");
                if (Setting::get("smtp_encryption")) {
                    $mail->SMTPSecure = Setting::get("smtp_encryption");
                }
                if (Setting::get("smtp_debug")) {
                    $mail->SMTPDebug = Setting::get("smtp_debug");
                }
                if (Setting::get("smtp_username") && Setting::get("smtp_password")) {
                    $mail->SMTPAuth = true;
                    $mail->Username = Setting::get("smtp_username");
                    $mail->Password = Setting::get("smtp_password");
                }
                if (Setting::get("smtp_ssl_verification"))
                {
                    $mail->SMTPOptions = [
                        'ssl' => [
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ]
                    ];
                }
            }
        }
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $fromAddress = Setting::get("from_email_address") ? Setting::get("from_email_address") : $from[0];
        $fromName = Setting::get("from_email_name") ? Setting::get("from_email_name") : $from[1];
        $mail->setFrom($fromAddress, $fromName);
        $mail->addAddress($to[0], $to[1]);
        foreach ($attachments as $filename => $data) {
            $mail->addStringAttachment($data, $filename);
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        ob_start();
        $mail->send();
        ob_end_clean();
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        $debugInfo = ob_get_clean();
        if (!empty($debugInfo)) {
            file_put_contents(__DIR__ . "/../smtp-debug-info.html", nl2br($debugInfo));
        }
        throw $e;
    }
}

/**
 * @param array $list
 * @param int $parts
 * @return array
 * @link https://stackoverflow.com/a/15723262/1273550
 */
function partition(array $list, int $parts): array
{
    $listLen = count($list);
    $partLen = floor($listLen / $parts);
    $partRem = $listLen % $parts;
    $partition = array();
    $mark = 0;
    for ($i = 0; $i < $parts; $i++) {
        $increment = ($i < $partRem) ? $partLen + 1 : $partLen;
        $partition[$i] = array_slice($list, $mark, $increment);
        $mark += $increment;
    }
    return $partition;
}

/**
 * @return array
 * @throws Exception
 * @link https://stackoverflow.com/a/17355238/1273550
 */
function generate_timezone_list(): array
{
    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

    $timezone_offsets = array();
    foreach ($timezones as $timezone) {
        $tz = new DateTimeZone($timezone);
        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
    }

    // sort timezone by offset
    asort($timezone_offsets);

    $timezone_list = array();
    foreach ($timezone_offsets as $timezone => $offset) {
        $offset_prefix = $offset < 0 ? '-' : '+';
        $offset_formatted = gmdate('H:i', abs($offset));

        $pretty_offset = "UTC{$offset_prefix}{$offset_formatted}";

        $timezone_list[$timezone] = "({$pretty_offset}) $timezone";
    }

    return $timezone_list;
}

/**
 * @param string $time
 * @return DateTime
 */
function getDatabaseTime(string $time): DateTime
{
    if (isset($_SESSION["timeZone"])) {
        $result = date_create($time, new DateTimeZone($_SESSION["timeZone"]));
        $result->setTimezone(new DateTimeZone(TIMEZONE));
        return $result;
    } else {
        return date_create($time);
    }
}

/**
 * @param string $time
 * @return DateTime
 */
function getDisplayTime(string $time): DateTime
{
    $result = date_create($time);
    if (isset($_SESSION["timeZone"])) {
        $result->setTimezone(new DateTimeZone($_SESSION["timeZone"]));
    }
    return $result;
}

/**
 * @param string $option
 * @param string $value
 * @param boolean $selected
 * @param array $data
 * @return void
 */
function createOption(string $option, string $value, bool $selected, array $data = [])
{
    echo "<option value='{$value}' ";
    foreach ($data as $key => $value) {
        echo "data-{$key}='$value' ";
    }
    if ($selected) {
        echo "selected=selected";
    }
    echo ">{$option}</option>";
}

/**
 * @param string $number
 * @return boolean
 */
function isValidMobileNumber(string $number, bool $multiple = false): bool
{
    if ($multiple) {
        $mobileNumbers = explode('|', $number);
        foreach ($mobileNumbers as $mobileNumber) {
            if (!ctype_digit(ltrim($mobileNumber, '+'))) {
                return false;
            }
        }
        return true;
    }
    return ctype_digit(ltrim($number, '+'));
}

/**
 * @param string $string
 * @return string
 *
 * @link https://stackoverflow.com/a/23131396/1273550
 */
function sanitize(string $string): string
{
    return trim(preg_replace('/\p{C}+/u', "", $string));
}

function sanitizeByReference(&$value)
{
    $value = sanitize($value);
}

function trimByReference(&$value, $key)
{
    if ($key != "message" && $key != "response") {
        $value = trim($value);
    }
}

/**
 * Check if a string is a valid timezone
 *
 * timezone_identifiers_list() requires PHP >= 5.2
 *
 * @param string $timezone
 * @return bool
 */
function isValidTimezone(string $timezone): bool
{
    return in_array($timezone, timezone_identifiers_list());
}

/**
 * @throws Exception
 * @return mixed
 */
function lock($name, $callback) {
    $tmpDir = __DIR__ . "/../tmp";
    if (is_dir($tmpDir) || mkdir($tmpDir, 0755, true)) {
        $result = null;
        $lockFile = $tmpDir . "/{$name}";
        $lock = fopen($lockFile, 'w');
        if ($lock === false) {
            throw new Exception(__("error_creating_lock_file"));
        }
        if (flock($lock, LOCK_EX | LOCK_NB)) {
            try {
                $result = $callback();
            } finally {
                fclose($lock);
                unlink($lockFile);
            }
        } else {
            fclose($lock);
        }
        return $result;
    } else {
        throw new Exception(__("error_creating_directory", ["name" => "tmp"]));
    }
}

/**
 * @param $string
 * @return string
 *
 * @link https://stackoverflow.com/a/13479855/1273550
 */
function spintax($string): string
{
    // Returns random values found between { this | and }
    return preg_replace_callback("/{(.*?)}/", function ($match) {
        // Splits 'foo|bar' strings into an array
        $words = explode("|", $match[1]);
        // Grabs a random array entry and returns it
        return $words[array_rand($words)];
        // The input string, which you provide when calling this func
    }, $string);
}

function validateJson($json, $schema): bool
{
    $decodedValue = json_decode($json);

    if (json_last_error() === JSON_ERROR_NONE) {
        $validator = new Validator();
        $result = $validator->validate($decodedValue, $schema);

        if ($result->isValid()) {
            return true;
        }
    }

    return false;
}

/**
 * @throws \Exception
 */
function getFirebaseServiceAccountJson(): string|null
{
    if (isset($_FILES["firebase_service_account"]["tmp_name"]) && is_uploaded_file($_FILES["firebase_service_account"]["tmp_name"])) {
        $json = file_get_contents($_FILES["firebase_service_account"]["tmp_name"]);
        if ($json && !validateJson($json, file_get_contents(__DIR__ . "/../js/schema.json"))) {
            throw new Exception(__("error_invalid_firebase_service_account_json"));
        }
        return $json;
    }
    return null;
}

function getUserIpAddress()
{
    if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (! empty($_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function countMessageCredits($number, $message, $type): float|int
{
    if ($type === "mms") {
        return count(explode("|", $number));
    } else {
        if (Setting::get("deplete_credit_for_each_sms_part")) {
            $smsCalc = new SmsLengthCalculator();
            return $smsCalc->getPartCount($message);
        } else {
            return 1;
        }
    }
}