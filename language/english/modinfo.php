<?php
include_once XOOPS_ROOT_PATH . '/modules/tadtools/language/' . $xoopsConfig['language'] . '/modinfo_common.php';

define('_MI_TADUP_NAME', 'Tad Uploader');
define('_MI_TADUP_DESC', 'Simple module for uploading files~');
define('_MI_TADUP_CREDITS', 'prolin lin (prolin99@gmail.com)');
define('_MI_TADUP_ADMENU1', 'Folder');
define('_MI_TADUP_ADMENU2', 'Permission');

define('_MI_TADUP_BNAME1', 'Last Files');
define('_MI_TADUP_BDESC1', 'Files uploaded recently');

define('_MI_PAGE_SHOW_NUM', 'File per page');
define('_MI_PAGE_SHOW_NUM_DESC', 'How many files displayed per page.');
define('_MI_ONLY_SHOW_DESC', 'Replaceable file title');
define('_MI_ONLY_SHOW_DESC_DESC', 'Display description instead of original filename.');

define('_MI_SHOW_MODE', 'Default display mode');
define('_MI_SHOW_MODE_DESC', 'Select Icon or List as default display mode.');
define('_MI_SHOW_MODE_MORE', 'List');
define('_MI_SHOW_MODE_ICON', 'Icon');

define('_MI_TADUPLOADER_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_TADUPLOADER_HELP_HEADER', __DIR__ . '/help/helpheader.html');
define('_MI_TADUPLOADER_BACK_2_ADMIN', 'Back to Administration of ');

//help
define('_MI_TADUPLOADER_HELP_OVERVIEW', 'Overview');
