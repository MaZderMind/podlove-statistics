Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',

		items: [
			{
				region: 'west',

				xtype: 'panel',
				id: 'metricsPanel',
				title: l18n.MetricsPanel.Title,
				
				width: 295,
				minWidth: 290,

				collapsible: true,
				resizable: true,
				resizeHandles: 'e',

				layout:  'accordion',

				items: [
					/*{
						title: l18n.MetricsPanel.Saved,
						html: 'zoo'
					},*/ {
						xtype: 'treepanel',
						id: 'downloadsMetricsPanel',
						title: l18n.MetricsPanel.Downloads,
						
						rootVisible: false,
						
						store: Ext.create('Ext.data.TreeStore', {
							storeId: 'downloadsMetrics',

							listeners: {
								load: {
									single: true,
									fn: function() {
										Ext.data.StoreManager.lookup('downloadsMetrics').reloadFromApi();
									},
								}
							},
							
							reloadFromApi: function() {
								var store = Ext.data.StoreManager.lookup('downloadsMetrics');
								var episode = store.getNodeById('episode');
								
								Ext.Ajax.request({
									url: '.',
									params: {
										get: 'metrics'
									},
									success: function() {
										episode.appendChild({text: 'fooo', leaf: true});
									}
								});
							},

							root: {
								expanded: true,
								children: [
									{
										id: 'episode',
										text: l18n.MetricsPanel.DownloadMetric.Episode,
										expanded: true,
										checked: false
									}, {
										id: 'format',
										text: l18n.MetricsPanel.DownloadMetric.Format,
										expanded: true,
										checked: false
									}, {
										text: l18n.MetricsPanel.DownloadMetric.OS,
										Apps: 'os',
										expanded: true,
										checked: false
									}, {
										text: l18n.MetricsPanel.DownloadMetric.App,
										Apps: 'app',
										expanded: true,
										checked: false
									}, {
										text: l18n.MetricsPanel.DownloadMetric.Country,
										Apps: 'country',
										expanded: true,
										checked: false
									}
								]
							}
						})
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
						enableToggle: true
					},
					items: [
						{
							xtype: 'daterangefield',
							fieldLabel: l18n.GraphPanel.Toolbar.DateRange
						}, '->', {
							text: l18n.GraphPanel.Toolbar.Areas,
							iconCls: 'icon chart-area',
							pressed: true,
							toggleGroup: 'chartSelect'
						}, {
							text: l18n.GraphPanel.Toolbar.Lines,
							iconCls: 'icon chart-line',
							toggleGroup: 'chartSelect'
						}, '-', {
							text: l18n.GraphPanel.Toolbar.Pies,
							iconCls: 'icon chart-pie',
							toggleGroup: 'chartSelect'
						}, {
							text: l18n.GraphPanel.Toolbar.Bars,
							iconCls: 'icon chart-bar',
							toggleGroup: 'chartSelect'
						}, {
							text: l18n.GraphPanel.Toolbar.StackBars,
							iconCls: 'icon chart-stackbar',
							toggleGroup: 'chartSelect'
						}, ' ', '-', ' ', {
							text: 'Downloads',
							iconCls: 'icon brick',
							toggleGroup: 'axisSelect',
							pressed: true
						}, {
							text: 'Bytes',
							iconCls: 'icon drive-web',
							toggleGroup: 'axisSelect'
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
