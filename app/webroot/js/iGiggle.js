$(function () {

	setInterval(function () {
		$("#flashMessage").slideUp();
	}, 3000);

	$("#gbqfq").googleSuggest("web");
	$("#search_button").click(function (e) {
		e.stopPropagation();
		$("#search_form").submit();
	});

	var widgetControl = {
		settings: {
			addWidgetUrl: "widgets/addWidget",
			addWidgetColumn: "#column0",
			dialog: $("#dialog"),
			color: "color-white"
		},
		init: function () {
			self = this;
			$("#addWidgetLink").click(function () {
				self.addWidget();
			});
		},

		create_guid: function () {
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			});
		},
		addWidget: function () {

			var guid = this.create_guid();

			this.settings.dialog.dialog({
				modal: true,
				autoOpen: true,
				width: 350,
				height: 200,
				close: function () {
					$(widgetControl.settings.dialog).html();
				},
				buttons: {
					OK: function () {
						$.ajax({
							url: widgetControl.settings.addWidgetUrl,
							dataType: "json",
							async: false,
							type: "post",
							data: {
								title: $("#title").val(),
								url: $("#url").val(),
								id: guid
							},
							success: function (response) {
								if (response.response.status == true) {
									iNettuts.addWidget(widgetControl.settings.addWidgetColumn, {
										id: guid,
										color: widgetControl.settings.color,
										title: $("#title").val()
									});
									widgetControl.settings.dialog.dialog("close");
								}
								else {
									widgetControl.settings.dialog.append(response.response.message);
								}
							}
						});
					},
					Cancel: function () {
						widgetControl.settings.dialog.dialog("close");
					}
				}
			});
			$(widgetControl.settings.dialog).load("widgets/addWidgetForm");
		},

		removeWidget: function () {

		}
	};

	widgetControl.init();

});