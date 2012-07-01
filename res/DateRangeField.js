var DateRangePicker = Ext.extend( Ext.form.TriggerField, {
	strBefore: l18n.DateRangePicker.BeforePrefix,
	strAfter: l18n.DateRangePicker.AfterPrefix,
	storageFormat: 'Y-m-d',
	displayFormat: l18n.DateRangePicker.DisplayFormat,
	width: 175,
	triggerCls: 'x-form-date-trigger',

	sendParam: function() {
		var reformat = function (dstr) {
			return Date.parseDate(dstr.trim(), this.displayFormat).format(this.storageFormat)
		}.createDelegate(this);

		var str = this.getValue();
		try {
			if (str) {
				var fstr = '', tstr = '', tst;
				str = str.trim();

				// parse date string
				if ((tst = str.indexOf('-')) > 0) {
					// from...to
					fstr = reformat(str.substring(0, tst));
					tstr = reformat(str.substring(tst + 1));
				}
				else if (str.startsWith(this.strBefore)) {
					// to
					tstr = reformat(str.substring(this.strBefore.length));
				}
				else if (str.startsWith(this.strAfter)) {
					// from
					fstr = reformat(str.substring(this.strAfter.length));
				}
				else {
					// to = from
					fstr = tstr = reformat(str);
				};
				str = '';

				if (fstr) str += 'FROM ' + fstr;

				if (tstr) str += ' TO ' + tstr;

				str = str.trim();
			};
		} catch(e){/*who cares?*/}

		return str;
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
							items: {
								xtype: 'datepicker',
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
							items: {
								xtype: 'datepicker',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setValue(this.strBefore + ' ' + format(dt))
									}, this)
								}
							}
						}
					}, {
						text: l18n.DateRangePicker.Menu.After,
						menu: {
							items: {
								xtype: 'datepicker',
								listeners: {
									select: Ext.bind(function(p, dt) {
										this.setValue(this.strAfter + ' ' + format(dt))
									}, this)
								}
							}
						}
					}, {
						text: l18n.DateRangePicker.Menu.Span,
						menu: {
							items: {
								xtype: 'fieldcontainer',
								layout: 'hbox',
								items: [
									this.fromPicker = new Ext.picker.Date({
										listeners: {
											select: Ext.bind(function(p, dt) {
												this.setValue(format(dt) + ' - ' + format(this.toPicker.getValue()))
											}, this)
										}
									}),
									this.toPicker = new Ext.picker.Date({
										xtype: 'datepicker',
										listeners: {
											select: Ext.bind(function(p, dt) {
												this.setValue(format(this.fromPicker.getValue()) + ' - ' + format(dt))
											}, this)
										}
									})
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
