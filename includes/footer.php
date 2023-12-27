<?php
/**
 * @var string $currentPage
 * @var string[] $accessibleScripts
 * @var User $logged_in_user
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}
?>

<?php if (!in_array($currentPage, $accessibleScripts)) { ?>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> <?= __("application_version") ?>
        </div>
        <?= __("application_copyright", ["present" => date('Y'), "name" => htmlentities(__("company_name"), ENT_QUOTES), "url" => __("company_url")]) ?>
    </footer>

    </div>
    <!-- ./wrapper -->

<?php } ?>

    <!-- jQuery 3 -->
    <script src="components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery Validation Plugin -->
    <script src="components/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Common Functionality -->
    <script src="js/common.js?md5=FA3C704CF9C6AA46357B9A93283EF9B5"></script>
    <!-- toastr -->
    <script src="components/toastr/build/toastr.min.js"></script>
    <!-- Select2 -->
    <script src="components/select2/dist/js/select2.full.min.js"></script>

<?php if (!in_array($currentPage, $accessibleScripts)) { ?>

    <!-- DataTables -->
    <script src="components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="components/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="components/datatables.net-responsive-bs/js/responsive.bootstrap.min.js"></script>
    <!-- Code Prettify -->
    <script src="components/code-prettify/src/run_prettify.js?skin=desert"></script>
    <!-- FastClick -->
    <script src="components/fastclick/lib/fastclick.js"></script>
    <!-- Dropzone -->
    <script src="components/dropzone/dist/min/dropzone.min.js"></script>
    <!-- AdminLTE App -->
    <script src="js/adminlte.min.js"></script>
    <!-- SMS Counter -->
    <script src="js/sms-counter.min.js"></script>

    <?php if (Setting::get("received_message_notification_enabled")) { ?>
    <script>
        <?php if (!isset($_COOKIE["DEVICE_ID"])) { ?>
        if (Notification.permission !== 'granted') {
            Notification.requestPermission();
        }

        <?php
            $data = MysqliDb::getInstance()->rawQueryOne("SELECT MAX(ID) as lastId FROM Message;");
            $lastId = empty($data["lastId"]) ? 0 : $data["lastId"];
        ?>

        let lastId = <?= $lastId; ?>;
        (function notify() {
            if (Notification.permission !== 'granted') {
                return;
            }

            ajaxRequest("ajax/get-received-messages.php", { lastId: lastId })
                .then(result => {
                    if (result.messages.length <= 0) {
                        return;
                    }

                    lastId = result.lastId;
                    result.messages.forEach(function (item, index, arr) {
                        const notification = new Notification(item.number, {
                            icon: '<?= Setting::get("logo_src"); ?>',
                            body: item.message,
                        });
                        notification.onclick = function () {
                            window.location.href = 'messages.php?status=Received';
                        };
                    });
                }).finally(() => {
                    setTimeout(notify, 5000);
                });
        })();
        <?php } ?>
    </script>
    <?php } ?>
<?php } ?>