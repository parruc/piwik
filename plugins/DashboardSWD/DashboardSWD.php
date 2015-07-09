<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\DashboardSWD;

class DashboardSWD extends \Piwik\Plugin
{
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'AssetManager.getJavaScriptFiles' => 'getJavaScriptFiles',
        );
    }

    public function getJavaScriptFiles(&$files)
    {
        $files[] = 'plugins/DashboardSWD/javascripts/moment.js';
        $files[] = 'plugins/DashboardSWD/javascripts/daterangepicker.js';
        $files[] = 'plugins/DashboardSWD/javascripts/iframeResizer.js';
        $files[] = 'plugins/DashboardSWD/javascripts/plugin.js';
    }

    public function getStylesheetFiles(&$files)
    {
        $files[] = 'plugins/DashboardSWD/stylesheets/daterangepicker.less';
        $files[] = 'plugins/DashboardSWD/stylesheets/plugin.less';
    }
}
