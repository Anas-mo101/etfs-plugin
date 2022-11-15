<?php

/**
 * Loads all files found in a given folder.
 * Calls itself recursively for all sub folders.
 *
 * @param string $dir
 */
function requireFilesOfFolder($dir){
    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if (!$fileInfo->isDot()) {
            if ($fileInfo->isDir()) {
                requireFilesOfFolder($fileInfo->getPathname());
            } else {
                require_once $fileInfo->getPathname();
            }
        }
    }
}

$rootFolder = __DIR__.'/libs/Smalot/PdfParser';

$libs = array(
    __DIR__.'/libs/Smalot/PdfParser',
);

// Manually require files, which can't be loaded automatically that easily.
require_once $rootFolder.'/Element.php';
require_once $rootFolder.'/PDFObject.php';
require_once $rootFolder.'/Font.php';
require_once $rootFolder.'/Page.php';
require_once $rootFolder.'/Element/ElementString.php';
require_once $rootFolder.'/Encoding/AbstractEncoding.php';

require_once 'assets/class-atts.php';
require_once 'assets/FundDocuments.php';
require_once 'assets/DistributionDetail.php';
require_once 'assets/fields-display.php'; 
require_once 'data/GoogleSheetProvider.php';
require_once 'data/Pdf2Data.php';
require_once 'data/SFTP.php';
require_once 'data/CsvProvider.php';
require_once 'data/PostMeta.php';
require_once 'data/Calculations.php';
require_once 'data/MailCollector.php';
require_once 'data/Notices.php';
require_once 'data/xlsm_parser/XSLMParser.php';
require_once 'data/xlsm_parser/SimpleXLSX.php';


foreach ($libs as $lib) {
    requireFilesOfFolder($lib);
}

