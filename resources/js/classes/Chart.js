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
                onclick: params.onClick || null,
                colors: {
                    y: 'var(--success-color)'
                }
            },
            axis: {
                x: {
                    type: 'timeseries',
                    localtime: false,
                    tick: {
                        format: '%d/%m/%y',
                    }
                },
            },
            grid: {
                y: {
                    show: true
                }
            },
            point: {
                r: 4
            },
            tooltip: {
                contents: ([data], defaultTitleFormat) => {
                    const title = defaultTitleFormat(data.x);
                    const dayTransactions = chartData.json[data.index].transactions;
                    let transactionsTable = '';
                    dayTransactions.forEach((transaction) => {
                        transactionsTable += `<tr><td class="name">${transaction.label}</td><td class="value is-nis">${transaction.amount}</td></tr>`
                    });
                    const dailyTotal = dayTransactions.reduce((sum, transaction) => {
                        return sum += transaction.amount;
                    }, 0);

                    return `<table class="c3-tooltip"><tbody><tr><th colspan="2">${title}</th></tr><tr class="c3-tooltip-name--y"><td class="name">Total</td><td class="value is-nis">${data.value}</td></tr><tr><th colspan="2">Transactions</th></tr>${transactionsTable}<tr><th>Sum</th><th class="value is-nis">${dailyTotal}</th></tr></tbody></table>`;
                },
            },
            onresized: params.onResized || null,
            onrendered: params.onRendered || null
        });
    }

    load(data) {
        this.chart.load({
            json: data,
            keys: chartData.keys,
            names: chartData.names,

        });
        chartData.json = data;
    }

    xGrids(xGrids) {
        this.chart.xgrids(xGrids);
    }
}

export default Chart;

