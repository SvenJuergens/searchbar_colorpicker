<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Sven Juergens <t3@blue-side.de>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

class tx_searchbarcolorpicker {
    /* @var $localCObj tslib_cObj */
    public $localCObj;

    public $templateFile;

    public function execute(&$row, &$searchEngineInput) {
        $this->init();
        echo  $this->prepareOutput($searchEngineInput);
        exit;

    }

    public function init() {
        $this->localCObj = t3lib_div::makeInstance('tslib_cObj');
        $this->templateFile = t3lib_extMgm::extPath('searchbar_colorpicker') . 'Resources/html/templateColorPicker.html';
    }

    public function prepareOutput($searchEngineInput) {
	    $imageNotAllowed = '';
        $subpart = t3lib_div::getUrl($this->templateFile);

        $path = t3lib_extMgm::siteRelPath('searchbar_colorpicker');

	    //check User Input
	    $imageURL = $this->checkInput($searchEngineInput);
        if($imageURL === NULL){
		    // keine URL uebermittelt
		    $imageSrc =    'data:image/jpeg;base64,' . base64_encode(t3lib_div::getUrl( $path . 'Resources/image/fullLogo_SafeArea.jpg'));

        }elseif($imageURL === FALSE){
		     // Link nicht erlaubt
	        $imageSrc   = '';
	        $imageNotAllowed = 'Link not allowed only images (*.jpg, *.jpeg, *.gif, *.png) ';
	    }else{
	        $imageSrc =    'data:image/jpeg;base64,' . base64_encode(t3lib_div::getUrl(  $imageURL ));
        }

        $markerArray = array(
            'path' => $path,
            'imagesrc' => t3lib_div::quoteJSvalue($imageSrc),
	        'imageNotAllowed' => $imageNotAllowed ? $imageNotAllowed : ''
        );

        return t3lib_parsehtml::substituteMarkerArray($subpart, $markerArray, '###|###', TRUE, TRUE);
    }

	public function checkInput($input) {
		   if(!isset($input[1])){
			   return NULL;
		   }

		$extension = substr(strrchr($input[1], '.'), 1);

		if(stristr($extension, 'gif') || stristr($extension, 'jpg') || stristr($extension, 'jpg') || stristr($extension, 'png')){
			 return $input[1];
		}else{
			return FALSE;
		}

	}
}
