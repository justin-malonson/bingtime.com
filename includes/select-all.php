<?php
if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

if (isset($_COOKIE["DEVICE_ID"])) { ?>

    <script type="text/javascript">
        $(function () {
            $('.selectAllDevices').click(function (event) {
                event.preventDefault();
                let input = $(this).data('target');
                $(input).find('option').prop('selected', 'selected').end();
            })

            $('.clearAllDevices').click(function (event) {
                event.preventDefault();
                let input = $(this).data('target');
                $(input).find('option').prop('selected', false).end();
            })
        })
    </script>

<?php } else { ?>

    <script type="text/javascript">
        $(function () {
            $('.selectAllDevices').click(function (event) {
                event.preventDefault();
                let input = $(this).data('target');
                $(input).select2('destroy').find('option').prop('selected', 'selected').end().select2();
            })

            $('.clearAllDevices').click(function (event) {
                event.preventDefault();
                let input = $(this).data('target');
                $(input).select2('destroy').find('option').prop('selected', false).end().select2();
            })
        })
    </script>

<?php } ?>