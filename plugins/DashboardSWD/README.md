# Piwik DashboardSWD Plugin

## Description

This plugin create a page like dashboard with a customizable template and a selection of widgets

## FAQ

__How can I disable annotations on widgets?__

You have to deactivate Annotations plugin

    ./console plugin:deactivate Annotations

## Changelog

0.1.2 Add the rights widgets
	62eef8f Filter and sort right widgets
	bfef896 Add translation of new Report
	b108693 Fix API class that now extends the Referrers\API one
	9a509cb Remove Realtime report
	4d97a26 Create new Report that extends Referrers.getReferrerType to change visualization into graphpie

0.1.1 Fix the template removing segment selection and changing layout from 3 to 2 cols 
	21d9e38 Add Readme
	a5d52f0 Change layout from 3 cols to 2 cols and hide keywords widget
	fcaa585 Remove segment selection
	83fa374 Override datatable footer template to remove php and json exports format
	d9cad9b Downgrade the required version of piwik
	150c4d7 Move loading of Live widget on client side
	3184c5c Fix css classes

