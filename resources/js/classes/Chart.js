import c3 from 'c3';

let chartData;

class Chart {

    constructor(el, data, params){
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
                names: data.names || []
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m/%y',
                    }
                }
            },
            onresized: params.onresized || null,
            onrendered: params.onrendered || null
        });
    }

    load(data){
        this.chart.load({
            json: data,
            keys: chartData.keys,
            names: chartData.names
        });
    }
}

export default Chart;

