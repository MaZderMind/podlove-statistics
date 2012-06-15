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
				html: 'centercenter'
			}
		]
	});
});
