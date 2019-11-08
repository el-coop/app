import c3 from 'c3';

let chartData;

class Chart {

    constructor(el, data, params = {}) {
        chartData = data;
        this.chart = c3.generate({
            bindto: el,
            size: {
                height: params.height || 400
            },
            title: {
                text: params.title || ''
            },
            data: {
                json: data.data || {},
                keys: data.keys || [],
                names: data.names || [],
                onclick: params.onClick || null
            },
            axis: {
                x: {
                    show: false,
                    type: 'timeseries',
                    tick: {
                        format: '%m/%y',
                    }
                },
                y: {
                    show: false
                }
            },
            onresized: params.onResized || null,
            onrendered: params.onRendered || null
        });
    }

    load(data) {
        this.chart.load({
            json: data,
            keys: chartData.keys,
            names: chartData.names
        });
    }

    xGrids(xGrids) {
        this.chart.xgrids(xGrids);
    }
}

export default Chart;

