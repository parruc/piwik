<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\SWDVisitsSummary;

use Piwik\View;
use Piwik\Piwik;
use Piwik\Common;

/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class Controller extends \Piwik\Plugins\VisitsSummary\Controller
{

    public function index()
    {
        $view = new View('@SWDVisitsSummary/index');
        $this->setPeriodVariablesView($view);
        $view->graphEvolutionVisitsSummary = $this->getEvolutionGraph(array('nb_visits', 'nb_uniq_visitors'), array('nb_visits', 'nb_uniq_visitors'));
        $this->setSparklinesAndNumbers($view);
        return $view->render();
    }

    public function getEvolutionGraph(array $columns = array(), array $defaultColumns = array(), $callingAction = __FUNCTION__)
    {
        if (empty($columns)) {
            $columns = Common::getRequestVar('columns', false);
            if (false !== $columns) {
                $columns = Piwik::getArrayFromApiParameter($columns);
            }
        }

        $selectableColumns = array(
            // columns from VisitsSummary.get
            'nb_visits',
            'nb_uniq_visitors'
        );

        $idSite = Common::getRequestVar('idSite');

        $view = $this->getLastUnitGraphAcrossPlugins($this->pluginName, __FUNCTION__, $columns,
            $selectableColumns, "");

        if (empty($view->config->columns_to_display) && !empty($defaultColumns)) {
            $view->config->columns_to_display = $defaultColumns;
        }
        $view->config->show_series_picker = false;
        $view->config->show_footer_icons = false;

        return $this->renderView($view);
    }

    protected function setSparklinesAndNumbers($view)
    {
        parent::setSparklinesAndNumbers($view);
        $view->urlSparklineNbVisits = $this->getUrlSparkline('getEvolutionGraph', array('columns' =>  array('nb_visits', 'nb_uniq_visitors')));
    }
}
