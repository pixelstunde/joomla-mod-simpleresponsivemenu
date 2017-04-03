<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * This is placed here to make sure you can
 * change or remove it easily using a simple
 * template override
 */



//ensure we have a working jquery
JHtml::_('jquery.framework');

if ("1" !== $params->get('disable-js-css')){
	$inlineStyle = '';
	$cur = $params->get('responsive-min-width');
	if (!empty($cur)){
		$inlineStyle.=
		'#responsive-menu-trigger{display:none;}'.
		'@media screen and (max-width:'.$cur.'){
			#responsive-menu-trigger{
				display:block;
			}
			#sidr-content{
				display:none !important;
			}
		}';
	}
}

$document = JFactory::getDocument();

$document->addStylesheet(JURI::base(true).'/modules/mod_simpleresponsivemenu/css/main.css');
$document->addScript(JURI::base(true).'/modules/mod_simpleresponsivemenu/js/script.js');
if (!empty($inlineStyle)) $document->addStyleDeclaration($inlineStyle);

$id = '';

if (($tagId = $params->get('tag_id', '')))
{
	$id = ' id="' . $tagId . '"';
}
// The menu class is deprecated. Use nav instead
?>

<div id="responsive-menu-trigger">
	<a href="#sidr" id="sidr"><span><em></em><em></em><em></em></span><span class="caption"><?= $params->get('responsiveMenuText')?></span></a>
</div>

<ul id="sidr-content" class="nav menu<?php echo $class_sfx; ?>"<?php echo $id; ?>>
	<?php foreach ($list as $i => &$item)
	{
		$class = 'item-' . $item->id;
	
		if ($item->id == $default_id)
		{
			$class .= ' default';
		}
	
	
		if (($item->id == $active_id) || ($item->type == 'alias' && $item->params->get('aliasoptions') == $active_id))
		{
			$class .= ' current';
		}
	
		if (in_array($item->id, $path))
		{
			$class .= ' active';
		}
		elseif ($item->type == 'alias')
		{
			$aliasToId = $item->params->get('aliasoptions');
	
			if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
			{
				$class .= ' active';
			}
			elseif (in_array($aliasToId, $path))
			{
				$class .= ' alias-parent-active';
			}
		}
	
		if ($item->type == 'separator')
		{
			$class .= ' divider';
		}
	
		if ($item->deeper)
		{
			$class .= ' deeper';
		}
	
		if ($item->parent)
		{
			$class .= ' parent';
		}
	
		echo '<li class="' . $class . '">';
	
		switch ($item->type) :
			case 'separator':
			case 'component':
			case 'heading':
			case 'url':
				require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
				break;
	
			default:
				require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
				break;
		endswitch;
	
		// The next item is deeper.
		if ($item->deeper)
		{
			echo '<ul class="nav-child unstyled small">';
		}
		// The next item is shallower.
		elseif ($item->shallower)
		{
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else
		{
			echo '</li>';
		}
	}
	?>
</ul>
