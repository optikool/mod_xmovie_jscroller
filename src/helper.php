<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component XMovie Component
 * @copyright Copyright (C) Dana Harris optikool.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */ 
defined('_JEXEC') or die('Restricted access');

class modXMovieJScrollerHelper {
	private $_moduleclass_sfx;
	private $_number_movie;	
	private $_number_to_view;
	private $_category_id;
	private $_catids;
	private $_scroll_sort_method;
	private $_scroll_direction;
	private $_scroll_loop;
	private $_scroll_speed;
	private $_scroll_easing;
	private $_show_movie_name;
	private $_show_date;
	private $_show_hits;
	private $_show_category;
	private $_category_list;
	private $_thumb_width;
	private $_thumb_height;
	private $_query;
	private $_catslug;
	private $_collslug;
	private $_catnames;
	private $_enable_css;
	private $_auto_scroll;
	private $_auto_scroll_step;
	private $_auto_scroll_interval;
	private $_auto_scroll_autopause;
	private $_load_jquery;
	private $_add_embed_code;
	private $_movie_width;
	private $_movie_height;
	private $_movie_percent;
	private $_params;
		
	public function __construct(&$params) {		
		global $scroller_id;
		
		$this->slideid	= ++$scroller_id;			

		$this->_moduleclass_sfx			= $params->get('moduleclass_sfx', '');
		$this->_number_movie			= $params->get('number_movie', 5);	
		$this->_category_id				= $params->get('category_id', '');
		$this->_scroll_sort_method		= $params->get('scroll_sort_method', 'random');
		$this->_show_movie_name			= $params->get('show_movie_name', 1);
		$this->_show_date				= $params->get('show_date', 0);
		$this->_show_hits				= $params->get('show_hits', 1);
		$this->_show_category			= $params->get('show_category', 0);
		$this->_rows					= '';
		$this->_params					= $params;
	}

	public function getList() {
		$cfgParams =  JComponentHelper::getParams('com_xmovie');
		$user	= JFactory::getUser();
		$groups	= implode(',', $user->getAuthorisedViewLevels());
		$cat_ids = implode(',', $this->_params->get('category_id'));
		$itemids = MovieHelper::getItemIds();
		$tmpRows = array();
		$cTime = time();
		$sort_method = $this->_params->get('scroll_sort_method');
		$cLimit = $this->_params->get('number_movie');
		$items = array();
		
		// Create a new query object.
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		
		// Select required fields from the categories.
		$query->select('a.*');
		$query->from($db->quoteName('#__xmovie_movies').' AS a');
		
		// Join over the categories.
		$query->select('c.alias AS catalias, c.title AS cattitle');
		$query->join('LEFT', $db->quoteName('#__categories') . ' AS c ON c.id = a.catid');
		
		$query->where('a.access IN ('.$groups.')');
		$query->where('a.catid IN ('.$cat_ids.')');
				
		$query->where('a.published = 1');
		
		switch($sort_method) {
			case 'popular':
				$sort_method = "hits DESC";
				break;
			case 'oldest':
				$sort_method = "creation_date ASC";
				break;
			case 'random':
				$sort_method= "RAND()";
				break;
			case 'newest':
			default:
				$sort_method= "creation_date DESC";
				break;
		}
			
		$query->order($sort_method);
		
		$db->setQuery($query, 0, $cLimit);
		$rows = $db->loadObjectList();
		
		if(count($rows) < $cLimit) {
			$cLimit = count($rows);
		}
		
		$fp = array(13, 14, 16, 30, 23, 24, 26, 31);
		$baseurl = JURI::root(). $cfgParams->get('movie_path');
		
		// Convert the params field into an object, saving original in _params
		for ($i = 0; $i < $cLimit; $i++) {
			$items[$i] = $rows[$i];
			$cid = $items[$i]->catid;			
			
			if(isset($itemids[$cid]['itemId']) && $itemids[$cid]['itemId'] != '') {
				$items[$i]->itemId = $itemids[$cid]['itemId'];
			} else {
				$items[$i]->itemId = $itemids[0]['itemId'];
			}
			
			$items[$i]->catslug = $items[$i]->catid . ':' . $items[$i]->catalias;
			$items[$i]->slug = $items[$i]->id . ':' . $items[$i]->alias;

			$usr = JFactory::getUser($items[$i]->user_id);
			$items[$i]->submitter = $usr->username;			
		}
		
		unset($rows);
		$rows = $this->_rows = $items;
		
		return $rows;
	}
	
	public function loadYoutubeTracking() {
		$document = JFactory::getDocument();
		$document->addScript(JURI::base().'/components/com_xmovie/js/video.tracking.youtube.min.js');
	}
}
?>