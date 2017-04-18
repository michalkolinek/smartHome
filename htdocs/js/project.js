
moment.locale('cs');

Highcharts.chart('graph', {
    chart: {
        type: 'spline',
        zoomType: 'x'
    },
    title: {
        text: false
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: chartData.categories,
        plotBands: chartData.bands,
        labels: {
            formatter: function () {
                return moment(this.value * 1000).format('D.M. LT');
            }
        }
    },
    yAxis: {
        labels: {
            format: '{value}°C',
            style: {
                color: '#c73301'
            }
        },
        title: {
            text: 'Teplota',
            style: {
                color: '#c73301'
            }
        }
    },
    // , {
    //     labels: {
    //         format: '{value}°C',
    //         style: {
    //             color: '#0091dc'
    //         }
    //     },
    //     title: {
    //         text: 'Venku',
    //         style: {
    //             color: '#0091dc'
    //         }
    //     },
    //     opposite: true
    // }],
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Uvnitř (°C)',
        data: chartData.in,
        yAxis: 0,
        color: '#c73301'
    }, {
        name: 'Venku (°C)',
        data: chartData.out,
        yAxis: 0,
        color: '#0091dc'
    }],
    tooltip: {
        shared: true,
        formatter: function () {
            var s = '<b>' + moment(this.x * 1000).format('D.M. LT') + '</b>';

                s += '<br/><span style="color: #c73301;">Teplota uvnitř: <b>' +
                    this.points[0].y + '</b></span>';

                s += '<br/><span style="color: #0091dc;">Teplota venku: <b>' +
                    this.points[1].y + '</b></span>';
            

            return s;
        }
    }
});
