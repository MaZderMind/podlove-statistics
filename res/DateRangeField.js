Ext.define('Ext.ux.form.field.DateRange', {
	extend: 'Ext.form.TriggerField',
 	alias: 'widget.daterangefield',

	strBefore: l18n.DateRangePicker.BeforePrefix,
	strAfter: l18n.DateRangePicker.AfterPrefix,
	strBetween: l18n.DateRangePicker.BetweenInfix,
	displayFormat: l18n.DateRangePicker.DisplayFormat,
	//width: 175,
	triggerCls: 'x-form-date-trigger',
	fromDate: null,
	toDate: null,
	internalValue: null,

	setInternalValue: function(value) {
		if(!this.fireEvent('beforeInternalValueChange', value))
			return;

		this.internalValue = value;

		if(value.relative) {
			this.setValue(l18n.DateRangePicker.Menu[value.relative]);
		}
		else if(value.atdate) {
			this.setValue(Ext.Date.format(value.atdate, this.displayFormat));
		}
		else if(value.before) {
			this.setValue(this.strBefore + Ext.Date.format(value.before, this.displayFormat));
		}
		else if(value.after) {
			this.setValue(this.strAfter + Ext.Date.format(value.after, this.displayFormat));
		}
		else if(value.from && value.to) {
			this.setValue(Ext.Date.format(value.from, this.displayFormat) + this.strBetween + Ext.Date.format(value.to, this.displayFormat));
		}
		else {
			this.setValue('???');
		}

		this.fireEvent('afterInternalValueChange');
	},

	getInternalValue: function() {
		return this.internalValue;
	},

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
					// relative values, that refer to an relative date ranges
					{
						text: l18n.DateRangePicker.Menu.Today,
						handler: Ext.bind(this.setInternalValue, this, [{relative: 'Today'}])
					}, {
						text: l18n.DateRangePicker.Menu.Last7Days,
						handler: Ext.bind(this.setInternalValue, this, [{relative: 'Last7Days'}])
					}, {
						text: l18n.DateRangePicker.Menu.ThisMonth,
						handler: Ext.bind(this.setInternalValue, this, [{relative: 'ThisMonth'}])
					}, {
						text: l18n.DateRangePicker.Menu.ThisYear,
						handler: Ext.bind(this.setInternalValue, this, [{relative: 'ThisYear'}])
					},

					// absolute values, thet refer to an absolute date range
					{
						text: l18n.DateRangePicker.Menu.AtDate,
						menu: {
							plain: true,
							items: {
								xtype: 'datepicker',
								margins: '10 10 10 10',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setInternalValue({atdate: dt});
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
										this.setInternalValue({before: dt});

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
										this.setInternalValue({after: dt})
										
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
												this.setInternalValue({from: dt, to: toPicker.getValue()});

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
												this.setInternalValue({from: fromPicker.getValue(), to: dt});

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
