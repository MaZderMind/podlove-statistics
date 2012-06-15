Ext.require(['*']);

Ext.onReady(function () {
	var viewport = Ext.create('Ext.Viewport', {
		layout: 'border',
		items: [
			{
				region: 'north',
				xtype: 'toolbar',
				items: [
					{
						text: 'Paste',
						iconCls: 'icon add',
						cls: 'x-btn-as-arrow'
					}
				]
			}, {
				region: 'west',
				title: 'Left',
				html: 'leftleft',
				minWidth: 200,
				
				collapsible: true,
				resizeable: true
			}, {
				region: 'center',
				title: 'Center',
				html: 'centercenter'
			}
		]
	});
});
