Ext.require(['Ext.Window', 'Ext.layout.container.Fit', 'Ext.window.MessageBox']);

Ext.onReady(function () {
	
	var win = Ext.create('Ext.Window', {
		width: 800,
		height: 600,
		minHeight: 400,
		minWidth: 550,
		hidden: false,
		shadow: false,
		maximizable: true,
		renderTo: Ext.getBody(),
		layout: 'fit',
		title: 'Test',
		items: [
			{
				xtype: 'panel',
				html: 'Test'
			}
		]
	});
});
