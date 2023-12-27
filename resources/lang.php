<?php

define("PRIMARY_LANGUAGE", "English");

/**
 * @param string $language
 * @return bool|string
 * @throws Exception
 */
function setLanguage($language = PRIMARY_LANGUAGE)
{
    $result = applyLanguage($language);
    if ($result === false) {
        if ($language !== PRIMARY_LANGUAGE) {
            $result = applyLanguage(PRIMARY_LANGUAGE);
        }
        if ($result === false) {
            $files = getLanguageFiles();
            foreach ($files as $file) {
                $result = applyLanguage($file);
                if ($result) {
                    break;
                }
            }
        }
    }
    return $result;
}

/**
 * @param string $language
 * @return bool|string
 * @throws Exception
 */
function applyLanguage($language = PRIMARY_LANGUAGE)
{
    $languageFilePath = getLanguageFilePath($language);
    if ($languageFilePath) {
        include $languageFilePath;
        if (!isset($lang)) {
            throw new Exception("Please provide a valid language file.");
        }
        $GLOBALS["lang"] = $lang;
        return $language;
    } else {
        return false;
    }
}

/**
 * @param string $language
 * @return bool|string
 */
function getLanguageFilePath($language)
{
    $languageFile = __DIR__ . "/lang/$language.php";
    if (file_exists($languageFile)) {
        return $languageFile;
    }
    return false;
}

/**
 * @return array
 */
function getLanguageFiles()
{
    $files = [];
    $fi = new FilesystemIterator(__DIR__ . '/lang', FilesystemIterator::SKIP_DOTS);
    foreach ($fi as $fileInfo) {
        $name = str_replace(".{$fileInfo->getExtension()}", "", $fileInfo->getFilename());
        $files[] = $name;
    }
    return $files;
}

/**
 * @param string $msgId
 * @param array $placeHolders
 * @param string|bool $language
 * @return string
 * @throws Exception
 */
function __($msgId, $placeHolders = array(), $language = false)
{
    if (empty($GLOBALS["lang"])) {
        setLanguage();
    }

    if ($language) {
        $originalLang = $GLOBALS["lang"];
        applyLanguage($language);
    }

    $result = $GLOBALS["lang"][$msgId];
    foreach ($placeHolders as $placeHolder => $placeHolder_value) {
        $result = str_replace("%$placeHolder%", $placeHolder_value, $result);
    }

    if (isset($originalLang)) {
        $GLOBALS["lang"] = $originalLang;
    }

    return $result;
}