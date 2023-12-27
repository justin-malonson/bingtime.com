<?php
/**
 * @var User $logged_in_user
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

if (!isset($sims)) {
    $sims = $logged_in_user->getSims();
}

?>

<script type="text/javascript">

    $(function () {
        const deviceInput = $('#deviceInput');

        let sims = <?= json_encode($sims, JSON_FORCE_OBJECT) ?>;

        function getSims(deviceId) {
            let simsInput = $('#simInput');
            simsInput.html('');
            simsInput.append('<option value=""><?=__("default")?></option>');
            if (sims[deviceId]) {
                $.each(sims[deviceId], function (val, label) {
                    let selected = '';
                    <?php if(isset($_REQUEST["sim"])) { ?>
                    if (val === '<?=$_REQUEST["sim"]?>') {
                        selected = 'selected="selected"';
                    }
                    <?php } ?>
                    simsInput.append(`<option value="${val}" ${selected}>${label}</option>`);
                });
            }
        }

        deviceInput.change(function () {
            getSims(this.value);
        });

        getSims(deviceInput.val());
    });

</script>
