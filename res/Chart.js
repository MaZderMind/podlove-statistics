Ext.define('DownloadsPoint', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'date',	type: 'date', dateFormat: 'timestamp'},
		{name: 'hdate',	type: 'string'},
		{name: 'num',	 type: 'float'},
		{name: 'szsum',   type: 'integer'},
		{name: 'episode', type: 'string'},
		{name: 'format',  type: 'string'},
		{name: 'app',	 type: 'string'},
		{name: 'os',	  type: 'string'}
	]
});

Ext.create('Ext.data.Store', {
	model: 'DownloadsPoint',
	storeId: 'DownloadsStore',
	autoLoad: true,
	proxy: {
		type: 'ajax',
		url : '?get=downloads&group='+60*60*24*7
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
			fields: 'num',
			minimum: 0
		}, {
			title: l18n.GraphPanel.Date,
			type: 'Category',
			position: 'bottom',
			fields: 'hdate'
		}
	],

	series: [
		{
			type: 'column',
			xField: 'date',	
			yField: 'num',
			tips: {
				trackMouse: true,
				//width: 80,
				//height: 40,
				renderer: function(storeItem, item) {
					this.setTitle(Ext.Date.format(
						storeItem.get('date'),
						'd.m.'
					));
					this.update(storeItem.get('num'));
				}
			},
			style: {
				fill: '#38B8BF',
				'stroke-width': 3
			}
		}
	]
});
