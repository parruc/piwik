<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\DashboardSWD;

use Piwik\Common;
use Piwik\FrontController;
use Piwik\Plugins\UsersManager\UserPreferences;
use Piwik\Translation\Translator;
use Piwik\View;

/**
 * A controller let's you for example create a page that can be added to a menu. For more information read our guide
 * http://developer.piwik.org/guides/mvc-in-piwik or have a look at the our API references for controller and view:
 * http://developer.piwik.org/api-reference/Piwik/Plugin/Controller and
 * http://developer.piwik.org/api-reference/Piwik/View
 */
class Controller extends \Piwik\Plugin\Controller
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;

        parent::__construct();
    }

    public function index()
    {
        $currentPeriod = Common::getRequestVar('period', false);
        $currentDate = Common::getRequestVar('date', false);
        $currentIdSite = Common::getRequestVar('idSite', false);

        if (empty($currentIdSite) || empty($currentPeriod) || empty($currentDate)) {
            $defaultParams = $this->getDefaultParams();
            return $this->redirectToIndex("DashboardSWD", 'index', $defaultParams['idSite'], $defaultParams['period'], $defaultParams['date'] );
        }

        $view = new View("@DashboardSWD/index.twig");

        $this->setGeneralVariablesView($view);

        $view->prettyDate = date("Y-m-d");

        $widgetsCols = $this->getWidgets();
        foreach ($widgetsCols as $col => $widgets) {

            $view->widgets[$col] = [];

            foreach ($widgets as $i => $widget) {

                $module = $widget['module'];
                $method = $widget['action'];
                $params = !empty($widget['params']) ? $widget['params'] : [];

                $view->widgets[$col][$i]['title'] = $widget['title'];
                $view->widgets[$col][$i]['content'] = FrontController::getInstance()->dispatch($module, $method, $params);
            }
        }
        return $view->render();
    }

    protected function getWidgets()
    {
        $widgets = [
            [
                [
                    "title" => "Visite",
                    "module" => "SWDVisitsSummary",
                    "action" => "index",
                ],
            ],
            [
                [
                    "title" => "Sorgenti di traffico",
                    "module" => "SWDReferrers",
                    "action" => "getReferrersPieGraph",
                ],
                [
                    "title" => "Pagine viste",
                    "module" => "SWDActions",
                    "action" => "getSimplifiedPageTitles",
                ],
                 [
                    "title" => "Provenienza geografica delle visite",
                    "module" => "UserCountryMap",
                    "action" => "visitorMap",
                ],
            ],
            [
                [
                    "title" => "Dettaglio sorgenti di traffico",
                    "module" => "SWDReferrers",
                    "action" => "getWebsitesSimplified",
                ],
                [
                    "title" => "Dettaglio provenienza geografica delle visite",
                    "module" => "SWDUserCountry",
                    "action" => "getSimplifiedCity",
                ],
            ],
        ];

        return $widgets;
    }

    protected function getDefaultParams()
    {
        $userPreferences = new UserPreferences();
        if (empty($websiteId)) {
            $websiteId = $userPreferences->getDefaultWebsiteId();
        }
        if (empty($websiteId)) {
            throw new \Exception("A website ID was not specified and a website to default to could not be found.");
        }
        if (empty($defaultDate)) {
            $defaultDate = $userPreferences->getDefaultDate();
        }
        if (empty($defaultPeriod)) {
            $defaultPeriod = $userPreferences->getDefaultPeriod();
        }

        return array(
            'idSite' => $websiteId,
            'period' => $defaultPeriod,
            'date'   => $defaultDate,
        );
    }
}
