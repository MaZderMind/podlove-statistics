Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',
		items: [
			{
				region: 'west',
				title: 'Berichte',
				width: 200,
				
				collapsible: true,
				resizeable: true,
				
				layout: 'fit',
				xtype: 'panel',
				items: {
					layout: 'accordion',
					items: [
						{
							title: 'Gespeicherte Reports',
							html: 'zoo'
						}, {
							title: 'Complete Downloads',
							html: 'foo'
						}, {
							title: 'Podcatcher & Betriebssysteme',
							html: 'bar'
						}, {
							title: 'Benutzernamen',
							html: 'moo'
						}
					]
				}
			}, {
				region: 'center',
				title: 'Podlove Statistics',
				layout: 'border',
				
				items: [
					{
						region: 'north',
						layout: {
							type: 'hbox',
							pack: 'center',
							align: 'top',
							valign: 'top'
						},
						border: 0,
						items: [
							{
								xtype: 'datepicker',
								margin: 20
							}, {
								xtype: 'datepicker',
								margin: 20
							}, {
								xtype: 'menu',
								margin: 20,
								floating: false,
								items: [{
									text: 'Heute'
								},{
									text: 'Gestern'
								},{
									text: 'Vorgestern'
								},{
									text: 'Die letzten 7 Tage'
								},{
									text: 'Die letzte Woche'
								},{
									text: 'Dieser Monat'
								},{
									text: 'Dieses Jahr'
								}]
							}
						]
					}, {
						html: 'centercenter',
						region: 'center'
					}
				]
			}
		]
	});
});
