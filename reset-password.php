<?php
require_once __DIR__ . "/includes/session.php";

if (isset($_SESSION["userID"])) {
    header("location:dashboard.php");
    die;
}

$title = __("application_title") . " | " . __("reset_password");

try {
    if (isset($_REQUEST["code"]) && isset($_REQUEST["email"])) {
        $users = User::where("email", $_REQUEST["email"])->read_all();
        if (count($users) <= 0) {
            throw new Exception(__("error_email_not_exist"));
        } else {
            $user = $users[0];
            $timestamp = decrypt($_REQUEST["code"], $user->getPassword(), $user->getDateAdded()->format(DATE_RFC850));
            if ($timestamp && ctype_digit($timestamp)) {
                $diff = time() - $timestamp;
                if ($diff > 86400) {
                    throw new Exception(__("error_link_expired"));
                } else {
                    $user = $users[0];
                    $random_password = random_str(16);
                    $user->setPassword($random_password);
                    MysqliDb::getInstance()->startTransaction();
                    $user->save();
                    $admin = User::getAdmin();
                    $from = array($admin->getEmail(), $admin->getName());
                    $to = array($user->getEmail(), $user->getName());
                    $subject = __("reset_password_email_subject", ["app" => __("application_title")]);
                    $serverURL = getServerURL();
                    $body = __("reset_password_email_body", ["app" => __("application_title"), "user" => htmlentities($user->getName(), ENT_QUOTES), "userEmail" => $user->getEmail(), "admin" => htmlentities($admin->getName(), ENT_QUOTES), "adminEmail" => $admin->getEmail(), "password" => $random_password, "server" => $serverURL]);
                    try {
                        sendEmail($from, $to, $subject, $body);
                    } catch (\PHPMailer\PHPMailer\Exception $e) {
                        throw new Exception(__("error_send_email_reset_password") . " {$e->errorMessage()}");
                    }
                    MysqliDb::getInstance()->commit();
                    $success = __("success_reset_password");
                }
            } else {
                throw new Exception(__("error_link_invalid"));
            }
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/head.php" ?>
    <style type="text/css">
        body {
            overflow: hidden;
        }
    </style>
</head>
<body class="hold-transition login-page">

<?php require_once __DIR__ . "/includes/language-form.php"; ?>

<div class="login-box">
    <div class="login-logo">
        <img src="<?= Setting::get("logo_src"); ?>" style="width: 64px; height: 64px" alt="logo">
        <a href="index.php"><?= __("application_title"); ?></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"><?= __("reset_your_password"); ?></p>

        <form id="resetPasswordForm" method="post">
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" placeholder="<?= __("email"); ?>" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <a href="index.php"><?= __("sign_in"); ?></a>
                </div>
                <!-- /.col -->
                <div class="col-xs-6 pull-right">
                    <button type="submit" id="resetPasswordButton" name="resetPassword"
                            class="btn btn-primary btn-block btn-flat"><?= __("reset_password"); ?></button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php require_once __DIR__ . "/includes/footer.php" ?>
<?php require_once __DIR__ . "/includes/common-js.php" ?>

<script type="text/javascript">

    $(function () {
        const resetPasswordForm = $('#resetPasswordForm');
        const resetPasswordButton = $('#resetPasswordButton');
        const options = {positionClass: "toast-top-center", closeButton: true};

        resetPasswordForm.submit(function (event) {
            event.preventDefault();
            let postData = $(this).serialize();
            let url = "ajax/reset-password-link.php";
            resetPasswordButton.prop('disabled', true);
            ajaxRequest(url, postData).then(result => {
                toastr.success(result, null, options);
                event.target.reset();
            }).catch(reason => {
                toastr.error(reason, null, options);
            }).finally(() => {
                resetPasswordButton.prop('disabled', false);
            });
        });

        <?php if (isset($success)) { ?>
        toastr.success("<?=$success?>", null, options);
        <?php } ?>
        <?php if (isset($error)) { ?>
        toastr.success("<?=$error?>", null, options);
        <?php } ?>
    });

</script>
</body>
</html>
