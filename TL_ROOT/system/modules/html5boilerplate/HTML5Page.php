<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Kahlil Lechelt 2010
 * @author     Kahlil Lechelt
 * @package    html5 
 * @license    GNU/LGPL 
 * @filesource
 */


/**
 * Class HTML5Page
 *
 * This is heavily based on the XHTML5 Extension by Fabian Wolf which is
 * heavily based on PageRegular.php by Leo Feyer <http://www.contao.org>
 *
 * @copyright  Fluxpunkt 2010, Leo Feyer 2005-2010
 * @author     Fabian Wolf, Leo Feyer 
 * @package    Controller
 */
class HTML5Page extends PageRegular {

	protected $nbsp = '&nbsp;';
	protected $defaultTemplate = 'fe_html5_page';

	private function httpAccept() {
		$strAccept = explode(';', strtolower($_SERVER['HTTP_ACCEPT']), 1);
		return array_values(array_unique(explode(',', $strAccept[0])));
	}
	
	protected function fixEmptySections(Database_Result $objLayout) {
		// Add an invisible character to empty sections (IE fix)
		if (!$this->Template->header && $objLayout->header) {
			$this->Template->header = $this->nbsp;
		}

		if (!$this->Template->left && ($objLayout->cols == '2cll' || $objLayout->cols == '3cl')) {
			$this->Template->left = $this->nbsp;
		}

		if (!$this->Template->right && ($objLayout->cols == '2clr' || $objLayout->cols == '3cl')) {
			$this->Template->right = $this->nbsp;
		}

		if (!$this->Template->footer && $objLayout->footer) {
			$this->Template->footer = $this->nbsp;
		}
	}
	
	public function generate(Database_Result $objPage) {
		$GLOBALS['TL_KEYWORDS'] = '';
		$GLOBALS['TL_LANGUAGE'] = $objPage->language;

		$this->loadLanguageFile('default');

		$objLayout = $this->getPageLayout($objPage->layout);
		$objPage->template = strlen($objLayout->template) ? $objLayout->template : $this->defaultTemplate;
		$objPage->templateGroup = $objLayout->templates;

		// Initialize the template
		$this->createTemplate($objPage, $objLayout);

		// Initialize modules and sections
		$arrCustomSections = array();
		$arrSections = array('header', 'left', 'right', 'main', 'footer');
		$arrModules = deserialize($objLayout->modules);

		// Generate all modules
		foreach ($arrModules as $arrModule) {
			if (in_array($arrModule['col'], $arrSections)) {
				$this->Template->$arrModule['col'] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
			} else {
				$arrCustomSections[$arrModule['col']] .= $this->getFrontendModule($arrModule['mod'], $arrModule['col']);
			}
		}

		$this->Template->sections = $arrCustomSections;

		// HOOK: modify the page or layout object
		if (isset($GLOBALS['TL_HOOKS']['generatePage']) && is_array($GLOBALS['TL_HOOKS']['generatePage'])) {
			foreach ($GLOBALS['TL_HOOKS']['generatePage'] as $callback) {
				$this->import($callback[0]);
				$this->$callback[0]->$callback[1]($objPage, $objLayout, $this);
			}
		}

		//Set page title and description AFTER the modules have been generated
		$this->Template->mainTitle = $objPage->rootTitle;
		$this->Template->pageTitle = strlen($objPage->pageTitle) ? $objPage->pageTitle : $objPage->title;
		$this->Template->title = $this->Template->mainTitle . ' - ' . $this->Template->pageTitle;
		$this->Template->description = str_replace(array("\n", "\r", '"'), array(' ' , '', ''), $objPage->description);

		// Body onload and body classes
		$this->Template->onload = trim($objLayout->onload);
		$this->Template->class = trim($objLayout->cssClass . ' ' . $objPage->cssClass);

		// HOOK: extension "bodyclass"
		if (in_array('bodyclass', $this->Config->getActiveModules())) {
			if (strlen($objPage->cssBody)) {
				$this->Template->class .= ' ' . $objPage->cssBody;
			}
		}

		// Execute AFTER the modules have been generated and create footer scripts first
		$this->createFooterScripts($objLayout);
		$this->createHeaderScripts($objLayout);

		$this->fixEmptySections($objLayout);

		// Print the template to the screen
		$this->Template->output();
	}	
	
	protected function initTemplate(Database_Result $objPage, Database_Result $objLayout) {
		$arrAccept = $this->httpAccept();
		$contype = 'text/html';
		$doctype = '';
	
		switch($objLayout->doctype) {
			case 'xhtml_strict':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
				if (in_array('application/xhtml+xml', $arrAccept)) {
					$contype = 'application/xhtml+xml';
					$this->nbsp = '&#160;';
				}
				break;

			case 'xhtml_trans':
				$doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
				break;
			case 'html5' :
			default:
				$doctype = '<!doctype html>' . "\n";
				break;
		}
		$this->Template = new FrontendTemplate($objPage->template, $contype);
		$this->Template->doctype = $doctype;
	}

	/**
	 * Overwrite PageRegular::createTemplate()
	 *
	 * We don't call the parent implementation as we need to change quite a bit and it faster during
	 * runtime if we make it right the first time than calling the parent and changing the template
	 * afterwards.
	 * So this is quite some code duplication.
	 */
	protected function createTemplate(Database_Result $objPage, Database_Result $objLayout) {

		$this->initTemplate($objPage, $objLayout);
		
		// Robots
		if (strlen($objPage->robots)) {
			$this->Template->robots = '<meta name="robots" content="' . $objPage->robots . '" />' . "\n";
		}

		// Initialize margin
		$arrMargin = array
		(
			'left'   => '0 auto 0 0',
			'center' => '0 auto',
			'right'  => '0 0 0 auto'
		);

		$strFramework = '';

		// Wrapper
		if ($objLayout->static) {
			$arrSize = deserialize($objLayout->width);
			$strFramework .= sprintf('#wrapper { width:%s; margin:%s; }', $arrSize['value'] . $arrSize['unit'], $arrMargin[$objLayout->align]) . "\n";
		}

		// Header
		if ($objLayout->header)	{
			$arrSize = deserialize($objLayout->headerHeight);

			if ($arrSize['value'] > 0) {
				switch ($objLayout->doctype) {
					case 'html5':
					default:
						$strFramework .= '#header';
						break;
				}
				$strFramework .= sprintf(' { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			}
		}

		$strMain = '';

		// Left column
		if ($objLayout->cols == '2cll' || $objLayout->cols == '3cl') {
			$arrSize = deserialize($objLayout->widthLeft);

			if ($arrSize['value'] > 0) {
				$strFramework .= sprintf('#left { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
				$strMain .= sprintf(' margin-left:%s;', $arrSize['value'] . $arrSize['unit']);
			}
		}

		// Right column
		if ($objLayout->cols == '2clr' || $objLayout->cols == '3cl') {
			$arrSize = deserialize($objLayout->widthRight);

			if ($arrSize['value'] > 0) {
				$strFramework .= sprintf('#right { width:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
				$strMain .= sprintf(' margin-right:%s;', $arrSize['value'] . $arrSize['unit']);
			}
		}

		// Main column
		if (strlen($strMain)) {
			$strFramework .= sprintf('#main { %s }', $strMain) . "\n";
		}

		// Footer
		if ($objLayout->footer) {
			$arrSize = deserialize($objLayout->footerHeight);

			if ($arrSize['value'] > 0) {
			
				switch ($objLayout->doctype) {
					case 'html5':
					default:
						$strFramework .= '#footer';
						break;
				}
			
				$strFramework .= sprintf(' { height:%s; }', $arrSize['value'] . $arrSize['unit']) . "\n";
			}
		}

		$this->Template->framework = '';

		// Add layout specific CSS
		if (!empty($strFramework)) {
			$this->Template->framework .= '<style type="text/css" media="screen">' . "\n";
			$this->Template->framework .= '<!--/*--><![CDATA[/*><!--*/' . "\n";
			$this->Template->framework .= $strFramework;
			$this->Template->framework .= '/*]]>*/-->' . "\n";
			$this->Template->framework .= '</style>' . "\n";
		}

		// Include basic style sheets
		$this->Template->framework .= '<link rel="stylesheet" href="system/contao.css" type="text/css" media="screen">' . "\n";
		$this->Template->framework .= '<!--[if lte IE 7]> <link rel="stylesheet" href="system/iefixes.css" type="text/css" media="screen"> <![endif]-->' . "\n";

		// MooTools scripts
		if ($objLayout->mooSource == 'moo_googleapis') {
			$this->Template->mooScripts  = '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/'. MOOTOOLS_CORE .'/mootools-yui-compressed.js"></script>' . "\n";
			$this->Template->mooScripts .= '<script type="text/javascript" src="plugins/mootools/mootools-more.js?'. MOOTOOLS_MORE .'"></script>' . "\n";
		} else {
			$this->Template->mooScripts  = '<script type="text/javascript" src="plugins/mootools/mootools-core.js?'. MOOTOOLS_CORE .'"></script>' . "\n";
			$this->Template->mooScripts .= '<script type="text/javascript" src="plugins/mootools/mootools-more.js?'. MOOTOOLS_MORE .'"></script>' . "\n";
		}
		
		// Initialize sections
		$this->Template->header = '';
		$this->Template->left = '';
		$this->Template->main = '';
		$this->Template->right = '';
		$this->Template->footer = '';

		// Initialize custom layout sections
		$this->Template->sections = array();
		$this->Template->sPosition = $objLayout->sPosition;

		// Default settings
		$this->Template->layout = $objLayout;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->base = $this->Environment->base;		
	}
}

?>