Ext.require(['*']);

var
	minDate = Ext.Date.add(new Date(), Ext.Date.DAY, -7),
	maxDate = new Date();

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',
		items: [
			{
				region: 'west',
				title: l18n.ReportsPanel.Title,
				width: 290,
				minWidth: 290,

				collapsible: true,
				resizable: true,
				resizeHandles: 'e',

				layout: 'border',
				items: [
					{
						region: 'north',
						id: 'reportConfigPanel',

						border: false,
						bodyPadding: 10,

						layout: {
							type: 'vbox',
							align: 'stretch'
						},
						items: [
							{
								layout: 'fit',
								items: new DateRangePicker({
									id: 'reportConfigDateRange',
									editable: false,
									emptyText: l18n.ReportsPanel.DateRange,
									padding: 10
								}),
								padding: '0 0 10 0'
							}, {
								xtype: 'toolbar',
								id: 'reportConfigChart',
								padding: 10,
								defaults: {
									iconAlign: 'bottom',
									
									enableToggle: true,
									toggleGroup: 'reportConfigChartSelect'
								},
								items: [
									{
										text: l18n.ReportsPanel.Chart.Areas,
										iconCls: 'icon chart-area',
										pressed: true
									}, {
										text: l18n.ReportsPanel.Chart.Lines,
										iconCls: 'icon chart-line'
									}, '-', {
										text: l18n.ReportsPanel.Chart.Pies,
										iconCls: 'icon chart-pie'
									}, {
										text: l18n.ReportsPanel.Chart.Bars,
										iconCls: 'icon chart-bar'
									}, {
										text: l18n.ReportsPanel.Chart.StackBars,
										iconCls: 'icon chart-stackbar'
									}
								]
							}
						]
					}, {
						region: 'center',
						xtype: 'panel',
						id: 'reportSelectionPanel',
						layout: 'accordion',
						items: [
							{
								title: l18n.ReportsPanel.Saved,
								html: 'zoo'
							}, {
								title: l18n.ReportsPanel.Downloads,
								html: 'foo'
							}, {
								title: l18n.ReportsPanel.Apps,
								html: 'bar'
							}, {
								title: l18n.ReportsPanel.User,
								html: 'moo'
							}
						]
					}
				]
			}, {
				region: 'center',
				title: l18n.GraphPanel.Title,
				titleAlign: 'center',

				bodyPadding: 10,
				html: 'nice graphs'
			}, {
				region: 'east',
				title: l18n.TablePanel.Title,
				titleAlign: 'center',

				width: '30%',
				collapsible: true,
				collapsed: true,
				floatable: false,

				bodyPadding: 10,
				html: 'nice tables'
			}
		]
	});
});
