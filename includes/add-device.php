<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}
?>

<div class="modal fade" id="modal-add-device">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= __("add_device") ?></h4>
            </div>
            <div class="modal-body">
                <p><?= __("add_device_instruction"); ?></p>
                <ol>
                    <li><?= __("add_device_step_1", ["app_url" => __("application_url")]); ?></li>
                    <li><?= __("add_device_step_2") ?></li>
                    <li><?= __("add_device_step_3") ?><br/><span style="text-align: center; display: block;"><img
                                    src="qr-code.php" alt="Sign in QR code" id="qr-code"></span></li>
                    <li><?= __("add_device_step_4") ?></li>
                </ol>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
