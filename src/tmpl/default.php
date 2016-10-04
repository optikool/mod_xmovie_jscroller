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

$scroller_id = $params->get('scroller_id');
$moduleclass_sfx = $params->get('moduleclass_sfx');

$listCount = 0;
$numberOfItems = count($lists);
$isActiveItem = true;

if(count($numberOfItems) > 0) {
?>
<div id="<?php echo $scroller_id; ?>" class="carousel slide <?php echo $moduleclass_sfx; ?>">
	<ol class="carousel-indicators">
	<?php for($i = 0; $i < $numberOfItems; $i++) { ?>
		<?php if($i == 0) { ?>
    	<li data-target="#<?php echo $scroller_id; ?>" data-slide-to="<?php echo $i; ?>" class="active"></li>
    	<?php } else { ?>
    	<li data-target="#<?php echo $scroller_id; ?>" data-slide-to="<?php echo $i; ?>"></li>	
    	<?php } ?>
    <?php } ?>
    </ol>
    <!-- Carousel items -->
    <div class="carousel-inner">
    <?php foreach($lists as $item) {
    	$link = JRoute::_("index.php?option=com_xmovie&view=single&catid={$item->catid}:{$item->catalias}&id={$item->id}:{$item->alias}&Itemid={$item->itemId}");
		
		$imageThumb = parse_url($item->thumb);
				
		if(!isset($imageThumb['host']) || $imageThumb['host'] == '') {
			$movString = JURI::base(true) . "/components/com_xmovie/helpers/img.php?file=".urlencode($item->thumb)."&t=m";
		} else {
			$movString = $item->thumb;										
		}
					
    	if($isActiveItem) { ?>
    	<div class="active item">
    	<?php 
			$isActiveItem = false;
		} else { ?>
    	<div class="item">
    	<?php } ?>
	    	<a href="<?php echo $link ;?>">
				<img class="thumbnail img-responsive" src="<?php echo $movString; ?>" alt="<?php echo htmlspecialchars($item->title); ?>" />
			</a>
			<?php if($params->get('show_movie_name') || $params->get('show_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_quicktake') || $params->get('show_submitter')) { ?>
			<div class="carousel-caption">
				<?php if($params->get('show_movie_name')) { ?>
				<h4><?php echo $item->title; ?></h4>
				<?php } ?>
				<?php if($params->get('show_date') || $params->get('show_hits') || $params->get('show_category') || $params->get('show_submitter')) { ?>
				<ul class="inline">
					<?php if($params->get('show_category')) { ?>
					<li><strong><?php echo JTEXT::_('MOD_XMOVIE_JSCROLLER_CATEGORY'); ?>:</strong> <?php echo $item->cattitle; ?></li>	
					<?php } ?>
					<?php if($params->get('show_submitter')) { ?>
					<li><strong><?php echo JTEXT::_('MOD_XMOVIE_JSCROLLER_SUBMITTER'); ?>:</strong> <?php echo $item->submitter; ?></li>	
					<?php } ?>
					<?php if($params->get('show_hits')) { ?>
					<li><strong><?php echo JTEXT::_('MOD_XMOVIE_JSCROLLER_HITS'); ?>:</strong> <?php echo $item->hits; ?></li>	
					<?php } ?>
					<?php if($params->get('show_date')) { ?>
					<li><strong><?php echo JTEXT::_('MOD_XMOVIE_JSCROLLER_DATE_ADDED'); ?>:</strong> <?php echo JHTML::Date($item->creation_date, 'm-d-Y'); ?></li>	
					<?php } ?>
				</ul>	
				<?php } ?>
				<?php if($params->get('show_quicktake')) { ?>
				<p><?php echo $item->introtext; ?></p>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
    <?php } ?>
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" href="#<?php echo $scroller_id; ?>" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" href="#<?php echo $scroller_id; ?>" data-slide="next">&rsaquo;</a>
</div>
<?php } ?>







<?php /*
<div id="xmjscrol" class="xmovie-scr-horz<?php echo $moduleclass_sfx; ?>">
 	<div class="<?php echo $scrollName; ?> scrollable">  
    	<div class="slides_container">  
        	<?php foreach($lists as $item) {
				if($currId->id == '') {
					$fceItemid = $item->itemid->id;
				} else {
					$fceItemid = $currId->id;
				}
        		
				$link = JRoute::_("index.php?option=com_xmovie&view=single&catid={$item->catslug}&id={$item->slug}&Itemid={$item->itemId}");
				
        		$imageThumb = parse_url($item->thumb);
				
				if(!isset($imageThumb['host']) || $imageThumb['host'] == '') {
					$movString = JURI::base(true) . "/components/com_xmovie/helpers/img.php?file=".urlencode($item->thumb)."&t=m";
				} else {
					$movString = $item->thumb;										
				}
				
				if($item->isfp) {
					$isfp = 'fp';
				} else {
					$isfp = '';
				}
				
				$p = simplexml_load_string($item->quicktake);
				$introtext = '';
				$maxLength = 15;
				
				if(is_bool($p)){
					if($item->quicktake == '') {
						$introtext = $item->description;
					} else {
						$introtext = $item->quicktake;
					}
				} else {
					$introtext = $p[0];
				}
				if($maxLength > 0) {
					// explode the text into an array of words
			    	$wordArray = explode(' ', $introtext);
					
				    // do we have too many?
				    if( sizeof($wordArray) > $maxLength ) {
				       	// remove the unwanted words
				       	$wordArray = array_slice($wordArray, 0, $maxLength);
				
				       	// turn the word array back into a string and add our ...
				       	$introtext = implode(' ', $wordArray) . '&hellip;';
				    } else {
						$introtext = implode(' ', $wordArray);
					}
				}
				?>
			<?php if($lCountS == 1) : ?>
        	<div>
        	<?php endif; ?>	
        	<div class="scr-horiz-container scr-item"> 		
 				<div class="xmovie-scr-image">
 					<div class="image-item-inner">
	 					<a class="img-thumb" data-mov-date="<?php echo JHTML::Date($item->creation_date, 'm-d-Y'); ?>" data-mov-submitter="<?php echo $item->submitter;?>" data-mov-hits="<?php echo $item->hits;?>" data-mov-id="<?php echo $item->id; ?>" data-mov-type= "<?php echo $isfp; ?>" href="<?php echo $link ;?>">
							<img src="<?php echo $movString; ?>" alt="<?php echo htmlspecialchars($item->title); ?>" />
						</a> 
					</div>				
 				</div>
 				<?php if($params->get('show_movie_name')) { ?>
 				<div class="xmovie-scr-title"><small><?php echo htmlspecialchars($item->title); ?></small></div>
 				<?php } ?>
 			
 				<?php if($params->get('show_category')) { ?>
 				<div class="xmovie-scr-category"><small><?php echo htmlspecialchars($item->cattitle); ?></small></div>
 				<?php } ?>
 			
 				<?php if($params->get('show_date')) { ?>
 				<div class="xmovie-scr-date"><small><?php echo JHTML::Date($item->creation_date, 'm-d-Y'); ?></small></div>
 				<?php } ?>
 				
 				<?php if($params->get('show_hits')) { ?>
 				<div class="xmovie-scr-hits"><small><?php echo JText::_('MOD_XMOVIE_JSCROLLER_HITS'); ?> <?php echo $item->hits; ?></small></div>
 				<?php } ?> 
 				
 				<div class="xmovie-scr-desc" style="display:none;"><p class="video-block-desc"><?php echo $introtext; ?></p></div>	
        	</div> 
 			
 			<?php if($lCountS == $params->get('number_to_view')) : ?>
 				<div style="clear:both;"><!-- clear --></div>
			</div>
			<?php endif; ?>
			<?php 
				$lCountS++;
				
				if($lCountS > $params->get('number_to_view')) {
					$lCountS = 1;
				}
			} ?> 		
    	</div> 
	</div>
	<div style="clear:both;"><!-- clear --></div>
</div>
*/ ?>