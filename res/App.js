Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',
		items: [
			{
				region: 'west',
				title: l18n.MetricsPanel.Title,
				width: 295,
				minWidth: 290,

				collapsible: true,
				resizable: true,
				resizeHandles: 'e',

		
				xtype: 'panel',
				id: 'reportSelectionPanel',
				layout: 'accordion',
				items: [
					{
						title: l18n.MetricsPanel.Saved,
						html: 'zoo'
					}, {
						title: l18n.MetricsPanel.Downloads,
						html: 'foo'
					}, {
						title: l18n.MetricsPanel.Errors,
						html: 'bar'
					}
				]
			}, {
				region: 'center',
				title: l18n.Title,
				titleAlign: 'center',

				bodyPadding: 10,
				html: 'nice graphs',

				
				tbar: {
					defaults: {
						enableToggle: true,
						toggleGroup: 'reportConfigChartSelect'
					},
					items: [
						{
							xtype: 'daterangefield',
							fieldLabel: l18n.GraphPanel.Toolbar.DateRange
						}, '->', {
							text: l18n.GraphPanel.Toolbar.Areas,
							iconCls: 'icon chart-area',
							pressed: true
						}, {
							text: l18n.GraphPanel.Toolbar.Lines,
							iconCls: 'icon chart-line'
						}, '-', {
							text: l18n.GraphPanel.Toolbar.Pies,
							iconCls: 'icon chart-pie'
						}, {
							text: l18n.GraphPanel.Toolbar.Bars,
							iconCls: 'icon chart-bar'
						}, {
							text: l18n.GraphPanel.Toolbar.StackBars,
							iconCls: 'icon chart-stackbar'
						}
					]
				}
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
