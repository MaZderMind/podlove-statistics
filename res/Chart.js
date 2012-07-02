Ext.define('DownloadsPoint', {
	extend: 'Ext.data.Model',
	fields: [
        {name: 'date', type: 'date', dateFormat: 'timestamp'},
        {name: 'num',  type: 'integer'},
        'episode', 'format', 'app', 'os'
    ]
});

Ext.create('Ext.data.Store', {
	model: 'DownloadsPoint',
	storeId: 'DownloadsStore',
    autoLoad: true,
	proxy: {
		type: 'ajax',
		url : '?get=downloads'
	}
});

Ext.define('Ext.ux.chart.DownloadsLinechart', {
	extend: 'Ext.chart.Chart',
	alias: 'widget.downloadslinechart',

	store: 'DownloadsStore',
	axes: [
		{
			title: l18n.GraphPanel.Downloads,
			type: 'Numeric',
			position: 'left',
			fields: ['num'],
			minimum: 0,
            maximum: 100
		}, {
			title: l18n.GraphPanel.Date,
			type: 'Time',
			position: 'bottom',
			fields: 'date',
			//groupBy: 'year',
            //aggregateOp: 'sum',
			dateFormat: l18n.GraphPanel.DateFormat
		}
	],

	series: [
		{
			type: 'line',
			xField: 'date',
			yField: 'num'
		}
	]
});
