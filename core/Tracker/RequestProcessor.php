<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Tracker;

use Piwik\Tracker\Visit\VisitProperties;

/**
 * TODO
 *
 * TODO: maybe we should rename manipulateVisitProperties to afterRequestProcessed and rename processRequest to handleRequest or recordLogs
 */
abstract class RequestProcessor
{
    /**
     * TODO
     */
    public function processRequestParams(VisitProperties $visitProperties, Request $request)
    {
        return false;
    }

    /**
     * TODO
     */
    public function manipulateVisitProperties(VisitProperties $visitProperties, Request $request)
    {
        return false;
    }

    /**
     * TODO
     */
    public function onNewVisit(VisitProperties $visitProperties, Request $request)
    {
        // empty
    }

    /**
     * TODO
     */
    public function onExistingVisit(&$valuesToUpdate, VisitProperties $visitProperties, Request $request)
    {
        // empty
    }

    /**
     * TODO
     */
    public function processRequest(Visitor $visitor, VisitProperties $visitProperties)
    {
        // empty
    }
}