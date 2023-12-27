<?php
/**
 * @var string $currentLanguage
 */

if (count(get_included_files()) == 1) {
    http_response_code(403);
    die("HTTP Error 403 - Forbidden");
}

$languageFiles = getLanguageFiles();
if (count($languageFiles) > 1) {
    ?>
    <div style="position: absolute; right: 28px; top: 28px">
        <form method="get" id="languageForm">
            <select title="Language" name="language" id="languageInput"
                    class="form-control select2" onchange="$('#languageForm').submit()" style="width: 125px">
                <?php
                foreach ($languageFiles as $languageFile) {
                    createOption(ucfirst($languageFile), $languageFile, $languageFile === $currentLanguage);
                }
                ?>
            </select>
        </form>
    </div>
<?php } ?>
