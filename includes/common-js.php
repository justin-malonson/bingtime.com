<?php
if(count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}
?>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        <?= isset($_COOKIE["DEVICE_ID"]) ? '' : '$(".select2").select2();'?>
    });
</script>
