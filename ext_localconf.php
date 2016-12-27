<?php
defined('TYPO3_MODE') or die();

// Adding ColorPicker to SearchBar
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['searchbar']['additionalFunctions']['tx_searchbarcolorpicker'] = [
    'title' => 'Color Picker',
    'namespaceOfClass' => 'SvenJuergens\\SearchbarColorpicker\\Colorpicker'
];
