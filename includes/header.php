<?php
/**
 * @var string $currentPage
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$showSubscriptions = false;
$activePlansCount = 0;
$activePlansCount = Plan::where("enabled", 1)->count();
if ($_SESSION["isAdmin"]) {
    $showSubscriptions = true;
} else {
    $showSubscriptions = Subscription::where("Subscription.userID", $_SESSION["userID"])->count() > 0;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/head.php" ?>
    <?php if ($currentPage == "subscribe.php") { ?>
        <link rel="stylesheet" href="css/pricing-table.css">
    <?php } ?>
    <?php if ($currentPage == "settings.php") { ?>
        <!-- Trumbowyg -->
        <link href="components/trumbowyg/dist/ui/trumbowyg.min.css" rel="stylesheet">
        <!-- Trumbowyg plugins -->
        <link rel="stylesheet" href="components/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css">
    <?php } ?>
</head>
<body class="hold-transition skin-<?= Setting::get("skin"); ?> sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="<?= Setting::get("logo_src"); ?>" alt="logo"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">
                <img src="<?= Setting::get("logo_src"); ?>" alt="logo">
                <span id="application-title"><?= htmlentities(__("application_title"), ENT_QUOTES); ?></span>
            </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only"><?= __("toggle_navigation") ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>


            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="profile.php">
                            <img src="img/avatar5.png" class="user-image" alt="User Image">
                            <span class="hidden-xs user-name"><?php echo htmlentities($_SESSION["name"], ENT_QUOTES); ?></span>
                        </a>
                    </li>
                    <!-- Sign Out Button -->
                    <li>
                        <a href="logout.php"><i class="fa fa-sign-out"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="img/avatar5.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p class="user-name"><?php echo htmlentities($_SESSION["name"], ENT_QUOTES); ?></p>
                    <a href="profile.php"><i class="fa fa-circle text-success"></i><?= __("online") ?></a>
                </div>
            </div>
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li <?php if ($currentPage == "dashboard.php") echo 'class="active"'; ?>>
                    <a href="dashboard.php">
                        <i class="fa fa-dashboard"></i>
                        <span><?= __("dashboard") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "profile.php") echo 'class="active"'; ?>>
                    <a href="profile.php">
                        <i class="fa fa-user"></i>
                        <span><?= __("profile") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "devices.php") echo 'class="active"'; ?>>
                    <a href="devices.php">
                        <i class="fa fa-android"></i>
                        <span><?= __("devices") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "blacklist.php") echo 'class="active"'; ?>>
                    <a href="blacklist.php">
                        <i class="fa fa-ban"></i>
                        <span><?= __("blacklist") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "templates.php") echo 'class="active"'; ?>>
                    <a href="templates.php">
                        <i class="fa fa-sticky-note"></i>
                        <span><?= __("templates") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "contacts.php") echo 'class="active"'; ?>>
                    <a href="contacts.php">
                        <i class="fa fa-address-card"></i>
                        <span><?= __("contacts") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "messages.php") echo 'class="active"'; ?>>
                    <a href="messages.php">
                        <i class="fa fa-envelope"></i>
                        <span><?= __("messages") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "sender.php") echo 'class="active"'; ?>>
                    <a href="sender.php">
                        <i class="fa fa-send"></i>
                        <span><?= __("sender") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "ussd.php") echo 'class="active"'; ?>>
                    <a href="ussd.php">
                        <i class="fa fa-phone"></i>
                        <span><?= __("ussd"); ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "auto-responder.php") echo 'class="active"'; ?>>
                    <a href="auto-responder.php">
                        <i class="fa fa-mail-reply-all"></i>
                        <span><?= __("auto_responder") ?></span>
                    </a>
                </li>
                <li <?php if ($currentPage == "api.php") echo 'class="active"'; ?>>
                    <a href="api.php">
                        <i class="fa fa-code"></i>
                        <span><?= __("api") ?></span>
                    </a>
                </li>
                <?php if ($showSubscriptions) { ?>
                    <li <?php if ($currentPage == "subscriptions.php") echo 'class="active"'; ?>>
                        <a href="subscriptions.php">
                            <i class="fa fa-money"></i>
                            <span><?= __("subscriptions") ?></span>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($_SESSION["isAdmin"]) { ?>
                    <li <?php if ($currentPage == "plans.php") echo 'class="active"'; ?>>
                        <a href="plans.php">
                            <i class="fa fa-table"></i>
                            <span><?= __("plans") ?></span>
                        </a>
                    </li>
                    <li <?php if ($currentPage == "manage-users.php") echo 'class="active"'; ?>>
                        <a href="manage-users.php">
                            <i class="fa fa-users"></i>
                            <span><?= __("manage_users") ?></span>
                        </a>
                    </li>
                    <li <?php if ($currentPage == "settings.php") echo 'class="active"'; ?>>
                        <a href="settings.php">
                            <i class="fa fa-gear"></i>
                            <span><?= __("settings") ?></span>
                        </a>
                    </li>
                <?php } else { ?>
                    <?php if (Setting::get("paypal_enabled") && $activePlansCount > 0) { ?>
                        <li <?php if ($currentPage == "subscribe.php") echo 'class="active"'; ?>>
                            <a href="subscribe.php">
                                <i class="fa fa-newspaper-o"></i>
                                <span><?= __("subscribe") ?></span>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <hr>
                <li>
                    <a href="<?= Setting::get("application_url") ?>">
                        <i class="fa fa-download"></i>
                        <span><?= __("download_app") ?></span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>