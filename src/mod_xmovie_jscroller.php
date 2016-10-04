<?php
/*
 * @package Joomla 3.0
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component XMovie Component
 * @copyright Copyright (C) Dana Harris optikool.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die('Restricted access');

if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

require_once (JPATH_SITE.DS.'components'.DS.'com_xmovie'.DS.'router.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_xmovie'.DS.'helpers'.DS.'movie.php');

MovieHelper::setCookieParams();	

$jscroller = new modXMovieJScrollerHelper($params);
$lists = $jscroller->getList();
$jscroller->loadYoutubeTracking();

require JModuleHelper::getLayoutPath('mod_xmovie_jscroller', $params->get('layout', 'default'));
?>
