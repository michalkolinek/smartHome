
Highcharts.chart('graph', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Vnitřní teplota'
    },
    subtitle: {
        text: 'Přesnost +-2°C'
    },
    xAxis: {
        categories: chartData.categories
    },
    yAxis: [{
        labels: {
            format: '{value}°C',
            style: {
                color: Highcharts.getOptions().colors[2]
            }
        },
        title: {
            text: 'Teplota',
            style: {
                color: Highcharts.getOptions().colors[2]
            }
        }
    }, {
        labels: {
            format: '{value}%',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        title: {
            text: 'Vlhkost',
            style: {
                color: Highcharts.getOptions().colors[1]
            }
        },
        opposite: true
    }],
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Teplota (°C)',
        data: chartData.temp,
        yAxis: 0
    }, {
        name: 'Vlhkost (%)',
        data: chartData.humidity,
        yAxis: 1
    }],
    tooltip: {
        shared: true
    },
});
