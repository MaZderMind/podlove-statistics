Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',

		items: [
			{
				id: 'metricsPanel',
				title: l18n.MetricsPanel.Title,

				region: 'west',

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

						listeners: {
							afterrender: {
								single: true,
								fn: function() {
									Ext.getCmp('downloadsMetricsPanel').reloadFromApi();
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
								success: function(response, opts) {
									var res = Ext.decode(response.responseText);
									var nodes = [];

									Ext.iterate(res, function(metricGroup, metrics) {
										if(!l18n.MetricsPanel.DownloadMetric[metricGroup])
											return;

										if(!Ext.isArray(metrics) || metrics.length == 0)
											return;

										var node = {
											text: l18n.MetricsPanel.DownloadMetric[metricGroup],
											checked: false,
											expanded: true,
											children: []
										};

										Ext.iterate(metrics, function(metric) {
											node.children.push({
												text: metric,
												checked: false,
												leaf: true
											});
										});

										nodes.push(node);
									});

									var store = Ext.data.StoreManager.lookup('downloadsMetrics');
									var root = store.getRootNode();
									root.removeAll(true).appendChild(nodes);
								}
							});
						},

						store: Ext.create('Ext.data.TreeStore', {
							storeId: 'downloadsMetrics',

							root: {
								expanded: true,
								children: []
							}
						})
					}, {
						title: l18n.MetricsPanel.Errors,
						html: 'bar'
					}
				]
			}, {
				id: 'graphsPanel',

				region: 'center',
				title: l18n.Title,
				titleAlign: 'center',

				bodyPadding: 10,
				layout: 'fit',

				items: {
					xtype: 'downloadslinechart'
				},

				tbar: {
					defaults: {
						enableToggle: true
					},
					items: [
						{
							xtype: 'daterangefield',
							fieldLabel: l18n.GraphPanel.Toolbar.DateRange,

							listeners: {
								change: function(oldValue, newValue) {
									console.log('changed date to ', newValue);
								}
							}
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
				},

				listeners: {
					afterrender: {
						single: true,
						fn: function() {
							Ext.getCmp('graphsPanel').reloadFromApi();
						},
					}
				},

				reloadFromApi: function() {
					console.log('reloadFromApi');
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
