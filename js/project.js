
moment.locale('cs');

Highcharts.chart('graph', {
    chart: {
        type: 'spline',
        zoomType: 'x'
    },
    title: {
        text: 'Vnitřní teplota'
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
        shared: true,
        formatter: function () {
            var s = '<b>' + moment(this.x * 1000).format('D.M. LT') + '('+this.x+')</b>';

                s += '<br/><span style="color: #c73301;">' + this.points[0].series.name + ': <b>' +
                    this.points[0].y + '</b></span>';

                s += '<br/><span style="color: #0091dc;">' + this.points[1].series.name + ': <b>' +
                    this.points[1].y + '</b></span>';
            

            return s;
        }
    }
});
