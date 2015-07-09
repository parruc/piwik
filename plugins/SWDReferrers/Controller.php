<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\SWDReferrers;

use Piwik\View;
//use Piwik\Plugins\Referrers\Reports\GetReferrerType;
use Piwik\ViewDataTable\Factory as ViewDataTableFactory;

/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class Controller extends \Piwik\Plugin\Controller
{

    public function getReferrersPieGraph()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build(
            'graphPie', 'Referrers.getReferrerType', $controllerAction=$controllerAction);

        $view->config->columns_to_display = array('nb_visits');
        $view->config->selectable_columns = array("nb_visits");
        $view->config->show_footer_icons = false;
        $view->config->show_series_picker = false;

        return $view->render();
    }

    public function getWebsitesSimplified()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build('table', "Referrers.getWebsites", $controllerAction=$controllerAction);
        $view->config->show_footer_icons = false;
        $view->requestConfig->flat = true;

        return $view->render();
    }
}
