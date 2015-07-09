<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\SWDActions;

use Piwik\ViewDataTable\Factory as ViewDataTableFactory;
use Piwik\Piwik;
//use Piwik\View;

/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class Controller extends \Piwik\Plugin\Controller
{
    public function getSimplifiedPageTitles()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build(
            'table', 'Actions.getPageTitles', $controllerAction=$controllerAction);

        $view->config->columns_to_display = array('label', 'nb_hits');
        $view->config->show_footer_icons = false;
        $view->config->related_reports = array();
        $view->requestConfig->flat = true;
        /*$view->config->addRelatedReports(array('SWDActions.getSimplifiedPageTitles' => Piwik::translate('Actions_PageTitles'),
                                               'SWDActions.getSimplifiedEntryPageTitles' => Piwik::translate('Actions_EntryPageTitles'),
                                               'SWDActions.getSimplifiedExitPageTitles' => Piwik::translate('Actions_ExitPageTitles')
                                               )
                                        );
*/
        return $view->render();
    }
    public function getSimplifiedEntryPageTitles()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build(
            'table', 'Actions.getEntryPageTitles', $controllerAction=$controllerAction);

        $view->config->columns_to_display = array('label', 'nb_hits');
        $view->config->show_footer_icons = false;
        $view->config->related_reports = array();
        /*$view->config->addRelatedReports(array('SWDActions.getSimplifiedPageTitles' => Piwik::translate('Actions_PageTitles'),
                                               'SWDActions.getSimplifiedEntryPageTitles' => Piwik::translate('Actions_EntryPageTitles'),
                                               'SWDActions.getSimplifiedExitPageTitles' => Piwik::translate('Actions_ExitPageTitles')
                                               )
                                        );
        */return $view->render();
    }
    public function getSimplifiedExitPageTitles()
    {
        $controllerAction = $this->pluginName . '.' . __FUNCTION__;
        $view = ViewDataTableFactory::build(
            'table', 'Actions.getExitPageTitles', $controllerAction=$controllerAction);

        $view->config->columns_to_display = array('label', 'nb_hits');
        $view->config->show_footer_icons = false;
        $view->config->related_reports = array();
        /*$view->config->addRelatedReports(array('SWDActions.getSimplifiedPageTitles' => Piwik::translate('Actions_PageTitles'),
                                               'SWDActions.getSimplifiedEntryPageTitles' => Piwik::translate('Actions_EntryPageTitles'),
                                               'SWDActions.getSimplifiedExitPageTitles' => Piwik::translate('Actions_ExitPageTitles')
                                               )
                                        );
        */return $view->render();
    }
}
