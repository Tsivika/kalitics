// Dashboard 1 Morris-chart
$(function () {
    "use strict";

    // LINE CHART
    /*let line = new Morris.Line({
        element: 'morris-line-chart',
        resize: true,
        data: [
            {
                y: '2011 Q1',
                item1: 2666
            },
            {
                y: '2011 Q2',
                item1: 2778
            },
            {
                y: '2011 Q3',
                item1: 4912
            },
            {
                y: '2011 Q4',
                item1: 3767
            },
            {
                y: '2012 Q1',
                item1: 6810
            },
            {
                y: '2012 Q2',
                item1: 5670
            },
            {
                y: '2012 Q3',
                item1: 4820
            },
            {
                y: '2012 Q4',
                item1: 15073
            },
            {
                y: '2013 Q1',
                item1: 10687
            },
            {
                y: '2013 Q2',
                item1: 8432
            }
        ],
        xkey: 'y',
        ykeys: ['item1'],
        labels: ['Item 1'],
        gridLineColor: 'transparent',
        lineColors: ['#00C9AE'],
        lineWidth: 1,
        hideHover: 'auto',
    });

    // Morris donut chart
    /!*Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Hiboo",
            value: 12,

        }, {
            label: "Hiboo Projet",
            value: 30
        }, {
            label: "Hiboo Entreprise",
            value: 20
        }],
        resize: true,
        colors: ['#065b4b', '#00c9ae', '#98e5d6']
    });*!/


    browsersChart.options.data.forEach(function(label, i) {
        var legendItem = $('<span></span>').text( label['label'] + " ( " +label['value'] + " )" ).prepend('<br><span>&nbsp;</span>');
        legendItem.find('span')
            .css('backgroundColor', browsersChart.options.colors[i])
            .css('width', '20px')
            .css('display', 'inline-block')
            .css('margin', '5px');
        $('#legend').append(legendItem)
    });

    // Morris bar chart
 Morris.Bar({
    element: 'morris-bar-chart',
    data: [{
        y: 'En cours',
        a: 100,
    }, {
        y: 'En attente',
        a: 75,
    }, {
        y: 'Terminée',
        a: 50,
    }],
    xkey: 'y',
    ykeys: ['a'],
    labels: ['A'],
    barColors: ['#00C9AE', '#ffaa2b', '#dc3545'],
    hideHover: 'auto',
    gridLineColor: 'transparent',
    resize: true
});*/

});
