
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
                color: '#c73301'
            }
        },
        title: {
            text: 'Teplota',
            style: {
                color: '#c73301'
            }
        }
    }, {
        labels: {
            format: '{value}%',
            style: {
                color: '#0091dc'
            }
        },
        title: {
            text: 'Vlhkost',
            style: {
                color: '#0091dc'
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
        yAxis: 0,
        color: '#c73301'
    }, {
        name: 'Vlhkost (%)',
        data: chartData.humidity,
        yAxis: 1,
        color: '#0091dc'
    }],
    tooltip: {
        shared: true
    },
});
