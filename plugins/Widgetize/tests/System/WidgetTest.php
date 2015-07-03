<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\Widgetize\tests\Integration;

use Piwik\Container\StaticContainer;
use Piwik\Http\ControllerResolver;
use Piwik\Piwik;
use Piwik\Plugins\Goals;
use Piwik\Plugins\Widgetize\tests\Fixtures\WidgetizeFixture;
use Piwik\Tests\Framework\TestCase\SystemTestCase;
use Piwik\Widget\WidgetsList;

/**
 * @group Widgetize
 * @group WidgetTest
 * @group Plugins
 */
class WidgetTest extends SystemTestCase
{
    /**
     * @var WidgetizeFixture
     */
    public static $fixture = null; // initialized below class definition

    public function setUp()
    {
        parent::setUp();

        $_GET = array();
        $_GET['idSite'] = self::$fixture->idSite;
        $_GET['period'] = 'year';
        $_GET['date']   = 'today';
    }

    public function tearDown()
    {
        $_GET = array();
        parent::tearDown();
    }

    public function test_allWidgetUniqueIdsAreActuallyUnique()
    {
        $uniqueIds = array();
        foreach (WidgetsList::get()->getWidgetConfigs() as $widget) {
            $uniqueIds[] = $widget->getUniqueId();
        }

        $this->assertEquals(array_unique($uniqueIds), $uniqueIds);
    }

    public function test_AvailableWidgetListIsUpToDate()
    {
        $namesOfWidgetsThatAreAPI = array_map(function ($widget) {
            return $widget['uniqueId'];
        }, $this->getWidgetsThatAreAPICurrently());

        Piwik::postEvent('Platform.initialized'); // userCountryMap defines it's Widgets via this event currently

        $currentUniqueIds = array();
        foreach (WidgetsList::get()->getWidgetConfigs() as $widget) {
            $currentUniqueIds[] = $widget->getUniqueId();
        }

        $allWidgetNames = array_merge($namesOfWidgetsThatAreAPI, $currentUniqueIds);
        $regressedWidgetNames = array_diff($allWidgetNames, $currentUniqueIds);

        $this->assertEmpty($regressedWidgetNames, 'The widgets list is no longer up to date. If you added or changed a widget please update `getWidgetsThatAreAPICurrently()`, if you removed a widget please add it to `getWidgetsThatAreDeprecatedButStillAPI()`. If the uniqueId changed you might need to create an update for Dashboards and Scheduled Reports! Different names: ' . var_export($regressedWidgetNames, 1));
    }

    /**
     * @param array $widget
     *
     * @dataProvider availableWidgetsProvider
     */
    public function test_WidgetIsRenderable_ToPreventBreakingTheAPI($widget)
    {
        $params     = $widget['parameters'];
        $parameters = array();

        /** @var ControllerResolver $resolver */
        $resolver   = StaticContainer::get('Piwik\Http\ControllerResolver');
        $controller = $resolver->getController($params['module'], $params['action'], $parameters);

        $this->assertNotEmpty($controller, $widget['name'] . ' is not renderable with following params: ' . json_encode($params) . '. This breaks the API, please make sure to keep the URL working');
    }

    public function availableWidgetsProvider()
    {
        $data = array();

        foreach ($this->getWidgetsThatAreAPICurrently() as $widget) {
            if (!empty($widget)) {
                $data[] = array($widget);
            }
        }

        foreach ($this->getWidgetsThatAreDeprecatedButStillAPI() as $widget) {
            if (!empty($widget)) {
                $data[] = array($widget);
            }
        }

        return $data;
    }

    public function getWidgetsThatAreAPICurrently()
    {
        return array(
            array (
                'name' => '',
                'uniqueId' => 'widgetDashboardembeddedIndexidDashboard1',
                'parameters' =>
                    array (
                        'module' => 'Dashboard',
                        'action' => 'embeddedIndex',
                        'idDashboard' => 1,
                    ),
            ),array (
                'name' => '',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdProducts',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Products',
                    ),
            ),array (
                'name' => '',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdEvents',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Events',
                    ),
            ),array (
                'name' => 'Visits Overview (with graph)',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdVisitOverviewWithGraph',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'VisitOverviewWithGraph',
                    ),
            ),array (
                'name' => '',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdContents',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Contents',
                    ),
            ),array (
                'name' => 'Support Piwik!',
                'uniqueId' => 'widgetCoreHomegetDonateForm',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'getDonateForm',
                    ),
            ),array (
                'name' => 'Welcome!',
                'uniqueId' => 'widgetCoreHomegetPromoVideo',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'getPromoVideo',
                    ),
            ),array (
                'name' => 'Example Widget Name',
                'uniqueId' => 'widgetExamplePluginmyExampleWidget',
                'parameters' =>
                    array (
                        'module' => 'ExamplePlugin',
                        'action' => 'myExampleWidget',
                    ),
            ),array (
                'name' => 'Top Keywords for Page URL',
                'uniqueId' => 'widgetReferrersgetKeywordsForPage',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getKeywordsForPage',
                    ),
            ),array (
                'name' => 'Manage Goals',
                'uniqueId' => 'widgetGoalseditGoals',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'editGoals',
                    ),
            ),array (
                'name' => 'Ecommerce Log',
                'uniqueId' => 'widgetEcommercegetEcommerceLog',
                'parameters' =>
                    array (
                        'module' => 'Ecommerce',
                        'action' => 'getEcommerceLog',
                    ),
            ),array (
                'name' => 'SEO Rankings',
                'uniqueId' => 'widgetSEOgetRank',
                'parameters' =>
                    array (
                        'module' => 'SEO',
                        'action' => 'getRank',
                    ),
            ),array (
                'name' => 'Piwik Changelog',
                'uniqueId' => 'widgetExampleRssWidgetrssChangelog',
                'parameters' =>
                    array (
                        'module' => 'ExampleRssWidget',
                        'action' => 'rssChangelog',
                    ),
            ),array (
                'name' => 'Piwik.org Blog',
                'uniqueId' => 'widgetExampleRssWidgetrssPiwik',
                'parameters' =>
                    array (
                        'module' => 'ExampleRssWidget',
                        'action' => 'rssPiwik',
                    ),
            ),array (
                'name' => 'Real-time Map',
                'uniqueId' => 'widgetUserCountryMaprealtimeMap',
                'parameters' =>
                    array (
                        'module' => 'UserCountryMap',
                        'action' => 'realtimeMap',
                    ),
            ),array (
                'name' => 'Visitor Map',
                'uniqueId' => 'widgetUserCountryMapvisitorMap',
                'parameters' =>
                    array (
                        'module' => 'UserCountryMap',
                        'action' => 'visitorMap',
                    ),
            ),array (
                'name' => 'Visitor profile',
                'uniqueId' => 'widgetLivegetVisitorProfilePopup',
                'parameters' =>
                    array (
                        'module' => 'Live',
                        'action' => 'getVisitorProfilePopup',
                    ),
            ),array (
                'name' => 'Visitors in Real-time',
                'uniqueId' => 'widgetLivewidget',
                'parameters' =>
                    array (
                        'module' => 'Live',
                        'action' => 'widget',
                    ),
            ),array (
                'name' => 'Insights Overview',
                'uniqueId' => 'widgetInsightsgetInsightsOverview',
                'parameters' =>
                    array (
                        'module' => 'Insights',
                        'action' => 'getInsightsOverview',
                    ),
            ),array (
                'name' => 'Movers and Shakers',
                'uniqueId' => 'widgetInsightsgetOverallMoversAndShakers',
                'parameters' =>
                    array (
                        'module' => 'Insights',
                        'action' => 'getOverallMoversAndShakers',
                    ),
            ),array (
                'name' => 'Real Time Visitor Count',
                'uniqueId' => 'widgetLivegetSimpleLastVisitCount',
                'parameters' =>
                    array (
                        'module' => 'Live',
                        'action' => 'getSimpleLastVisitCount',
                    ),
            ),array (
                'name' => 'Visits Over Time',
                'uniqueId' => 'widgetVisitsSummarygetEvolutionGraphforceView1viewDataTablegraphEvolution',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'graphEvolution',
                        'module' => 'VisitsSummary',
                        'action' => 'getEvolutionGraph',
                    ),
            ),array (
                'name' => 'Visits Overview',
                'uniqueId' => 'widgetVisitsSummarygetforceView1viewDataTablesparklines',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'sparklines',
                        'module' => 'VisitsSummary',
                        'action' => 'get',
                    ),
            ),array (
                'name' => 'Visitor Log',
                'uniqueId' => 'widgetLivegetLastVisitsDetailsforceView1viewDataTablePiwik%5CPlugins%5CLive%5CVisitorLogsmall1',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'Piwik\\Plugins\\Live\\VisitorLog',
                        'module' => 'Live',
                        'action' => 'getLastVisitsDetails',
                        'small' => 1,
                    ),
            ),array (
                'name' => 'Custom Variables',
                'uniqueId' => 'widgetCustomVariablesgetCustomVariables',
                'parameters' =>
                    array (
                        'module' => 'CustomVariables',
                        'action' => 'getCustomVariables',
                    ),
            ),array (
                'name' => 'Device type',
                'uniqueId' => 'widgetDevicesDetectiongetType',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getType',
                    ),
            ),array (
                'name' => 'Device model',
                'uniqueId' => 'widgetDevicesDetectiongetModel',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getModel',
                    ),
            ),array (
                'name' => 'Device brand',
                'uniqueId' => 'widgetDevicesDetectiongetBrand',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getBrand',
                    ),
            ),array (
                'name' => 'Screen Resolution',
                'uniqueId' => 'widgetResolutiongetResolution',
                'parameters' =>
                    array (
                        'module' => 'Resolution',
                        'action' => 'getResolution',
                    ),
            ),array (
                'name' => 'Operating System versions',
                'uniqueId' => 'widgetDevicesDetectiongetOsVersions',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getOsVersions',
                    ),
            ),array (
                'name' => 'Browsers',
                'uniqueId' => 'widgetDevicesDetectiongetBrowsers',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getBrowsers',
                    ),
            ),array (
                'name' => 'Browser version',
                'uniqueId' => 'widgetDevicesDetectiongetBrowserVersions',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getBrowserVersions',
                    ),
            ),array (
                'name' => 'Configurations',
                'uniqueId' => 'widgetResolutiongetConfiguration',
                'parameters' =>
                    array (
                        'module' => 'Resolution',
                        'action' => 'getConfiguration',
                    ),
            ),array (
                'name' => 'Operating System families',
                'uniqueId' => 'widgetDevicesDetectiongetOsFamilies',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getOsFamilies',
                    ),
            ),array (
                'name' => 'Browser engines',
                'uniqueId' => 'widgetDevicesDetectiongetBrowserEnginesviewDataTablegraphPie',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphPie',
                        'module' => 'DevicesDetection',
                        'action' => 'getBrowserEngines',
                    ),
            ),array (
                'name' => 'Browser Plugins',
                'uniqueId' => 'widgetDevicePluginsgetPlugin',
                'parameters' =>
                    array (
                        'module' => 'DevicePlugins',
                        'action' => 'getPlugin',
                    ),
            ),array (
                'name' => 'Country',
                'uniqueId' => 'widgetUserCountrygetCountry',
                'parameters' =>
                    array (
                        'module' => 'UserCountry',
                        'action' => 'getCountry',
                    ),
            ),array (
                'name' => '',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdContinent',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Continent',
                    ),
            ),array (
                'name' => 'Region',
                'uniqueId' => 'widgetUserCountrygetRegion',
                'parameters' =>
                    array (
                        'module' => 'UserCountry',
                        'action' => 'getRegion',
                    ),
            ),array (
                'name' => 'Browser language',
                'uniqueId' => 'widgetUserLanguagegetLanguage',
                'parameters' =>
                    array (
                        'module' => 'UserLanguage',
                        'action' => 'getLanguage',
                    ),
            ),array (
                'name' => 'City',
                'uniqueId' => 'widgetUserCountrygetCity',
                'parameters' =>
                    array (
                        'module' => 'UserCountry',
                        'action' => 'getCity',
                    ),
            ),array (
                'name' => 'Language code',
                'uniqueId' => 'widgetUserLanguagegetLanguageCode',
                'parameters' =>
                    array (
                        'module' => 'UserLanguage',
                        'action' => 'getLanguageCode',
                    ),
            ),array (
                'name' => 'Providers',
                'uniqueId' => 'widgetProvidergetProvider',
                'parameters' =>
                    array (
                        'module' => 'Provider',
                        'action' => 'getProvider',
                    ),
            ),array (
                'name' => 'Visits per visit duration',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsPerVisitDurationviewDataTablecloud',
                'parameters' =>
                    array (
                        'viewDataTable' => 'cloud',
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsPerVisitDuration',
                    ),
            ),array (
                'name' => 'Visits per number of pages',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsPerPageviewDataTablecloud',
                'parameters' =>
                    array (
                        'viewDataTable' => 'cloud',
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsPerPage',
                    ),
            ),array (
                'name' => 'Visits by Visit Number',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsByVisitCount',
                'parameters' =>
                    array (
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsByVisitCount',
                    ),
            ),array (
                'name' => 'Visits by Days Since Last Visit',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsByDaysSinceLast',
                'parameters' =>
                    array (
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsByDaysSinceLast',
                    ),
            ),array (
                'name' => 'Returning Visits Over Time',
                'uniqueId' => 'widgetVisitFrequencygetEvolutionGraphforceView1viewDataTablegraphEvolution',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'graphEvolution',
                        'module' => 'VisitFrequency',
                        'action' => 'getEvolutionGraph',
                    ),
            ),array (
                'name' => 'Frequency Overview',
                'uniqueId' => 'widgetVisitFrequencygetforceView1viewDataTablesparklines',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'sparklines',
                        'module' => 'VisitFrequency',
                        'action' => 'get',
                    ),
            ),array (
                'name' => 'Visits per local time',
                'uniqueId' => 'widgetVisitTimegetVisitInformationPerLocalTimeviewDataTablegraphVerticalBar',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphVerticalBar',
                        'module' => 'VisitTime',
                        'action' => 'getVisitInformationPerLocalTime',
                    ),
            ),array (
                'name' => 'Visits per server time',
                'uniqueId' => 'widgetVisitTimegetVisitInformationPerServerTimeviewDataTablegraphVerticalBar',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphVerticalBar',
                        'module' => 'VisitTime',
                        'action' => 'getVisitInformationPerServerTime',
                    ),
            ),array (
                'name' => 'Visits by Day of Week',
                'uniqueId' => 'widgetVisitTimegetByDayOfWeekviewDataTablegraphVerticalBar',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphVerticalBar',
                        'module' => 'VisitTime',
                        'action' => 'getByDayOfWeek',
                    ),
            ),array (
                'name' => 'Pages',
                'uniqueId' => 'widgetActionsgetPageUrls',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getPageUrls',
                    ),
            ),array (
                'name' => 'Entry pages',
                'uniqueId' => 'widgetActionsgetEntryPageUrls',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getEntryPageUrls',
                    ),
            ),array (
                'name' => 'Exit pages',
                'uniqueId' => 'widgetActionsgetExitPageUrls',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getExitPageUrls',
                    ),
            ),array (
                'name' => 'Page titles',
                'uniqueId' => 'widgetActionsgetPageTitles',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getPageTitles',
                    ),
            ),array (
                'name' => 'Site Search Keywords',
                'uniqueId' => 'widgetActionsgetSiteSearchKeywords',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getSiteSearchKeywords',
                    ),
            ),array (
                'name' => 'Pages Following a Site Search',
                'uniqueId' => 'widgetActionsgetPageUrlsFollowingSiteSearch',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getPageUrlsFollowingSiteSearch',
                    ),
            ),array (
                'name' => 'Search Keywords with No Results',
                'uniqueId' => 'widgetActionsgetSiteSearchNoResultKeywords',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getSiteSearchNoResultKeywords',
                    ),
            ),array (
                'name' => 'Page Titles Following a Site Search',
                'uniqueId' => 'widgetActionsgetPageTitlesFollowingSiteSearch',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getPageTitlesFollowingSiteSearch',
                    ),
            ),array (
                'name' => 'Search Categories',
                'uniqueId' => 'widgetActionsgetSiteSearchCategories',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getSiteSearchCategories',
                    ),
            ),array (
                'name' => 'Outlinks',
                'uniqueId' => 'widgetActionsgetOutlinks',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getOutlinks',
                    ),
            ),array (
                'name' => 'Downloads',
                'uniqueId' => 'widgetActionsgetDownloads',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getDownloads',
                    ),
            ),array (
                'name' => 'Entry Page Titles',
                'uniqueId' => 'widgetActionsgetEntryPageTitles',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getEntryPageTitles',
                    ),
            ),array (
                'name' => 'Exit page titles',
                'uniqueId' => 'widgetActionsgetExitPageTitles',
                'parameters' =>
                    array (
                        'module' => 'Actions',
                        'action' => 'getExitPageTitles',
                    ),
            ),array (
                'name' => 'Referrer Types',
                'uniqueId' => 'widgetReferrersgetReferrerTypeviewDataTabletableAllColumns',
                'parameters' =>
                    array (
                        'viewDataTable' => 'tableAllColumns',
                        'module' => 'Referrers',
                        'action' => 'getReferrerType',
                    ),
            ),array (
                'name' => 'Evolution over the period',
                'uniqueId' => 'widgetReferrersgetEvolutionGraphforceView1viewDataTablegraphEvolutioncolumnsArray',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'graphEvolution',
                        'module' => 'Referrers',
                        'action' => 'getEvolutionGraph',
                        'columns' =>
                            array (
                                0 => 'nb_visits',
                            ),
                    ),
            ),array (
                'name' => 'Referrer Type',
                'uniqueId' => 'widgetReferrersgetSparklinesforceView1viewDataTablesparklines',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'sparklines',
                        'module' => 'Referrers',
                        'action' => 'getSparklines',
                    ),
            ),array (
                'name' => 'Referrers',
                'uniqueId' => 'widgetReferrersgetAllviewDataTabletableAllColumns',
                'parameters' =>
                    array (
                        'viewDataTable' => 'tableAllColumns',
                        'module' => 'Referrers',
                        'action' => 'getAll',
                    ),
            ),array (
                'name' => 'Keywords',
                'uniqueId' => 'widgetReferrersgetKeywords',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getKeywords',
                    ),
            ),array (
                'name' => 'Search Engines',
                'uniqueId' => 'widgetReferrersgetSearchEngines',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getSearchEngines',
                    ),
            ),array (
                'name' => 'Websites',
                'uniqueId' => 'widgetReferrersgetWebsites',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getWebsites',
                    ),
            ),array (
                'name' => 'Social Networks',
                'uniqueId' => 'widgetReferrersgetSocialsviewDataTablegraphPie',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphPie',
                        'module' => 'Referrers',
                        'action' => 'getSocials',
                    ),
            ),array (
                'name' => 'Campaigns',
                'uniqueId' => 'widgetReferrersgetCampaigns',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getCampaigns',
                    ),
            ),array (
                'name' => 'Overview',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals_GoalsGeneral_Overview',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals_GoalsGeneral_Overview',
                    ),
            ),array (
                'name' => 'Overview',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals_EcommerceGeneral_Overview',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals_EcommerceGeneral_Overview',
                    ),
            ),array (
                'name' => '',
                'uniqueId' => 'widgetCoreHomerenderWidgetContaineridGoalecommerceOrdercontainerIdGoalsOrder',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'idGoal' => 'ecommerceOrder',
                        'containerId' => 'GoalsOrder',
                    ),
            ),array (
                'name' => 'Download Software',
                'uniqueId' => 'widgetCoreHomerenderWidgetContaineridGoal1containerIdGoals_Goals1',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'idGoal' => '1',
                        'containerId' => 'Goals_Goals1',
                    ),
            ),array (
                'name' => 'Goal Download Software conversions by type of visit',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals1',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals1',
                    ),
            ),array (
                'name' => 'Download Software2',
                'uniqueId' => 'widgetCoreHomerenderWidgetContaineridGoal2containerIdGoals_Goals2',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'idGoal' => '2',
                        'containerId' => 'Goals_Goals2',
                    ),
            ),array (
                'name' => 'Goal Download Software2 conversions by type of visit',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals2',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals2',
                    ),
            ),array (
                'name' => 'Opens Contact Form',
                'uniqueId' => 'widgetCoreHomerenderWidgetContaineridGoal3containerIdGoals_Goals3',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'idGoal' => '3',
                        'containerId' => 'Goals_Goals3',
                    ),
            ),array (
                'name' => 'Goal Opens Contact Form conversions by type of visit',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals3',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals3',
                    ),
            ),array (
                'name' => 'Visit Docs',
                'uniqueId' => 'widgetCoreHomerenderWidgetContaineridGoal4containerIdGoals_Goals4',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'idGoal' => '4',
                        'containerId' => 'Goals_Goals4',
                    ),
            ),array (
                'name' => 'Goal Visit Docs conversions by type of visit',
                'uniqueId' => 'widgetCoreHomerenderWidgetContainercontainerIdGoals4',
                'parameters' =>
                    array (
                        'module' => 'CoreHome',
                        'action' => 'renderWidgetContainer',
                        'containerId' => 'Goals4',
                    ),
            ),array (
                'name' => 'Data tables',
                'uniqueId' => 'widgetExampleUIgetTemperatures',
                'parameters' =>
                    array (
                        'module' => 'ExampleUI',
                        'action' => 'getTemperatures',
                    ),
            ),array (
                'name' => 'Data tables',
                'uniqueId' => 'widgetExampleUIgetTemperaturesforceView1viewDataTablegraphVerticalBar',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'graphVerticalBar',
                        'module' => 'ExampleUI',
                        'action' => 'getTemperatures',
                    ),
            ),array (
                'name' => 'Treemap example',
                'uniqueId' => 'widgetExampleUIgetTemperaturesforceView1viewDataTableinfoviz-treemap',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'infoviz-treemap',
                        'module' => 'ExampleUI',
                        'action' => 'getTemperatures',
                    ),
            ),array (
                'name' => 'Temperatures evolution over time',
                'uniqueId' => 'widgetExampleUIgetTemperaturesEvolutionforceView1viewDataTablesparklines',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'sparklines',
                        'module' => 'ExampleUI',
                        'action' => 'getTemperaturesEvolution',
                    ),
            ),array (
                'name' => 'Evolution of server temperatures over the last few days',
                'uniqueId' => 'widgetExampleUIgetTemperaturesEvolutionforceView1viewDataTablegraphEvolutioncolumnsArray',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'graphEvolution',
                        'module' => 'ExampleUI',
                        'action' => 'getTemperaturesEvolution',
                        'columns' =>
                            array (
                                0 => 'server1',
                                1 => 'server2',
                            ),
                    ),
            ),array (
                'name' => 'Pie graph',
                'uniqueId' => 'widgetExampleUIgetPlanetRatiosviewDataTablegraphPie',
                'parameters' =>
                    array (
                        'viewDataTable' => 'graphPie',
                        'module' => 'ExampleUI',
                        'action' => 'getPlanetRatios',
                    ),
            ),array (
                'name' => 'Simple tag cloud',
                'uniqueId' => 'widgetExampleUIgetPlanetRatiosforceView1viewDataTablecloud',
                'parameters' =>
                    array (
                        'forceView' => 1,
                        'viewDataTable' => 'cloud',
                        'module' => 'ExampleUI',
                        'action' => 'getPlanetRatios',
                    ),
            ),array (
                'name' => 'Advanced tag cloud: with logos and links',
                'uniqueId' => 'widgetExampleUIgetPlanetRatiosWithLogosviewDataTablecloud',
                'parameters' =>
                    array (
                        'viewDataTable' => 'cloud',
                        'module' => 'ExampleUI',
                        'action' => 'getPlanetRatiosWithLogos',
                    )
            )
        );
    }

    /**
     * This is a list of all widgets that we consider API. We need to make sure the widgets will be still renderable
     * etc.
     * @return array
     */
    public function getWidgetsThatAreDeprecatedButStillAPI()
    {
        return array(
            array (
                'name' => 'Visits per server time',
                'uniqueId' => 'widgetVisitTimegetVisitInformationPerServerTime',
                'parameters' =>
                    array (
                        'module' => 'VisitTime',
                        'action' => 'getVisitInformationPerServerTime',
                    ),
            ), array (
                'name' => 'Visits per local time',
                'uniqueId' => 'widgetVisitTimegetVisitInformationPerLocalTime',
                'parameters' =>
                    array (
                        'module' => 'VisitTime',
                        'action' => 'getVisitInformationPerLocalTime',
                    ),
            ), array (
                'name' => 'Visits by Day of Week',
                'uniqueId' => 'widgetVisitTimegetByDayOfWeek',
                'parameters' =>
                    array (
                        'module' => 'VisitTime',
                        'action' => 'getByDayOfWeek',
                    ),
            ), array (
                'name' => 'Visits Over Time',
                'uniqueId' => 'widgetVisitsSummarygetEvolutionGraphcolumnsArray',
                'parameters' =>
                    array (
                        'module' => 'VisitsSummary',
                        'action' => 'getEvolutionGraph',
                        'columns' =>
                            array (
                                0 => 'nb_visits',
                            ),
                    ),
            ), array (
                'name' => 'Visits Overview',
                'uniqueId' => 'widgetVisitsSummarygetSparklines',
                'parameters' =>
                    array (
                        'module' => 'VisitsSummary',
                        'action' => 'getSparklines',
                    ),
            ), array (
                'name' => 'Visits Overview (with graph)',
                'uniqueId' => 'widgetVisitsSummaryindex',
                'parameters' =>
                    array (
                        'module' => 'VisitsSummary',
                        'action' => 'index',
                    ),
            ), array (
                'name' => 'Visitor Log',
                'uniqueId' => 'widgetLivegetVisitorLogsmall1',
                'parameters' =>
                    array (
                        'module' => 'Live',
                        'action' => 'getVisitorLog',
                        'small' => 1,
                    ),
            ), array (
                'name' => 'Continent',
                'uniqueId' => 'widgetUserCountrygetContinent',
                'parameters' =>
                    array (
                        'module' => 'UserCountry',
                        'action' => 'getContinent',
                    ),
            ), array (
                'name' => 'Visits per visit duration',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsPerVisitDuration',
                'parameters' =>
                    array (
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsPerVisitDuration',
                    ),
            ), array (
                'name' => 'Pages per Visit',
                'uniqueId' => 'widgetVisitorInterestgetNumberOfVisitsPerPage',
                'parameters' =>
                    array (
                        'module' => 'VisitorInterest',
                        'action' => 'getNumberOfVisitsPerPage',
                    ),
            ), array (
                'name' => 'Frequency Overview',
                'uniqueId' => 'widgetVisitFrequencygetSparklines',
                'parameters' =>
                    array (
                        'module' => 'VisitFrequency',
                        'action' => 'getSparklines',
                    ),
            ), array (
                'name' => 'Returning Visits Over Time',
                'uniqueId' => 'widgetVisitFrequencygetEvolutionGraphcolumnsArray',
                'parameters' =>
                    array (
                        'module' => 'VisitFrequency',
                        'action' => 'getEvolutionGraph',
                        'columns' =>
                            array (
                                0 => 'nb_visits_returning',
                            ),
                    ),
            ), array (
                'name' => 'Browser engines',
                'uniqueId' => 'widgetDevicesDetectiongetBrowserEngines',
                'parameters' =>
                    array (
                        'module' => 'DevicesDetection',
                        'action' => 'getBrowserEngines',
                    ),
            ), array (
                'name' => 'Content Name',
                'uniqueId' => 'widgetContentsgetContentNames',
                'parameters' =>
                    array (
                        'module' => 'Contents',
                        'action' => 'getContentNames',
                    ),
            ), array (
                'name' => 'Content Piece',
                'uniqueId' => 'widgetContentsgetContentPieces',
                'parameters' =>
                    array (
                        'module' => 'Contents',
                        'action' => 'getContentPieces',
                    ),
            ), array (
                'name' => 'Event Categories',
                'uniqueId' => 'widgetEventsgetCategorysecondaryDimensioneventAction',
                'parameters' =>
                    array (
                        'module' => 'Events',
                        'action' => 'getCategory',
                        'secondaryDimension' => 'eventAction',
                    ),
            ), array (
                'name' => 'Event Actions',
                'uniqueId' => 'widgetEventsgetActionsecondaryDimensioneventName',
                'parameters' =>
                    array (
                        'module' => 'Events',
                        'action' => 'getAction',
                        'secondaryDimension' => 'eventName',
                    ),
            ), array (
                'name' => 'Event Names',
                'uniqueId' => 'widgetEventsgetNamesecondaryDimensioneventAction',
                'parameters' =>
                    array (
                        'module' => 'Events',
                        'action' => 'getName',
                        'secondaryDimension' => 'eventAction',
                    ),
            ), array (
                'name' => 'Overview',
                'uniqueId' => 'widgetReferrersgetReferrerType',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getReferrerType',
                    ),
            ), array (
                'name' => 'All Referrers',
                'uniqueId' => 'widgetReferrersgetAll',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getAll',
                    ),
            ), array (
                'name' => 'List of social networks',
                'uniqueId' => 'widgetReferrersgetSocials',
                'parameters' =>
                    array (
                        'module' => 'Referrers',
                        'action' => 'getSocials',
                    ),
            ), array (
                'name' => 'Goals Overview',
                'uniqueId' => 'widgetGoalswidgetGoalsOverview',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'widgetGoalsOverview',
                    ),
            ), array (
                'name' => 'Download Software',
                'uniqueId' => 'widgetGoalswidgetGoalReportidGoal1',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'widgetGoalReport',
                        'idGoal' => '1',
                    ),
            ), array (
                'name' => 'Download Software2',
                'uniqueId' => 'widgetGoalswidgetGoalReportidGoal2',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'widgetGoalReport',
                        'idGoal' => '2',
                    ),
            ), array (
                'name' => 'Opens Contact Form',
                'uniqueId' => 'widgetGoalswidgetGoalReportidGoal3',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'widgetGoalReport',
                        'idGoal' => '3',
                    ),
            ), array (
                'name' => 'Visit Docs',
                'uniqueId' => 'widgetGoalswidgetGoalReportidGoal4',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'widgetGoalReport',
                        'idGoal' => '4',
                    ),
            ), array (
                'name' => 'Product SKU',
                'uniqueId' => 'widgetGoalsgetItemsSku',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'getItemsSku',
                    ),
            ), array (
                'name' => 'Product Name',
                'uniqueId' => 'widgetGoalsgetItemsName',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'getItemsName',
                    ),
            ), array (
                'name' => 'Product Category',
                'uniqueId' => 'widgetGoalsgetItemsCategory',
                'parameters' =>
                    array (
                        'module' => 'Goals',
                        'action' => 'getItemsCategory',
                    ),
            ), array (
                'name' => 'Overview',
                'uniqueId' => 'widgetEcommercewidgetGoalReportidGoalecommerceOrder',
                'parameters' =>
                    array (
                        'module' => 'Ecommerce',
                        'action' => 'widgetGoalReport',
                        'idGoal' => 'ecommerceOrder',
                    ),
            )
        );
    }

}


WidgetTest::$fixture = new WidgetizeFixture();