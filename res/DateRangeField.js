Ext.define('Ext.ux.form.field.DateRange', {
	extend: 'Ext.form.TriggerField',
    alias: 'widget.daterangefield',

	strBefore: l18n.DateRangePicker.BeforePrefix,
	strAfter: l18n.DateRangePicker.AfterPrefix,
	displayFormat: l18n.DateRangePicker.DisplayFormat,
	//width: 175,
	triggerCls: 'x-form-date-trigger',
	fromDate: null,
	toDate: null,

	getDateRange: function() {
		return [this.fromDate, this.toDate];
	},
	
	onTriggerClick: function() {
		if(this.disabled){
			return;
		};

		if(this.menu == null){
			var format = Ext.bind(function (date) {
					return Ext.Date.format(date, this.displayFormat);
				}, this);

			this.menu = new Ext.menu.Menu({
				ignoreParentClicks :true,
				width: this.el.getWidth(),
				items: [
					{
						text: l18n.DateRangePicker.Menu.Today,
						handler: Ext.bind(this.setValue, this, [format(new Date())])
					}, {
						text: l18n.DateRangePicker.Menu.Last7Days,
						handler: Ext.bind(this.setValue, this, [
							format(Ext.Date.add(new Date(), Ext.Date.DAY, -7)) + ' - ' + format(new Date())
						])
					}, {
						text: l18n.DateRangePicker.Menu.ThisMonth,
						handler: Ext.bind(this.setValue, this, [
							format(Ext.Date.getFirstDateOfMonth(new Date()) ) + ' - ' + format(new Date())
						])
					}, {
						text: l18n.DateRangePicker.Menu.ThisYear,
						handler: Ext.bind(this.setValue, this, [
							format(new Date(new Date().getFullYear(), 0, 1)) + ' - ' + format(new Date(new Date().getFullYear(), 11, 31))
						])
					}, {
						text: l18n.DateRangePicker.Menu.AtDate,
						menu: {
							plain: true,
							items: {
								xtype: 'datepicker',
								margins: '10 10 10 10',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setValue(format(dt))
									}, this)
								}
							}
						}
					}, {
						text: l18n.DateRangePicker.Menu.Before,
						menu: {
							plain: true,
							items: {
								xtype: 'datepicker',
								margins: '10 10 10 10',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setValue(this.strBefore + ' ' + format(dt))

										this.fromDate = dt;
										this.toDate = null;
									}, this)
								}
							}
						}
					}, {
						text: l18n.DateRangePicker.Menu.After,
						menu: {
							plain: true,
							items: {
								xtype: 'datepicker',
								margins: '10 10 10 10',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setValue(this.strAfter + ' ' + format(dt))
										
										this.fromDate = null;
										this.toDate = dt;
									}, this)
								}
							}
						}
					}, {
						text: l18n.DateRangePicker.Menu.Span,
						menu: {
							plain: true,
							items: {
								xtype: 'fieldcontainer',
								layout: 'hbox',
								items: [
									{
										xtype: 'datepicker',
										itemId: 'fromPicker',
										margins: '10 0 5 10',
										listeners: {
											select: Ext.bind(function(p, dt) {
												var toPicker = p.ownerCt.getComponent('toPicker');
												toPicker.setMinDate(dt);
												this.setValue(format(dt) + ' - ' + format(toPicker.getValue()))

												this.fromDate = dt;
												this.toDate = toPicker.getValue();
											}, this)
										}
									}, {
										xtype: 'datepicker',
										itemId: 'toPicker',
										margins: '10 10 5 12',
										listeners: {
											select: Ext.bind(function(p, dt) {
												var fromPicker = p.ownerCt.getComponent('fromPicker');
												fromPicker.setMaxDate(dt);
												this.setValue(format(fromPicker.getValue()) + ' - ' + format(dt))

												this.fromDate = fromPicker.getValue();
												this.toDate = dt;
											}, this)
										}
									}
								]
							}
						}
					}
				]
			});
		};

		this.onFocus();
		this.menu.showBy(this.el, 'tl-bl?');
	}
});
