<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_sis_articles
 *
 * @copyright	Copyright © 2022 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @author 		Sergio Iglesias (@sergiois)
 */

namespace Joomla\Module\SISArticles\Site\Helper;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;
class SISArticlesHelper implements DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    /**
     * Get a list of the latest articles from the article model.
     *
     * @param   Registry         $params  Object holding the models parameters
     * @param   SiteApplication  $app     The app
     *
     * @return  mixed
     *
     * @since 4.2.0
     */
    public static function getArticles(Registry $params, SiteApplication $app)
    {
        $db = Factory::getContainer()->get('DatabaseDriver');
        $articles = array();
        foreach($params->get('articles') as $article)
        {
            $articles[] = $article->article;
        }

        $query = $db->getQuery(true);
        $query->select('a.*, c.title as category');
		$query->from('`#__content` AS a');
        $query->join('LEFT', '#__categories as c ON c.id = a.catid');
        $query->where('a.id IN ('.implode(',',$articles).')');
        $query->order('FIELD (a.id,'.implode(',',$articles).')');
        $db->setQuery($query);
		$items = $db->loadObjectList();

		return $items;
    }

    public static function getItems(&$params)
    {
        return (new self())->getArticles($params, Factory::getApplication());
    }
}