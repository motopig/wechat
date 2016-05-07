/**
 * Powered by ECDO
 */

if (typeof jQuery === "undefined") {
	throw new Error("Ecdo JavaScript requires jQuery");
}

(function($) {
	"use strict";
	var version = $.fn.jquery.split(" ")[0].split(".")
	if ((version[0] < 2 && version[1] < 9) || (version[0] == 1 && version[1] == 9 && version[2] < 1)) {
		throw new Error("Ecdo JavaScript requires jQuery version 1.9.1 or higher");
	}
})(jQuery);

if (typeof jQuery.fn.modal === "undefined") {
	throw new Error("Ecdo JavaScript requires bootstrap.modal");
}

(function($) {
	"use strict";
	var version = $.fn.modal.Constructor.VERSION.split(" ")[0].split(".")
	if ((version[0] < 4 && version[1] < 3) || (version[0] == 3 && version[1] == 3 && version[2] < 2)) {
		throw new Error("Ecdo JavaScript requires bootstrap.modal version 3.3.2 or higher");
	}
})(jQuery);

(function($) {
	var Ecdo = function() {
		this.version = "1.0";
		this.title = "EJsF";
		this.description = "ECDO's Javascript Framework.";
		this.csrf = "";
		var hell = this;

		this.g = {};

		this.obj = {};
		this.obj.mdl = null;

		this.fn = {
			getCsrf : function() {
				if (hell.csrf === "") {
					if ($(".hellCsrfToken").length > 0) {
						hell.csrf = $(".hellCsrfToken").attr("csrfToken");
					} else if ($("input[name='csrf_token']").length > 0) {
						hell.csrf = $("input[name='csrf_token']").val();
					}
				}

				return hell.csrf;
			},
			getForm : function(frmName) {
				var frm = {};
				if ($("#" + frmName).length > 0) {
					frm = $("#" + frmName);
				} else if ($("." + frmName).length > 0) {
					frm = $("." + frmName);
				}

				return frm;
			},
			getFormParams : function() {
				var str = "";
				if (arguments.length === 0) {
					return str;
				}

				var frm = hell.fn.getForm(arguments[0]);
				if (frm.lenght < 1) {
					return str;
				}

				str = frm.serialize();

				return str;
			},
			getBaseUrl : function() {
				var baseUrl = "";
				var protocol = window.location.protocol;
				var host = window.location.host;
				var path = window.location.pathname;
				var tmp = [];
				baseUrl = protocol + "//" + host;
				tmp = path.split("/");
				if (tmp.length > 2 && tmp[2] === "public") {
					baseUrl += "/" + tmp[1] + "/" + tmp[2];
				}

				if (arguments.length > 0 && typeof arguments[0] === "boolean" && arguments[0]) {
					baseUrl += "/";
				}

				return baseUrl;
			},
			bolt : function() {
				var settings = {
					url : "",
					type : "GET",
					data : "csrf_token=" + hell.csrf
				};

				if (arguments.length > 0 && typeof arguments[0] === "string" && arguments[0] !== "") {
					var baseUrl = hell.fn.getBaseUrl();
					if (arguments[0].indexOf(baseUrl) > -1) {
						settings.url = arguments[0];
					} else {
						settings.url = baseUrl + "/" + arguments[0];
					}
				}

				if (arguments.length > 1 && typeof arguments[1] === "string") {
					if (arguments[1].indexOf("csrf_token") > -1) {
						settings.data = arguments[1];
					} else {
						settings.data += "&" + arguments[1];
					}
				}

				if (arguments.length > 2 && (typeof arguments[2] === "boolean" && arguments[2] || typeof arguments[2] === "string" && arguments[2].toLowerCase() === "post")) {
					settings.type = "POST";
				}

				if (arguments.length > 3 && typeof arguments[3] === "object") {
					var funcs = arguments[3];

					if (typeof funcs.beforeSend === "function") {
						settings.beforeSend = funcs.beforeSend;
					}

					if (typeof funcs.error === "function") {
						settings.error = funcs.error;
					}

					if (typeof funcs.dataFilter === "function") {
						settings.dataFilter = funcs.dataFilter;
					}

					if (typeof funcs.success === "function") {
						settings.success = funcs.success;
					}

					if (typeof funcs.complete === "function") {
						settings.complete = funcs.complete;
					}
				}

				$.ajax(settings);
			},
			elBolt : function(el) {
				var url = "";
				var data = null;
				var type = null;
				var funcs = {};
				var _this = $(el);

				if (typeof _this.attr("bolt-url") !== "undefined") {
					url = _this.attr("bolt-url");
				}

				if (typeof _this.attr("bolt-post") !== "undefined" && _this.attr("bolt-post").toLowerCase() === "true") {
					type = "POST";
				}

				if (typeof _this.attr("bolt-data") !== "undefined" && _this.attr("bolt-data") !== "") {
					data = _this.attr("bolt-data");
				} else if (typeof _this.attr("bolt-form") !== "undefined" && _this.attr("bolt-form") !== "") {
					data = hell.fn.getFormParams(_this.attr("bolt-form"));
					var frm = hell.fn.getForm(_this.attr("bolt-form"));
					if (frm.length > 0 && url === "") {
						url = frm.attr("action");
					}

					if (frm.length > 0 && type === null) {
						type = frm.attr("method");
					}
				}

				if (typeof _this.attr("bolt-modal") !== "undefined") {
					if (hell.obj.mdl !== null) {
						hell.obj.mdl.destroy();
					}
					hell.obj.mdl = new hell.cls.Modal();

					var ttl = _this.attr("bolt-modal");
					var mdlLarge = true;
					
					if (typeof _this.attr("bolt-modal-icon") !== "undefined" && _this.attr("bolt-modal-icon") !== "") {
						hell.obj.mdl.title.icon = _this.attr("bolt-modal-icon");
					}

					if (typeof _this.attr("bolt-modal-size") !== "undefined" && _this.attr("bolt-modal-size").toLowerCase() === "small") {
						mdlLarge = false;
					}

					if (typeof _this.attr("bolt-modal-apply") !== "undefined") {
						hell.obj.mdl.btnApply.on = true;
						if (_this.attr("bolt-modal-apply") !== "") {
							hell.obj.mdl.btnApply.html = _this.attr("bolt-modal-apply");
						}
					} else if (typeof _this.attr("bolt-modal-applyclose") !== "undefined") {
						hell.obj.mdl.btnApply.on = true;
						hell.obj.mdl.btnApply.close = true;
						if (_this.attr("bolt-modal-applyclose") !== "") {
							hell.obj.mdl.btnApply.html = _this.attr("bolt-modal-applyclose");
						}
					}

					if (typeof _this.attr("bolt-modal-close") !== "undefined") {
						hell.obj.mdl.btnClose.on = true;
						if (_this.attr("bolt-modal-close") !== "") {
							hell.obj.mdl.btnClose.html = _this.attr("bolt-modal-close");
						}
					}

					hell.obj.mdl.init(ttl, mdlLarge);
					funcs.success = function(data) {
						hell.obj.mdl.setBody(data);
						if (typeof _this.attr("bolt-modal-form") !== "undefined" && _this.attr("bolt-modal-form") !== "") {
							$(hell.obj.mdl.footer.obj).find(".btn-primary").click(function() {
								var frmName = _this.attr("bolt-modal-form");

								var frm = null;
								if ($("#" + frmName).length > 0) {
									frm = $("#" + frmName);
								} else if ($("." + frmName).length > 0) {
									frm = $("." + frmName);
								}

								if (frm !== null) {
									var mdlUrl = frm.attr("action");
									var mdlType = frm.attr("method");
									var mdlData = hell.fn.getFormParams(frmName);

									hell.fn.bolt(mdlUrl, mdlData, mdlType);
								}
							});
						}
						hell.obj.mdl.show();
					}
				}

				if (typeof _this.attr("bolt-func-beforeSend") !== "undefined") {
					funcs.beforeSend = hell.fn.getFunc(_this.attr("bolt-func-beforeSend"));
				}

				if (typeof _this.attr("bolt-func-error") !== "undefined") {
					funcs.error = hell.fn.getFunc(_this.attr("bolt-func-error"));
				}

				if (typeof _this.attr("bolt-func-dataFilter") !== "undefined") {
					funcs.dataFilter = hell.fn.getFunc(_this.attr("bolt-func-dataFilter"));
				}

				if (typeof _this.attr("bolt-func-success") !== "undefined") {
					funcs.success = hell.fn.getFunc(_this.attr("bolt-func-success"));
				}

				if (typeof _this.attr("bolt-func-complete") !== "undefined") {
					funcs.complete = hell.fn.getFunc(_this.attr("bolt-func-complete"));
				}

				hell.fn.bolt(url, data, type, funcs);
			},
			initBolt : function() {
				$("body").on("click", ".boltClick", function() {
					hell.fn.elBolt(this);
				});

				$("body").on("mouseenter", ".boltMouseenter", function() {
					hell.fn.elBolt(this);
				});

				$("body").on("mouseleave", ".boltMouseleave", function() {
					hell.fn.elBolt(this);
				})

				$("body").on("blur", ".boltBlur", function() {
					hell.fn.elBolt(this);
				});

				$("body").on("focus", ".boltFocus", function() {
					hell.fn.elBolt(this);
				});
			},
			regFunc : function(key, func) {
				if (typeof key !== "string" || key === "" || typeof func !== "function") {
					return false;
				}

				hell.g[key] = func;
			},
			getFunc : function(key) {
				return window[key] || hell.g[key];
			},
			genAlert : function(def) {
				var dft = {
					bg : {
						"color" : "info"
					},
					close : {
						"on" : true,
						"symbol" : "×"
					},
					title : {
						"size" : "4",
						"html" : "提示信息"
					},
					body : {
						"html" : ""
					},
					apply : {
						"color" : "success",
						"html" : "确定",
						"click" : ""
					},
					cancel : {
						"color" : "default",
						"html" : "取消"
					}
				};
				
				if (typeof def.bg === "undefined" || def.bg.color === "") {
					def.bg = dft.bg;
				}
				
				var alert = $("<div>", {"class" : "alert alert-" + def.bg.color + " alert-dismissible fade in", role : "alert"});
				
				if (typeof def.close === "undefined") {
					def.close = dft.close;
				}
				
				if (typeof def.close.on === "undefined" || def.close.on === true) {
					var btnClose = $("<button>", {"type" : "button", "class" : "close", "data-dismiss" : "alert", "aria-label" : "Close"})
					
					if ((typeof def.close.symbol === "undefined" || def.close.symbol === "")) {
						def.close.symbol = dft.close.symbol;
					}
					
					var symbol = $("<span>", {"aria-hidden" : "true", "html" : def.close.symbol});
					symbol.appendTo(btnClose);
					btnClose.appendTo(alert);
				}
				
				if (typeof def.body === "undefined" || typeof def.body.html !== "string") {
					def.body = dft.body;
				}
				
				if (typeof def.title === "undefined" || typeof def.title.html === "undefined" || def.title.html === "") {
					alert.append(def.body.html);
				} else {
					if (typeof def.title.size === "undefined" || def.title.size === "") {
						def.title.size = dft.title.size;
					}
					
					var title = $("<h" + def.title.size + ">", {html : def.title.html});
					title.appendTo(alert);
					
					var bd = $("<p>", {html : def.body.html});
					bd.appendTo(alert);
				}
				
				var btnFlag = false;
				var btnP = $("<p>");
				if (typeof def.apply === "object") {
					btnFlag = true;
					if (typeof def.apply.color === "undefined" || def.apply.color === "") {
						def.apply.color = dft.apply.color;
					}
					
					if (typeof def.apply.html === "undefined" || def.apply.html === "") {
						def.apply.html = dft.apply.html;
					}
					
					var btn = $("<button>", {"type" : "button", "class" : "btn btn-" + def.apply.color, html : def.apply.html});
					
					if (typeof def.apply.click === "function") {
						btn.click(def.apply.click);
					}
					
					btn.appendTo(btnP);
				}
				
				if (typeof def.cancel === "object") {
					if (btnFlag === true) {
						btnP.append(" ");
					}
					
					btnFlag = true;
					if (typeof def.cancel.color === "undefined" || def.cancel.color === "") {
						def.cancel.color = dft.cancel.color;
					}
					
					if (typeof def.cancel.html === "undefined" || def.cancel.html === "") {
						def.cancel.html = dft.cancel.html;
					}
					
					var btn = $("<button>", {"type" : "button", "class" : "btn btn-" + def.cancel.color, "data-dismiss" : "alert", html : def.cancel.html});
					btn.appendTo(btnP);
				}
				
				if (btnFlag) {
					btnP.appendTo(alert);
				}
				
				return alert;
			}
		};

		this.cls = {};
		this.cls.Modal = function() {
			this.animation = true;
			this.options = {
				backdrop : true,
				keyboard : true,
				show : false
			};
			this.obj = null;
			this.skeleton = {
				"class" : [],
				html : "",
				id : ""
			};
			this.dialog = {
				obj : null,
				"class" : [],
				html : "",
				large : true
			};
			this.content = {
				obj : null,
				"class" : [],
				html : ""
			};
			this.header = {
				obj : null,
				"class" : [],
				html : ""
			};
			this.title = {
				obj : null,
				el : "h4",
				icon : "",
				html : ""
			};
			this.body = {
				obj : null,
				"class" : [],
				html : ""
			};
			this.footer = {
				obj : null,
				"class" : [],
				html : "",
				btn : []
			};
			this.btnApply = {
				obj : null,
				on : false,
				close : false,
				html : ""
			};
			this.btnClose = {
				obj : null,
				on : false,
				close : true,
				html : ""
			};

			this.buildSkeleton = function() {
				if (this.obj !== null) {
					this.obj.remove();
				}
				this.obj = $("<div>", {
					"class" : "modal"
				});

				if (this.animation) {
					this.obj.addClass("fade");
				}

				if (this.skeleton["class"].length > 0) {
					this.obj.addClass(this.skeleton["class"].join(" "));
				}

				if (this.skeleton.id !== "") {
					this.obj.attr("id", this.skeleton.id);
				}

				if (this.skeleton.html !== "") {
					this.obj.html(this.skeleton.html);
				}
			}

			this.buildDialog = function() {
				if (this.dialog.obj !== null) {
					this.dialog.obj.remove();
				}
				this.dialog.obj = $("<div>", {
					"class" : "modal-dialog"
				});
				this.dialog.obj.appendTo(this.obj);

				if (this.dialog.large) {
					this.dialog.obj.addClass("modal-lg");
				} else {
					this.dialog.obj.addClass("modal-sm");
				}

				if (this.dialog["class"].length > 0) {
					this.dialog.obj.addClass(this.dialog["class"].join(" "));
				}

				if (this.dialog.html !== "") {
					this.dialog.obj.html(this.dialog.html);
				}
			}

			this.buildContent = function() {
				if (this.content.obj !== null) {
					this.content.obj.remove();
				}
				this.content.obj = $("<div>", {
					"class" : "modal-content"
				});
				this.content.obj.appendTo(this.dialog.obj);

				if (this.content["class"].length > 0) {
					this.content.obj.addClass(this.content["class"].join(" "));
				}

				if (this.content.html !== "") {
					this.content.obj.html(this.content.html);
				}
			}

			this.buildTitle = function() {
				if (this.title.obj !== null) {
					this.title.obj.remove();
				}
				this.title.obj = $("<" + this.title.el + ">", {
					"class" : "modal-title",
					html : "模态窗"
				});
				this.title.obj.appendTo(this.header.obj);

				if (this.title.html !== "") {
					this.title.obj.html(this.title.html);
				}
				
				if (this.title.icon !== "") {
					var icon = $("<i>", {
						"class" : this.title.icon,
						html : " "
					});
					icon.prependTo(this.title.obj);
				}
			}

			this.buildHeader = function() {
				if (this.header.obj !== null) {
					this.header.obj.remove();
				}
				this.header.obj = $("<div>", {
					"class" : "modal-header"
				});
				this.header.obj.prependTo(this.content.obj);

				if (this.header["class"].length > 0) {
					this.header.obj.addClass(this.header["class"].join(" "));
				}

				if (this.header.html !== "") {
					this.header.obj.html(this.header.html);
				} else {
					var hdrClose = $("<button>", {
						type : "button",
						"class" : "close",
						"data-dismiss" : "modal",
						"aria-label" : "Close"
					});
					hdrClose.appendTo(this.header.obj);
					tmpObj = $("<span>", {
						"aria-hidden" : "true",
						html : "&times;"
					});
					tmpObj.appendTo(hdrClose);

					this.buildTitle();
				}
			}

			this.buildBody = function() {
				if (this.body.obj !== null) {
					this.body.obj.remove();
				}
				this.body.obj = $("<div>", {
					"class" : "modal-body"
				});
				this.body.obj.insertAfter(this.header.obj);

				if (this.body["class"].length > 0) {
					this.body.obj.addClass(this.body["class"].join(" "));
				}
				this.body.obj.html(this.body.html);
			}

			this.buildFooter = function() {
				if (this.footer.obj !== null) {
					this.footer.obj.remove();
				}
				this.footer.obj = $("<div>", {
					"class" : "modal-footer"
				});

				if (this.footer["class"].length > 0) {
					this.footer.obj.addClass(this.footer["class"].join(" "));
				}

				if (this.footer.html !== "") {
					this.footer.obj.html(this.footer.html);
					this.footer.obj.css({'text-align':'center'});
				} else {
					if (this.btnApply.on) {
						var tmpObj = $("<button>", {
							type : "button",
							"class" : "btn btn-primary",
							html : "应用"
						});

						if (this.btnApply.html !== "") {
							tmpObj.html(this.btnApply.html);
						}

						if (this.btnApply.close) {
							tmpObj.attr("data-dismiss", "modal");
						}
						tmpObj.appendTo(this.footer.obj);
					}

					if (this.btnClose.on) {
						var tmpObj = $("<button>", {
							type : "button",
							"class" : "btn btn-default",
							html : "关闭"
						});

						if (this.btnClose.html !== "") {
							tmpObj.html(this.btnClose.html);
						}

						if (this.btnClose.close) {
							tmpObj.attr("data-dismiss", "modal");
						}
						tmpObj.appendTo(this.footer.obj);
					}

					if (this.footer.btn.length > 0) {
						$.each(this.footer.btn, function() {
							this.appendTo(this.footer.obj);
						});
					}
					
					if (this.footer.obj.html() !== "") {
						this.footer.obj.appendTo(this.content.obj);
					}
				}
			}

			this.construct = function() {
				var on = true;

				this.buildSkeleton();
				if (this.skeleton.html !== "") {
					on = false;
				}

				if (on) {
					this.buildDialog();
					if (this.dialog.html !== "") {
						on = false;
					}
				}

				if (on) {
					this.buildContent();
					if (this.content.html !== "") {
						on = false;
					}
				}

				if (on) {
					this.buildHeader();
					this.buildBody();
					this.buildFooter();
				}
			}

			this.setOptions = function() {
				if (arguments.length > 0 && typeof arguments[0] === "object") {
					var opts = arguments[0];
					if (typeof opts.backdrop === "boolean") {
						this.options.backdrop = opts.backdrop;
					}

					if (typeof opts.keyboard === "boolean") {
						this.options.keyboard = opts.keyboard;
					}

					if (typeof opts.show === "boolean") {
						this.options.show = opts.show;
					}
				}

				if (this.obj !== null) {
					this.obj.modal(this.options);
				}
			}

			this.init = function() {
				if (arguments.length > 0 && arguments[0] !== "") {
					this.title.html = arguments[0];
				}

				if (arguments.length > 1 && arguments[1] === false) {
					this.dialog.large = false;
				}

				if (arguments.length > 2 && arguments[2] === false) {
					this.animation = false;
				}

				this.construct();
				this.setOptions();
			}

			this.setTtl = function() {
				if (arguments.length === 0) {
					throw new Error("Function needs at least 1 argument TITLE!");
				}

				if (arguments.length > 1) {
					this.title.el = arguments[1];
				}
				this.title.html = arguments[0];
				this.buildTitle();
			}

			this.setBody = function(body) {
				this.body.html = body;
				this.buildBody();
			}

			this.toggle = function() {
				if (this.obj !== null) {
					this.obj.modal("toggle");
				}
			}

			this.show = function() {
				if (this.obj !== null) {
					this.obj.modal("show");
				}
			}

			this.hide = function() {
				if (this.obj !== null) {
					this.obj.modal("hide");
				}
			}

			this.destroy = function() {
				this.obj.remove();
				this.obj = null;
			}
		}
	}

	if (typeof $.hell === "undefined" || ! $.hell) {
		$.hell = new Ecdo();
	}
	
	$(document).ready(function() {
		$.hell.fn.getCsrf();
		$.hell.fn.initBolt();
	});
})(jQuery);