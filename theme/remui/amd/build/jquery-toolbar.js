/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2018 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */

define(["jquery"],function($){var ToolBar={init:function(options,elem){var self=this;self.elem=elem,self.$elem=$(elem),self.options=$.extend({},$.fn.toolbar.options,options),self.metadata=self.$elem.data(),self.overrideOptions(),self.toolbar=$('<div class="tool-container" />').addClass("tool-"+self.options.position).addClass("toolbar-"+self.options.style).append('<div class="tool-items" />').append('<div class="arrow" />').appendTo("body").css("opacity",0).hide(),self.toolbar_arrow=self.toolbar.find(".arrow"),self.initializeToolbar()},overrideOptions:function(){var self=this;$.each(self.options,function($option){void 0!==self.$elem.data("toolbar-"+$option)&&(self.options[$option]=self.$elem.data("toolbar-"+$option))})},initializeToolbar:function(){var self=this;self.populateContent(),self.setTrigger(),self.toolbarWidth=self.toolbar.width()},setTrigger:function(){function decideTimeout(){self.$elem.hasClass("pressed")?moveTime=setTimeout(function(){self.hide()},150):clearTimeout(moveTime)}function decideTimeout(){self.$elem.hasClass("pressed")?moveTime=setTimeout(function(){self.hide()},150):clearTimeout(moveTime)}var self=this;if("click"!=self.options.event){self.$elem.on({mouseenter:function(event){self.$elem.hasClass("pressed")?clearTimeout(moveTime):self.show()}}),self.$elem.parent().on({mouseleave:function(event){decideTimeout()}}),$(".tool-container").on({mouseenter:function(event){clearTimeout(moveTime)},mouseleave:function(event){decideTimeout()}})}if("click"==self.options.event&&(self.$elem.on("click",function(event){event.preventDefault(),self.$elem.hasClass("pressed")?self.hide():self.show()}),self.options.hideOnClick&&$("html").on("click.toolbar",function(event){event.target!=self.elem&&0===self.$elem.has(event.target).length&&0===self.toolbar.has(event.target).length&&self.toolbar.is(":visible")&&self.hide()})),self.options.hover){var moveTime;self.$elem.on({mouseenter:function(event){self.$elem.hasClass("pressed")?clearTimeout(moveTime):self.show()}}),self.$elem.parent().on({mouseleave:function(event){decideTimeout()}}),$(".tool-container").on({mouseenter:function(event){clearTimeout(moveTime)},mouseleave:function(event){decideTimeout()}})}$(window).resize(function(event){event.stopPropagation(),self.toolbar.is(":visible")&&(self.toolbarCss=self.getCoordinates(self.options.position,20),self.collisionDetection(),self.toolbar.css(self.toolbarCss),self.toolbar_arrow.css(self.arrowCss))})},populateContent:function(){var self=this,location=self.toolbar.find(".tool-items"),content=$(self.options.content).clone(!0).find("a").addClass("tool-item");location.html(content),location.find(".tool-item").on("click",function(event){event.preventDefault(),self.$elem.trigger("toolbarItemClick",this)})},calculatePosition:function(){var self=this;self.arrowCss={},self.toolbarCss=self.getCoordinates(self.options.position,self.options.adjustment),self.toolbarCss.position="absolute",self.toolbarCss.zIndex=self.options.zIndex,self.collisionDetection(),self.toolbar.css(self.toolbarCss),self.toolbar_arrow.css(self.arrowCss)},getCoordinates:function(position,adjustment){var self=this;switch(self.coordinates=self.$elem.offset(),self.options.adjustment&&self.options.adjustment[self.options.position]&&(adjustment=self.options.adjustment[self.options.position]+adjustment),self.options.position){case"top":return{left:self.coordinates.left-self.toolbar.width()/2+self.$elem.outerWidth()/2,top:self.coordinates.top-self.$elem.outerHeight()-adjustment,right:"auto"};case"left":return{left:self.coordinates.left-self.toolbar.width()/2-self.$elem.outerWidth()/2-adjustment,top:self.coordinates.top-self.toolbar.height()/2+self.$elem.outerHeight()/2,right:"auto"};case"right":return{left:self.coordinates.left+self.toolbar.width()/2+self.$elem.outerWidth()/2+adjustment,top:self.coordinates.top-self.toolbar.height()/2+self.$elem.outerHeight()/2,right:"auto"};case"bottom":return{left:self.coordinates.left-self.toolbar.width()/2+self.$elem.outerWidth()/2,top:self.coordinates.top+self.$elem.outerHeight()+adjustment,right:"auto"}}},collisionDetection:function(){var self=this;"top"!=self.options.position&&"bottom"!=self.options.position||(self.arrowCss={left:"50%",right:"50%"},self.toolbarCss.left<20?(self.toolbarCss.left=20,self.arrowCss.left=self.$elem.offset().left+self.$elem.width()/2-20):$(window).width()-(self.toolbarCss.left+self.toolbarWidth)<20&&(self.toolbarCss.right=20,self.toolbarCss.left="auto",self.arrowCss.left="auto",self.arrowCss.right=$(window).width()-self.$elem.offset().left-self.$elem.width()/2-20-5))},show:function(){var self=this;self.$elem.addClass("pressed"),self.calculatePosition(),self.toolbar.show().css({opacity:1}).addClass("animate-"+self.options.animation),self.$elem.trigger("toolbarShown")},hide:function(){var self=this,animation={opacity:0};switch(self.$elem.removeClass("pressed"),self.options.position){case"top":animation.top="+=20";break;case"left":animation.left="+=20";break;case"right":animation.left="-=20";break;case"bottom":animation.top="-=20"}self.toolbar.animate(animation,200,function(){self.toolbar.hide()}),self.$elem.trigger("toolbarHidden")},getToolbarElement:function(){return this.toolbar.find(".tool-items")}};$.fn.toolbar=function(options){if($.isPlainObject(options))return this.each(function(){var toolbarObj=Object.create(ToolBar);toolbarObj.init(options,this),$(this).data("toolbarObj",toolbarObj)});if("string"==typeof options&&0!==options.indexOf("_")){var toolbarObj=$(this).data("toolbarObj");return toolbarObj[options].apply(toolbarObj,$.makeArray(arguments).slice(1))}},$.fn.toolbar.options={content:"#myContent",position:"top",hideOnClick:!1,zIndex:120,hover:!1,style:"default",animation:"standard",adjustment:10}});