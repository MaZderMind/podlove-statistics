Ext.define('WeatherPoint', {
    extend: 'Ext.data.Model',
    fields: ['temperature', 'date']
});

Ext.create('Ext.data.Store', {
    model: 'WeatherPoint',
    storeId: 'WeatherStore',
    data: [
        { temperature: 62, date: new Date(2011, 1, 1, 0) },
        { temperature: 50, date: new Date(2011, 1, 1, 2) },
        { temperature: 80, date: new Date(2011, 1, 1, 4) },
        { temperature: 82, date: new Date(2011, 1, 1, 6) },
        { temperature: 58, date: new Date(2011, 1, 1, 8) },
        { temperature: 63, date: new Date(2011, 1, 1, 9) },
        { temperature: 73, date: new Date(2011, 1, 1, 10) },
        { temperature: 78, date: new Date(2011, 1, 1, 11) },
        { temperature: 81, date: new Date(2011, 1, 1, 12) }
    ]
});

Ext.define('Ext.ux.chart.WeatherLinechart', {
	extend: 'Ext.chart.Chart',
	alias: 'widget.weatherlinechart',

	store: 'WeatherStore',
	axes: [
        {
            title: 'Temperature',
            type: 'Numeric',
            position: 'left',
            fields: ['temperature'],
            minimum: 0,
            maximum: 100
        },
        {
            title: 'Time',
            type: 'Time',
            position: 'bottom',
            fields: ['date'],
            dateFormat: 'ga'
        }
    ],

    series: [
        {
            type: 'line',
            xField: 'date',
            yField: 'temperature'
        }
    ]
});
