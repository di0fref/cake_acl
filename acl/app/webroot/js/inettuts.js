var iNettuts = {

	jQuery: $,

	settings: {
		columns: '.column',
		widgetSelector: '.widget',
		handleSelector: '.widget-head',
		contentSelector: '.widget-content',
		widgetDefault: {
			movable: true,
			removable: true,
			collapsible: true,
			editable: true,
			colorClasses: ['color-yellow', 'color-red', 'color-blue', 'color-white', 'color-orange', 'color-green'],
			content: "<div align='center'><img src='img/load.gif' border='0' /></div>"
		},
		widgetIndividual: {}
	},

	init: function (callback) {
		this.attachStylesheet('css/inettuts.js.css');
		$(this.settings.columns).css({
			visibility: 'visible'
		});
		this.sortWidgets();
		this.addWidgetControls();
		this.makeSortable();
		this.addEvents();
		callback();
	},

	addEvents: function () {
		$(".fmaxbox").live("click", function (e) {
			e.preventDefault();
			var span = $(this).closest(".RssEntry").find(".fmaxbox");
			var element = $(this).closest(".RssEntry").find(".RssSummary");
			element.toggle();
			if (element.is(":visible")) {
				span.css("background-position", "-5px 5px");
			}
			else {
				span.css("background-position", "7px 3px");
			}
		});

		$(this.settings.widgetSelector).live("mouseover", function () {
			$(this).addClass("mhover");
		});
		$(this.settings.widgetSelector).live("mouseout", function () {
			$(this).removeClass("mhover");
		});

		$(".cancel").live("click", function () {
			$(this).parents().find('.edit-box').slideUp();
		});
		$(".save").live("click", function () {
			var id = $(this).parent().parent().parent().parent().attr("id");

			$.post("widgets/saveWidgetSettings", {
				nr_of_articles: $(this).parents().find('.edit-box .nr_of_art').val(),
				id: id
			}).done(function () {
					$("#" + id + " " + iNettuts.settings.contentSelector).html(iNettuts.settings.widgetDefault.content);
					iNettuts.loadWidget(id);
				});
			$(this).parents().find('.edit-box').slideUp();
		});
	},

	initWidget: function (opt) {
		if (!opt.content) opt.content = iNettuts.settings.widgetDefault.content;
		return '<li id="' + opt.id + '" class="new widget ' + opt.color + '"><div class="widget-head"><h3>' + opt.title + '</h3></div><div class="widget-content">' + opt.content + '</div></li>';
	},

	loadWidget: function (id) {

		$.post("widgets/getWidgetData", {
				"id": id
			},
			function (data) {
				$("#" + id + " " + iNettuts.settings.contentSelector).html(data);
			});
	},

	addWidget: function (where, opt) {
		$("li").removeClass("new");
		var selectorOld = iNettuts.settings.widgetSelector;
		iNettuts.settings.widgetSelector = '.new';
		$(where).append(iNettuts.initWidget(opt));
		iNettuts.addWidgetControls();
		iNettuts.settings.widgetSelector = selectorOld;
		iNettuts.makeSortable();
		iNettuts.savePreferences();
		iNettuts.loadWidget(opt.id);
	},


	getWidgetSettings: function (id) {
		var $ = this.jQuery,
			settings = this.settings;
		return (id && settings.widgetIndividual[id]) ? $.extend({}, settings.widgetDefault, settings.widgetIndividual[id]) : settings.widgetDefault;
	},

	removefromDB: function (id) {
		$.post("widgets/removeWidget", {
			id: id
		});
	},

	addWidgetControls: function () {
		var iNettuts = this,
			$ = this.jQuery,
			settings = this.settings;

		$(settings.widgetSelector, $(settings.columns)).each(function () {

			var thisWidgetSettings = iNettuts.getWidgetSettings(this.id);
			if (thisWidgetSettings.removable) {
				$('<a href="#" class="remove"></a>').mousedown(function (e) {
					e.stopPropagation();
				}).click(function () {
						if (confirm('This widget will be removed, ok?')) {
							/* Delete entry in DB */
							iNettuts.removefromDB($(this).parent().parent().attr("id"));
							$(this).parents(settings.widgetSelector).animate({
								opacity: 0
							}, function () {
								$(this).wrap('<div/>').parent().slideUp(function () {
									$(this).remove();
									iNettuts.savePreferences();

								});
							});
						}
						return false;
					}).appendTo($(settings.handleSelector, this));
			}

			if (thisWidgetSettings.editable) {
				$('<a href="#" class="edit"></a>').mousedown(function (e) {
					e.stopPropagation();
				}).toggle(function () {
						$(this).parents(settings.widgetSelector).find('.edit-box')
							.load("widgets/editWidgetForm", {id: $(this).parent().parent().attr("id")})
							.slideDown();
						return false;
					},
					function () {
						$(this).parents(settings.widgetSelector).find('.edit-box').slideUp();
						return false;
					}).appendTo($(settings.handleSelector, this));
				$('<div class="edit-box" style="display:none;"/>')
					.insertAfter($(settings.handleSelector, this));
			}

		});
	},

	attachStylesheet: function (href) {
		var $ = this.jQuery;
		return $('<link href="' + href + '" rel="stylesheet" type="text/css" />').appendTo('head');
	},

	makeSortable: function () {
		var iNettuts = this,
			$ = this.jQuery,
			settings = this.settings,
			$sortableItems = (function () {
				var notSortable = '';
				$(settings.widgetSelector, $(settings.columns)).each(function (i) {
					if (!iNettuts.getWidgetSettings(this.id).movable) {
						if (!this.id) {
							this.id = 'widget-no-id-' + i;
						}
						notSortable += '#' + this.id + ',';
					}
				});
				if (notSortable == '')
					return $("> li", settings.columns);
				else
					return $('> li:not(' + notSortable + ')', settings.columns);
			})();

		$sortableItems.find(settings.handleSelector).css({
			cursor: 'move'
		}).mousedown(function (e) {
				$sortableItems.css({
					width: ''
				});
				$(this).parent().css({
					width: $(this).parent().width() + 'px'
				});
			}).mouseup(function () {
				if (!$(this).parent().hasClass('dragging')) {
					$(this).parent().css({
						width: ''
					});
				} else {
					$(settings.columns).sortable('disable');
				}
			});

		$(settings.columns).sortable('destroy');
		$(settings.columns).sortable({
			items: $sortableItems,
			connectWith: $(settings.columns),
			handle: settings.handleSelector,
			placeholder: 'widget-placeholder',
			forcePlaceholderSize: true,
			revert: 300,
			delay: 100,
			opacity: 0.8,
			containment: 'document',
			start: function (e, ui) {
				$(ui.helper).addClass('dragging');
			},
			stop: function (e, ui) {
				$(ui.item).css({
					width: ''
				}).removeClass('dragging');
				$(settings.columns).sortable('enable');
				iNettuts.savePreferences();
			}
		});
	},

	savePreferences: function () {
		var iNettuts = this,
			$ = this.jQuery,
			settings = this.settings;


		var widget_settings = new Array();

		$(settings.columns).each(function (column) {
			$(settings.widgetSelector, this).each(function (order) {

				var widget_setting = {
					id: $(this).attr('id'),
					column: column,
					order: order
				};
				widget_settings.push(widget_setting);
			});
		});

		$.ajax({
			url: "widgets/setWidgetData",
			type: "post",
			dataType: "json",
			data: JSON.stringify(widget_settings)
		});
	},


	sortWidgets: function () {
		var iNettuts = this,
			$ = this.jQuery,
			settings = this.settings;

		$.ajax({
			url: "widgets/getWidgets",
			type: "POST",
			dataType: "json",
			async: false,
			success: function (response) {
				var opt = {};
				$.each(response.response.data, function (obj_key, obj) {
					$.each(obj, function (key, value) {
						opt[key] = value;
					});
					$("#column" + opt.Widget["_column"]).append(iNettuts.initWidget(opt.Widget));
					iNettuts.loadWidget(opt.Widget["id"]);
				});
				iNettuts.makeSortable();
			}
		});

	}

};


$(document).ready(function () {

	iNettuts.init(function () {
		//setInterval(function(){iNettuts.loadWidgets()}, 3000);
	});


});

