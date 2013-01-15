Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',

		items: [
			{
				id: 'config-panel',
				title: 'Einstellungen',

				region: 'west',

				width: 295,
				minWidth: 290,

				collapsible: true,
				resizable: true,
				resizeHandles: 'e',

				layout: {
					type: 'vbox',
					align: 'stretch',
					pack: 'start'
				},
				bodyStyle: 'background-color:#DFE8F6',

				items: [
					{
						xtype: 'combo',
						id: 'saved-reports',
						fieldLabel: 'Gespeicherte Berichte',
						labelAlign: 'top',

						margin: '5 5 15 5',

						valueField: 'id',
						displayField: 'title',
						store: Ext.create('Ext.data.ArrayStore', {
							autoDestroy: true,
							storeId: 'saved-reports-store',
							fields: ['id', 'title'],
							data: [
								[-1, 'Aktuellen Bericht speichern']
							]
						})
					}, {
						xtype: 'tabpanel',
						id: 'config-tabs',
						flex: 1,
						border: false,
						items: [
							{
								title: 'Zeitraum',
								layout: {
									type: 'vbox',
									align: 'stretch',
									pack: 'start'
								},
								defaults: {
									margin: 10
								},
								items: [
									{
										xtype: 'daterangefield',
										fieldLabel: 'Zeitraum',
										labelAlign: 'top',
										id: 'date-range',
										listeners: {
											beforeInternalValueChange: function(newValue) {
												// calculate resolution and comparisons here
											}
										}
									}, {
										xtype: 'button',
										text: 'Vergleichszeitraum hinzufügen',
										textAlign: 'left',
										iconCls: 'icon add'
									}, {
										xtype: 'combo',
										id: 'resolution',
										fieldLabel: 'Auflösung',
										labelAlign: 'top',
										margin: '50 10 10 10',

										valueField: 'idx',
										displayField: 'title',
										store: Ext.create('Ext.data.ArrayStore', {
											autoDestroy: true,
											storeId: 'resolution-store',
											fields: ['idx', 'title', 'groupfmt'],
											data: [
												['h', 'Eine Stunde', '%Y%m%d%H'],
												['d', 'Ein Tag', '%Y%m%d'],
												['w', 'Eine Woche', '%Y%W'],
												['m', 'Ein Monat', '%Y%m'],
												['y', 'Ein Jahr', '%Y']
											]
										})
									}
								]
							}, {
								title: 'Metriken',
								html: 'This is tab 2 content.'
							}, {
								title: 'Darstellung',
								html: 'This is tab 3 content.'
							}
						],
						bbar: ['->', {
							id: 'config-tabs-next',
							text: 'Weiter &raquo;',
							handler: function() {
								var
									tabPanel = Ext.getCmp('config-tabs'),
									cnt = tabPanel.items.getCount(),
									idx = tabPanel.items.indexOf(tabPanel.getActiveTab());
								
								if(++idx < cnt)
									tabPanel.setActiveTab(idx);
							}
						}],
						listeners: {
							tabchange: function() {
								var
									tabPanel = Ext.getCmp('config-tabs'),
									nextBtn = Ext.getCmp('config-tabs-next'),
									cnt = tabPanel.items.getCount(),
									idx = tabPanel.items.indexOf(tabPanel.getActiveTab());

								nextBtn.setDisabled(idx == cnt-1);
							}
						}
					}
				]
			}, {
				id: 'ContentPanel',
				title: l18n.Title,
				titleAlign: 'center',

				region: 'center'
			}
			
		]
	});
});
