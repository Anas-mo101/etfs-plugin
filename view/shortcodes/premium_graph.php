<style>
    #pd-container {
        height: 800px;
        min-width: 310px;
        width: 100%;
    } 
</style>
<div class="chart-wrapper">
	<div id="pd-container"> </div> 
</div>
<script>
    (() => {
        const initPDGraph = async () => {
            const title =  window.location.pathname.replace(/\//g, '');
            const baseURL = "https://" + window.location.hostname + "/wp-json/etf-rest/v1";
            const response = await fetch(baseURL + '/premium/list', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fund: title,
                }),
                cache: 'no-cache'
            });

            const pdGraphDataSet = await response.json();

            let chart = Highcharts.stockChart('pd-container', {
                legend: {
                    enabled: true,
                    align: 'right',
                    verticalAlign: 'top',
                    x: -10,
                    y: 210,
                    floating: true,
                    labelFormat: title + ' Premium/Discount'
                },
                rangeSelector: {
                    selected: 1
                },
                title: {
                    text: ''
                },
                navigator: {
                    maskFill: 'rgba(99, 213, 211, 0.45)',
                    height: 86,
                    top: 40,
                    margin: 60,
                    series: {
                        type: 'line',
                        fillOpacity: 4,
                        fill: '#ffffff',
                        dataGrouping: {
                            smoothed: false
                        },
                        lineWidth: 2,
                        lineColor: 'rgba(99, 213, 211)',
                        fillColor: {
                            stops: [
                                [0, '#FF8000'],
                                [1, '#FFFF00']
                            ]
                        },
                        marker: {
                            enabled: false
                        },
                    },
                    xAxis: {
                        title: {
                            text: 'Time Period Filter',
                            align: 'low',
                            y: -110
                        },
                    },
                },
                chart: {
                    polar: true,
                    reflow: true,
                    type: 'line',
                    backgroundColor: '#f3f3f3',
                    plotBackgroundColor: '#ffffff',
                    marginTop: 200,
                    events: {
                        beforePrint: function() {
                            this.setSize(10000, null, false)
                        },
                        afterPrint: function() {
                            this.setSize(null, null, false)
                        },
                        load: function(event) {
                            event.target.reflow(); 
                        }
                    },
                },
                xAxis: {
                    title: {
                        text: 'Date',
                        y: 20,
                        style: {
                            fontSize: '14px',
                        }
                    },
                    labels: {
                        format: '{value:%b %d, %Y}',
                        minPadding: 0,
                        maxPadding: 0,
                        rotation: -45,
                        style: {
                            fontSize: '17px',
                            fontFamily: 'Avenir Next, sans-serif',
                            color: '#949494'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Premium/Discount',
                        style: {
                            fontSize: '14px',
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '17px',
                            fontFamily: 'Avenir Next, sans-serif',
                            color: '#949494'
                        },
                        align: 'left',
                        max: 43934,
                        x: 0
                    },
                    lineWidth: 0,
                    offset: 40,
                    opposite: false
                },
                inputBoxBorderColor: '#ffffff',
                inputBoxWidth: 110,
                inputBoxHeight: 84,
                inputStyle: {
                    color: '#9C9C9C',
                    fontWeight: '600'
                },
                exporting: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                rangeSelector: {
                    allButtonsEnabled: true,
                    buttons: [
                        {
                            type: 'all',
                            text: 'ALL TIME'
                        },
                        {
                            type: 'year',
                            count: 1,
                            text: 'Previous Year'
                        },
                        {
                            type: 'month',
                            count: 12,
                            text: 'Q1 Current Year'
                        },
                        {
                            type: 'month',
                            count: 9,
                            text: 'Q2 Current Year'
                        },
                        {
                            type: 'month',
                            count: 6,
                            text: 'Q3 Current Year'
                        },
                        {
                            type: 'month',
                            count: 3,
                            text: 'Q4 Current Year'
                        }
                    ],
                    buttonTheme: {
                        width: 110,
                        fill: 'transparent',
                        style: {
                            fontSize: 17,
                            fontWeight: 500,
                            color: '#0C233F',
                            style: {
                                color: '#63d5d3'
                            }
                        },
                        states: {
                            hover: {
                                fill: 'transparent',
                                stroke: '#63D5D3',
                                color: '#63D5D3'
                            },
                            select: {
                                fill: 'transparent',
                                stroke: '#63D5D3',
                                style: {
                                    color: '#63d5d3'
                                }
                            }
                        }
                    },
                    selected: 0
                },
                series: [{
                    name: title,
                    color: '#63d5d3',
                    data: pdGraphDataSet,
                    tooltip: {
                        valueDecimals: 4
                    }
                }]
            });
            chart.reflow();
            Highcharts.setOptions({
                lang: {
                    // Pre-v9 legacy settings
                    rangeSelectorFrom: '',
                    rangeSelectorTo: 'to',
                    rangeSelectorZoom: 'ZOOM'
                }
            });
            chart.reflow();
        }

        initPDGraph();
    })();
</script>