<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_sis_articles
 *
 * @copyright	Copyright © 2023 - All rights reserved.
 * @license		GNU General Public License v2.0
 * @author 		Sergio Iglesias (@sergiois)
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;

defined('_JEXEC') or die;

$spanmd = 4;
switch($params->get('count'))
{
    case 1: $spanmd = 12; break;
    case 2: $spanmd = 6; break;
    case 3: $spanmd = 4; break;
    case 4: $spanmd = 3; break;
    case 6: $spanmd = 2; break;
}
?>
<div class="row <?php echo $moduleclass_sfx; ?>">
<?php foreach ($items as $item) : ?>
    <?php $item->link = RouteHelper::getArticleRoute($item->id.':'.$item->alias, $item->catid); ?>
    <div class="col-xs-12 col-sm-6 col-md-<?php echo $spanmd; ?> col-lg-<?php echo $spanmd; ?>" itemscope itemtype="https://schema.org/Article">
        <div class="card">
            <?php if($params->get('show_image') != 'off'): ?>
                <?php
                $images = json_decode($item->images);
                $image = $images->image_intro;
                $alt = $images->image_intro_alt ? $images->image_intro_alt : $item->title;
                if($params->get('show_image') == 'fulltext')
                {
                    $image = $images->image_fulltext;
                    $alt = $images->image_fulltext_alt ? $images->image_fulltext_alt : $item->title;
                }
                $layoutAttr = [
                    'src'      => $image,
                    'itemprop' => 'image',
                    'class'    => 'card-img-top',
                    'alt'      => $alt,
                ];
                ?>
                <?php if($params->get('link_image')): ?>
                    <a href="<?php echo $item->link; ?>" itemprop="url" target="<?php echo $params->get('open_link', '_self'); ?>">
                        <?php echo LayoutHelper::render('joomla.html.image', $layoutAttr); ?>
                    </a>
                <?php else: ?>
                    <?php echo LayoutHelper::render('joomla.html.image', $layoutAttr); ?>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if($params->get('show_title') || $params->get('show_content') != 'offc' || $params->get('show_readmore')): ?>
            <div class="card-body">
                <?php if($params->get('show_title')): ?>
                    <h3 class="card-title" itemprop="name">
                        <?php if($params->get('link_title')): ?>
                            <a href="<?php echo $item->link; ?>" itemprop="url" target="<?php echo $params->get('open_link', '_self'); ?>">
                        <?php endif; ?>
                        <?php echo $item->title; ?>
                        <?php if($params->get('link_title')): ?>
                            </a>
                        <?php endif; ?>
                    </h3>
                <?php endif; ?>

                <?php if($params->get('show_category')): ?>
                    <small class="<?php echo $params->get('design_category'); ?>">
                        <?php echo $item->category_title; ?>
                    </small>
                <?php endif; ?>

                <?php if($params->get('show_date')): ?>
                    <small class="<?php echo $params->get('design_date'); ?>">
                    <?php
                        $dateformat = 'DATE_FORMAT_LC';
                        switch((int)$params->get('date_format')){
                            case 0: $dateformat = 'DATE_FORMAT_LC'; break;
                            case 1: $dateformat = 'DATE_FORMAT_LC1'; break;
                            case 2: $dateformat = 'DATE_FORMAT_LC2'; break;
                            case 3: $dateformat = 'DATE_FORMAT_LC3'; break;
                            case 4: $dateformat = 'DATE_FORMAT_LC4'; break;
                            case 5: $dateformat = 'DATE_FORMAT_LC5'; break;
                            case 6: $dateformat = $params->get('date_custom_format'); break;
                            default: $dateformat = 'DATE_FORMAT_LC';
                        }
                        echo HTMLHelper::_('date', $item->publish_up, Text::_($dateformat));
                    ?>
                    </small>
                <?php endif; ?>

                <?php
                if($params->get('show_content') != 'offc')
                {
                    if($params->get('show_content') == 'partc')
                    {
                        $cleanText = filter_var($item->introtext, FILTER_SANITIZE_STRING);
                        $introCleanText = strip_tags($cleanText);
                        if (strlen($introCleanText) > $params->get('tam_content', 200))
                        {
                            $introtext = substr($introCleanText,0,strrpos(substr($introCleanText,0,$params->get('tam_content', 200))," ")).'...';
                        }
                        else
                        {
                            $introtext = $introCleanText;
                        }
                        echo '<p>'.$introtext.'</p>';
                    }
                    else
                    {
                        echo $item->introtext;
                    }
                }
                ?>
                
                <?php if($params->get('show_readmore')): ?>
                <p class="mb-0">
                    <a href="<?php echo $item->link; ?>" class="btn btn-primary" target="<?php echo $params->get('open_link', '_self'); ?>"><?php echo $params->get('readmore_text') ? $params->get('readmore_text') : Text::_('MOD_SIS_ARTICLES_FIELD_READMORE_TEXT'); ?></a>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
	</div>
<?php endforeach; ?>
</div>