<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo htmlentities($title, ENT_QUOTES); ?></title>
<meta name="description" content="<?= htmlentities(__('application_description'), ENT_QUOTES) ?>">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="components/bootstrap/dist/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="components/font-awesome/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="components/ionicons/dist/css/ionicons.min.css">
<!-- Select2 -->
<link rel="stylesheet" href="components/select2/dist/css/select2.min.css">
<!-- toastr -->
<link rel="stylesheet" href="components/toastr/build/toastr.min.css">
<!-- flatpickr -->
<link rel="stylesheet" href="components/flatpickr/dist/flatpickr.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="components/datatables.net-bs/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="components/datatables.net-responsive-bs/css/responsive.bootstrap.min.css">
<!-- Dropzone -->
<link rel="stylesheet" href="components/dropzone/dist/min/dropzone.min.css">
<!-- Pace -->
<link rel="stylesheet" href="components/pace-js/themes/blue/pace-theme-corner-indicator.css">
<!-- Theme style -->
<link rel="stylesheet" href="css/AdminLTE.min.css">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="css/skins/skin-<?= Setting::get("skin"); ?>.min.css">
<!-- Custom style -->
<link rel="stylesheet" href="css/custom.css">
<?php if (isset($_COOKIE["DEVICE_ID"])) { ?>
    <!-- Android webview specific style -->
    <link rel="stylesheet" href="css/webview.css">
<?php } ?>

<link rel="shortcut icon" href="<?= Setting::get("favicon_src"); ?>" type="image/x-icon">
<link rel="icon" href="<?= Setting::get("favicon_src"); ?>" type="image/x-icon">
<link rel="apple-touch-icon" href="<?= Setting::get("logo_src"); ?>">

<script>
    window.paceOptions = {
        startOnPageLoad: false,
        eventLag: false,
    };
</script>
<!-- Pace -->
<script src="components/pace-js/pace.min.js"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Google Font -->
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">