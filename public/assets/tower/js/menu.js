(function($) {
	var towerMenu = {
		target : ".nav-primary",
		menuTags : null,
		groupTag : null,
		subGroupTags : null,
		thirdGroupTags : null,
		group : null,
		menus : null,
		initMenus : function(route) {
			var url = "angel/menu";
			var data = "";
			var method = "GET";
			var func = {
				success : function(rs) {
					var menus = JSON.parse(rs);
					towerMenu.group = menus["group"];
					if (typeof menus["menu"] !== "undefined") {
						towerMenu.menus = menus["menu"];
					}
					towerMenu.init(route);
				},
				error : function() {
					throw new Error("Web Problems!");
				}
			};
			
			$.hell.fn.bolt(url, data, method, func);
		},
		genGroup : function() {
			var tag = $("<ul>", {"class" : "nav bg clearfix"});
			return tag;
		},
		genSubGroup : function() {
			var tag = $("<ul>", {"class" : "nav dk text-sm"});
			return tag;
		},
		genThirdGroup : function() {
			var tag = $("<ul>", {"class" : "nav dker"});
			return tag;
		},
		genMenu : function(menu,route) {
			var url = menu["url"];
			
			if (url.search("http") !== 0 && url.search("#") !== 0) {
				url = $.hell.fn.getBaseUrl(true) + url;
			}
			
            if(menu['pid']==''){
    			var li = $("<li class='active'>");
    			var a = $("<a>", {"class":'active'});
            }else{
                
    			var li = $("<li>");
    			var a = $("<a>", {"href" : url});
                if(route==menu['url']){
        			var li = $("<li class='active'>");
        			var a = $("<a>", {"href" : url,"class":'active'});
                }
            }
			if (menu["target"]) {
				a.attr("target", menu["target"]);
                a.attr("data-target", menu["target"]);
			}
            
            if(menu['class']){
                li.addClass(menu['class']);
            }
            
			a.appendTo(li);
			
			var i = $("<i>", {"class" : menu["icon"]});
			i.appendTo(a);
			
			var span = $("<span>", {"html" : menu["title"]});
			span.appendTo(a);
			
			if (towerMenu.menus !== null && typeof towerMenu.menus[menu["id"]] !== "undefined") {
				towerMenu.genMark().prependTo(a);
			}
			
			return li;
		},
		genMark : function() {
			var tag = $("<span>", {"class" : "pull-right text-muted"});
			// var i = $("<i>", {"class" : "fa fa-angle-up text"});
			var i = $("<i>", {"class" : "fa fa-angle-left text"});
			//i.appendTo(tag);
			i = $("<i>", {"class" : "fa fa-angle-down text-active"});
			//i.appendTo(tag);
			
			return tag;
		},
		showMenus : function(route) {
			var menuTags = [], groupTag = [], subGroupTags = [], thirdGroupTags = [];
			
			groupTag = towerMenu.genGroup();
			for (var ind in towerMenu.group) {
				menuTags[ind] = towerMenu.genMenu(towerMenu.group[ind],route);
				menuTags[ind].appendTo(groupTag);
			}
			
			if (towerMenu.menus !== null) {
				for (var ind in towerMenu.menus) {
					if (typeof towerMenu.group[ind] !== "undefined") {
						var subMenus = towerMenu.menus[ind];
						subGroupTags[ind] = towerMenu.genSubGroup();
						for (var key in subMenus) {
							menuTags[key] = towerMenu.genMenu(subMenus[key],route);
							menuTags[key].appendTo(subGroupTags[ind]);
						}
						
						subGroupTags[ind].appendTo(menuTags[ind]);
					}
				}
				
				for (var ind in towerMenu.menus) {
					if (typeof towerMenu.group[ind] === "undefined") {
						var thirdMenus = towerMenu.menus[ind];
						if (typeof menuTags[ind] !== "undefined") {
							thirdGroupTags[ind] = towerMenu.genThirdGroup();
							for (var key in thirdMenus) {
								menuTags[key] = towerMenu.genMenu(thirdMenus[key],route);
								menuTags[key].appendTo(thirdGroupTags[ind]);
							}
							
							thirdGroupTags[ind].appendTo(menuTags[ind]);
						}
					}
				}
			}
			
			towerMenu.menuTags = menuTags;
			towerMenu.groupTag = groupTag;
			towerMenu.subGroupTags = subGroupTags;
			towerMenu.thirdGroupTags = thirdGroupTags;
			
			$(towerMenu.target).append(groupTag);
		},
		init : function(route) {
			towerMenu.showMenus(route);
			var tagAs = $(towerMenu.target).find("a");
			tagAs.click(function() {
				var _this = $(this);
				if (_this.hasClass("active")) {
					_this.removeClass("active");
				} else {
					_this.addClass("active");
				}
			});
		},
	};
	
	$(document).ready(function() {
        var route = $('#route_path').val();
		towerMenu.initMenus(route);
	});
})(jQuery);