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
				width: 250,
				minWidth: 150,

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
									editable: false,
									emptyText: l18n.ReportsPanel.DateRange,
									padding: 10
								}),
								padding: '0 0 10 0'
							}, {
								xtype: 'toolbar',
								padding: 10,
								items: [
									{
										text: l18n.ReportsPanel.Chart.Areas,
										cls: 'icon chart-area'
									}, {
										text: l18n.ReportsPanel.Chart.Lines,
										cls: 'icon chart-line'
									}, '-', {
										text: l18n.ReportsPanel.Chart.Pies,
										cls: 'icon chart-pie'
									}, {
										text: l18n.ReportsPanel.Chart.Bars,
										cls: 'icon chart-bar'
									}, {
										text: l18n.ReportsPanel.Chart.StackBars,
										cls: 'icon chart-stackbar'
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
