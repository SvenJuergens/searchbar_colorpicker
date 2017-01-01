<?php
namespace SvenJuergens\SearchbarColorpicker;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class Colorpicker
{
    /* @var $localCObj ContentObjectRenderer */
    public $localCObj;

    public $templateFile;

    public function execute(&$row, &$searchEngineInput)
    {
        $this->init();
        echo $this->prepareOutput($searchEngineInput);
        exit;
    }

    public function init()
    {
        $this->localCObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $this->templateFile = ExtensionManagementUtility::extPath('searchbar_colorpicker') . 'Resources/Public/Html/templateColorPicker.html';
    }

    public function prepareOutput($searchEngineInput)
    {
        $imageNotAllowed = '';
        $path = ExtensionManagementUtility::siteRelPath('searchbar_colorpicker');

        //check User Input
        $imageURL = $this->checkInput($searchEngineInput);

        if ($imageURL === null) {
            // missing url
            $imageSrc = 'data:image/jpeg;base64,' . base64_encode(GeneralUtility::getUrl($path . 'Resources/Public/Images/fullLogo_SafeArea.jpg'));
        } elseif ($imageURL === false) {
            // image not allowed
            $imageSrc = '';
            $imageNotAllowed = 'Link not allowed only images (*.jpg, *.jpeg, *.gif, *.png) ';
        } else {
            $imageSrc = 'data:image/jpeg;base64,' . base64_encode(GeneralUtility::getUrl($imageURL));
        }
        /** @var StandaloneView $View */
        $View = GeneralUtility::makeInstance(StandaloneView::class);
        $View->setTemplatePathAndFilename($this->templateFile);
        $View->assignMultiple([
            'path' => $path,
            'imageSrc' => GeneralUtility::quoteJSvalue($imageSrc),
            'imageNotAllowed' => $imageNotAllowed ? $imageNotAllowed : ''
        ]);
        return $View->render();

//        $markerArray = array(
//            'path' => $path,
//            'imagesrc' => GeneralUtility::quoteJSvalue($imageSrc),
//            'imageNotAllowed' => $imageNotAllowed ? $imageNotAllowed : ''
//        );
//        return HtmlParser::substituteMarkerArray($subPart, $markerArray, '###|###', true, true);
    }

    public function checkInput($input)
    {
        if (!isset($input[1])) {
            return null;
        }

        $extension = substr(strrchr($input[1], '.'), 1);

        if (stristr($extension, 'gif')
            || stristr($extension, 'jpg')
            || stristr($extension, 'jpg')
            || stristr($extension, 'png')
        ) {
            return $input[1];
        } else {
            return false;
        }
    }
}
