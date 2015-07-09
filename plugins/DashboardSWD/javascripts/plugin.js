$(document).ready(function () {

    (function (require, $) {

        function getUrlParameter(sParam)
        {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++)
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam)
                {
                    return sParameterName[1];
                }
            }
        }

        var ajaxHelper = require('ajaxHelper');
        var ajax = new ajaxHelper();
        var period = getUrlParameter("period");
        var date = getUrlParameter("date");
        var start_date = moment(date, "YYYY-MM-DD");
        var end_date = moment(date, "YYYY-MM-DD");
        if(period == "range")
        {
            var dates = date.split(',');
            start_date = moment(dates[0], "YYYY-MM-DD");
            end_date = moment(dates[1], "YYYY-MM-DD");
        }

        $('.dashboard-swd #daterange .daterange-visualizer').html(start_date.format('DD-MM-YYYY') + " - " + end_date.format('DD-MM-YYYY'));
        $('.dashboard-swd #daterange').daterangepicker({
            parentEl: ".dashboard-swd",
            startDate: start_date.format('DD-MM-YYYY'),
            endDate: end_date.format('DD-MM-YYYY'),
            opens: "left",
            format: 'DD-MM-YYYY',
            minDate: "01-01-2014",
            maxDate: moment().format("DD-MM-YYYY"),
            dateLimit: { days: 365 },
            ranges: {
               'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
               'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
            },
            locale: {
                applyLabel: 'Applica',
                cancelLabel: 'Cancella',
                fromLabel: 'Dal',
                toLabel: 'Al',
                customRangeLabel: 'Intervallo ',
                daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven','Sab'],
                monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                firstDay: 1
            }
        }, function(start, end) {
            $('.dashboard-swd  #daterange .daterange-visualizer').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
            location.href = 'index.php?module=DashboardSWD&action=index&idSite=' + piwik.idSite + '&period=range&date=' + start.format('YYYY-MM-DD') + "," + end.format('YYYY-MM-DD') + '&token_auth=' + piwik.token_auth;
        }).on("click", function(evt){
            evt.preventDefault();
        });

        // BBB piwik uses a striped down version of bootstrap so this datepicker shows up and is ugly
        $(".dashboard-swd .daterangepicker").hide();


        if (document.getElementById('liveReportContainer')) {

            ajax.setUrl('index.php?module=Live&method=getLastVisitsDetails&idSite=' + piwik.idSite + '&period=' + piwik.period) + '&date=' + pluginDate;
            ajax.setCallback(function (response) {
                $('#liveReportContainer').html(response);
            });
            ajax.setFormat('html'); // the expected response format
            ajax.setLoadingElement('#liveReportContainerLoading');
            ajax.send();
        }
    })(require, jQuery);

});
