<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\SWDUserCountry;

use Piwik\ViewDataTable\Factory as ViewDataTableFactory;
use Piwik\Piwik;
/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class Controller extends \Piwik\Plugin\Controller
{
    public function getSimplifiedCity()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build(
            'table', 'UserCountry.getCity', $controllerAction=$controllerAction);

        $view->config->show_footer_icons = false;
        $view->requestConfig->flat = true;
        return $view->render();
    }
}
