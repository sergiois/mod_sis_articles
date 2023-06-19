<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_sis_articles
 *
 * @copyright	Copyright © 2023 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @author 		Sergio Iglesias (@sergiois)
 */

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\SISArticles\Site\Helper\SISArticlesHelper;

defined('_JEXEC') or die;

if(!count((array)$params->get('articles')))
{
	return;
}

$items = SISArticlesHelper::getItems($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$layout = $params->get('layout', 'default');

require ModuleHelper::getLayoutPath('mod_sis_articles', $layout);