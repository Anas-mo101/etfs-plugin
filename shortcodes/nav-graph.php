<?php ?>
  
<style>
    #container {
        height: 1098px;
        min-width: 310px;
        width: 100%;
    } 
</style>

<script>
    <?php
	$graph_josn = get_post_meta(get_the_ID(), "ETF-Pre-graph-json-data", true);
    $post_title = get_the_title();
    echo 'const graphStrigJson = "'.$graph_josn. '";'; ?>
    preGraphDataSet = graphStrigJson == '' || graphStrigJson == null ? [] : JSON.parse(graphStrigJson); 
</script>

<div class="chart-wrapper">
	<div id="container"></div> 
</div>
<script defer>
    jQuery(document).ready(function() {
        setTimeout(function() {
            let chart = Highcharts.stockChart('container', {
                rangeSelector: {
                    selected: 1
                },
                title: {
                    text: ''
                },
                navigator: {
                    maskFill: 'rgba(99, 213, 211, 0.45)',
                    height: 276,
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

                    yAxis: {
                        reversed: true,
                        plotBands: [{
                            color: '#ffffff',
                            from: 0,
                            to: 100000
                        }]
                    },
                },
                chart: {
                    polar: true,
                    reflow: true,
                    type: 'line',
                    backgroundColor: '#f3f3f3',
                    plotBackgroundColor: '#ffffff',
                    plotAreaHeight: 653,
                    events: {
                        beforePrint: function() {
                            this.setSize(10000, null, false)
                        },
                        afterPrint: function() {
                            this.setSize(null, null, false)
                        },
                        load: function(event) {
                            event.target.reflow(); // i tried this not working 
                        }
                    },
                },
                xAxis: {
                    labels: {
                        format: '{value:%b %d, %Y}',
                        minPadding: 0,
                        maxPadding: 0,
                        style: {
                            fontSize: '20px',
                            fontFamily: 'Avenir Next, sans-serif',
                            color: '#949494'
                        }
                    }
                },
                yAxis: {
                    labels: {
                        style: {
                            fontSize: '20px',
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
                    buttons: [{
                            type: 'month',
                            count: 1,
                            text: '1M'
                        },
                        {
                            type: 'month',
                            count: 3,
                            text: '3M'
                        },
                        {
                            type: 'month',
                            count: 6,
                            text: '6M'
                        },
                        {
                            type: 'ytd',
                            text: 'YTD'
                        },
                        {
                            type: 'year',
                            count: 1,
                            text: '1Y'
                        },
                        {
                            type: 'year',
                            count: 3,
                            text: '3Y'
                        },
                        {
                            type: 'year',
                            ount: 5,
                            text: '5Y'
                        },
                        {
                            type: 'all',
                            text: 'ALL TIME'
                        }
                    ],
                    buttonTheme: {
                        width: 110,
                        fill: 'transparent',
                        style: {
                            fontSize: 20,
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
                    selected: 4
                },
                series: [{
                    name: '<?php echo $post_title; ?>',
                    color: '#63D5D3',
                    data: preGraphDataSet,
                    tooltip: {
                        valueDecimals: 2
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
        });
    });
</script>