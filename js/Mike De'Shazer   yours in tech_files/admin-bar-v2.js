if('undefined'==typeof wpcom){wpcom={};}
if('undefined'==typeof wpcom.events){wpcom.events=_.extend({},Backbone.Events);}
(function($){var wpNotesArgs=window.wpNotesArgs||{},cacheBuster=wpNotesArgs.cacheBuster||(function(){var today=new Date();return today.getFullYear()+('0'+(1+today.getMonth())).slice(-2)+('0'+today.getDate()).slice(-2);})(),iframeUrl=wpNotesArgs.iframeUrl||'https://widgets.wp.com/notes/';$(function(){new wpntView();});var wpntView=Backbone.View.extend({el:'#wp-admin-bar-notes',hasUnseen:null,initialLoad:true,count:null,iframe:null,iframeWindow:null,messageQ:[],iframeSpinnerShown:false,isJetpack:false,linkAccountsURL:false,currentMasterbarActive:false,initialize:function(){var t=this;var matches=navigator.appVersion.match(/MSIE (\d+)/);if(matches&&parseInt(matches[1],10)<8){var $panel=t.$('#wpnt-notes-panel');var $menuItem=t.$('.ab-empty-item');if(!$panel.length||!$menuItem.length){return;}
var offset=t.$el.offset();t.$('.ab-item').removeClass('ab-item');t.$('#wpnt-notes-unread-count').html('?');$panel.html(' \
					<div class="wpnt-notes-panel-header"><p>Notifications are not supported in this browser!</p> </div> \
					<img src="http://i2.wp.com/wordpress.com/wp-content/mu-plugins/notes/images/jetpack-notes-2x.png" /> \
					<p class="wpnt-ie-note"> \
					Please <a href="http://browsehappy.com" target="_blank">upgrade your browser</a> to keep using notifications. \
					</p>').addClass('browse-happy');t.$el.on('mouseenter',function(e){clearTimeout(t.fadeOutTimeout);if($panel.is(':visible:animated')){$panel.stop().css('opacity','');}
$menuItem.css({'background-color':'#eee'});$panel.show();});t.$el.on('mouseleave',function(){t.fadeOutTimeout=setTimeout(function(){clearTimeout(t.fadeOutTimeout);if($panel.is(':animated')){return;}
$panel.fadeOut(250,function(){$menuItem.css({'background-color':'transparent'});});},350);});return;}
if('function'!=typeof $.fn.spin){$.fn.spin=function(x){};}
this.isRtl=$('#wpadminbar').hasClass('rtl');this.count=$('#wpnt-notes-unread-count');this.panel=$('#wpnt-notes-panel');this.hasUnseen=this.count.hasClass('wpn-unread');if('undefined'!=typeof wpNotesIsJetpackClient&&wpNotesIsJetpackClient)
t.isJetpack=true;if(t.isJetpack&&'undefined'!=typeof wpNotesLinkAccountsURL)
t.linkAccountsURL=wpNotesLinkAccountsURL;this.$el.children('.ab-item').click(function(e){e.preventDefault();t.togglePanel();return false;});$(document).bind("mousedown focus",function(e){var $clicked;if(!t.showingPanel)
return true;$clicked=$(e.target);if($clicked.is(document))
return true;if($clicked.closest('#wp-admin-bar-notes').length)
return true;t.hidePanel();return false;});$(document).on('keydown.notes',function(e){var keyCode=wpNotesCommon.getKeycode(e);if(!keyCode)
return;if((keyCode==27))
t.hidePanel();if((keyCode==78))
t.togglePanel();if(this.iframeWindow===null)
return;if(t.showingPanel&&((keyCode==74)||(keyCode==40))){t.postMessage({action:"selectNextNote"});return false;}
if(t.showingPanel&&((keyCode==75)||(keyCode==38))){t.postMessage({action:"selectPrevNote"});return false;}
if(t.showingPanel&&((keyCode==82)||(keyCode==65)||(keyCode==83)||(keyCode==84))){t.postMessage({action:"keyEvent",keyCode:keyCode});return false;}});wpcom.events.on('notes:togglePanel',function(){t.togglePanel();});if(t.isJetpack)
t.loadIframe();else{setTimeout(function(){t.loadIframe();},3000);}
if(t.count.hasClass('wpn-unread'))
wpNotesCommon.bumpStat('notes-menu-impressions','non-zero');else
wpNotesCommon.bumpStat('notes-menu-impressions','zero');$(window).on('message',function(event){if(!event.data&&event.originalEvent.data){event=event.originalEvent;}
if(event.origin!='https://widgets.wp.com'){return;}
try{var data=('string'==typeof event.data)?JSON.parse(event.data):event.data;if(data.type!='notesIframeMessage'){return;}
t.handleEvent(data);}catch(e){}});},handleEvent:function(event){var inNewdash=('undefined'!==typeof wpcomNewdash&&'undefined'!==typeof wpcomNewdash.router&&'undefined'!==wpcomNewdash.router.setRoute);if(!event||!event.action){return;}
switch(event.action){case"togglePanel":this.togglePanel();break;case"render":this.render(event.num_new,event.latest_type);break;case"renderAllSeen":this.renderAllSeen();break;case"iFrameReady":this.iFrameReady(event);break;case"goToNotesPage":if(inNewdash){wpcomNewdash.router.setRoute('/notifications');}else{window.location.href='//wordpress.com/notifications/';}
break;}},render:function(num_new,latest_type){var t=this,flash=false;if((false===this.hasUnseen)&&(0===num_new))
return;if(this.initialLoad&&this.hasUnseen&&(0!==num_new)){this.initialLoad=false;return;}
if(!this.hasUnseen&&(0!==num_new)){wpNotesCommon.bumpStat('notes-menu-impressions','non-zero-async');}
var latest_icon_type=wpNotesCommon.noteType2Noticon[latest_type];if(typeof latest_icon_type=='undefined')
latest_icon_type='notification';var latest_img_el=$('<span/>',{'class':'noticon noticon-'+latest_icon_type+''});var status_img_el=this.getStatusIcon(num_new);if(0===num_new||this.showingPanel){this.hasUnseen=false;t.count.fadeOut(200,function(){t.count.empty();t.count.removeClass('wpn-unread').addClass('wpn-read');t.count.html(status_img_el);t.count.fadeIn(500);});if(wpcom&&wpcom.masterbar){wpcom.masterbar.hasUnreadNotifications(false);}}else{this.hasUnseen=true;t.count.fadeOut(400,function(){t.count.empty();t.count.removeClass('wpn-read').addClass('wpn-unread');t.count.html(latest_img_el);t.count.fadeIn(400,function(){});});if(wpcom&&wpcom.masterbar){wpcom.masterbar.hasUnreadNotifications(true);}}},renderAllSeen:function(){var img_el=this.getStatusIcon(0);this.count.removeClass('wpn-unread').addClass('wpn-read');this.count.empty();this.count.html(img_el);this.hasUnseen=false;if(wpcom&&wpcom.masterbar){wpcom.masterbar.hasUnreadNotifications(false);}},getStatusIcon:function(number){var new_icon='';switch(number){case 0:new_icon='noticon noticon-notification';break;case 1:new_icon='noticon noticon-notification';break;case 2:new_icon='noticon noticon-notification';break;default:new_icon='noticon noticon-notification';}
return $('<span/>',{'class':new_icon});},togglePanel:function(){this.$el.toggleClass('wpnt-stayopen');this.$el.toggleClass('wpnt-show');this.showingPanel=this.$el.hasClass('wpnt-show');this.loadIframe();if(this.showingPanel){var $unread=this.$('.wpn-unread');if($unread.length){$unread.removeClass('wpn-unread').addClass('wpn-read');}
this.reportIframeDelay();if(this.hasUnseen)
wpNotesCommon.bumpStat('notes-menu-clicks','non-zero');else
wpNotesCommon.bumpStat('notes-menu-clicks','zero');this.hasUnseen=false;}
this.postMessage({action:"togglePanel",showing:this.showingPanel});if(this.showingPanel){this.iframe[0].contentWindow.focus();}else{window.focus();}
this.setActive(this.showingPanel);},setActive:function(active){if(active){this.currentMasterbarActive=$('.masterbar li.active');this.currentMasterbarActive.removeClass('active');this.$el.addClass('active');}else{this.$el.removeClass('active');this.currentMasterbarActive.addClass('active');this.currentMasterbarActive=false;}},loadIframe:function(){var t=this,args=[],src,lang,queries;if(t.iframe===null){t.panel.addClass('loadingIframe');if(t.isJetpack){args.push('jetpack=true');if(t.linkAccountsURL){args.push('link_accounts_url='+escape(t.linkAccountsURL));}}
args.push('v='+cacheBuster);queries=(args.length)?'?'+args.join('&'):'';src=iframeUrl;src=src+queries+'#'+document.location.toString();if($('#wpnt-notes-panel').attr('dir')=='rtl'){src+='&rtl=1';}
lang=$('#wpnt-notes-panel').attr('lang')||'en';if(!lang.match(/^en/)){src+=('&lang='+lang);}
t.iframe=$('<iframe style="display:none" id="wpnt-notes-iframe" frameborder=0 ALLOWTRANSPARENCY="true" scrolling="no"></iframe>');t.iframe.attr('src',src);t.panel.append(t.iframe);}},reportIframeDelay:function(){if(!this.iframeWindow){if(!this.iframeSpinnerShown)
this.iframeSpinnerShown=(new Date()).getTime();return;}
if(this.iframeSpinnerShown!==null){var delay=0;if(this.iframeSpinnerShown)
delay=(new Date()).getTime()-this.iframeSpinnerShown;if(delay===0)
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','0');else if(delay<1000)
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','0-1');else if(delay<2000)
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','1-2');else if(delay<4000)
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','2-4');else if(delay<8000)
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','4-8');else
wpNotesCommon.bumpStat('notes_iframe_perceived_delay','8-N');this.iframeSpinnerShown=null;}},iFrameReady:function(event){var t=this;this.iframeWindow=this.iframe.get(0).contentWindow;if("num_new"in event)
this.render(event.num_new,event.latest_type);this.panel.removeClass('loadingIframe').find('.wpnt-notes-panel-header').remove();this.iframe.show();if(this.showingPanel)
this.reportIframeDelay();$(window).on('focus keydown mousemove scroll',function(){var now=(new Date()).getTime();if(!t.lastActivityRefresh||t.lastActivityRefresh<now-5000){t.lastActivityRefresh=now;t.postMessage({action:"refreshNotes"});}});this.sendQueuedMessages();},hidePanel:function(){if(this.showingPanel){this.togglePanel();}},postMessage:function(message){var t=this;try{var _msg=('string'==typeof message)?JSON.parse(message):message;if(!_.isObject(_msg)){return;}
_msg=_.extend({type:'notesIframeMessage'},_msg);var targetOrigin='https://widgets.wp.com';if(this.iframeWindow&&this.iframeWindow.postMessage){this.iframeWindow.postMessage(JSON.stringify(_msg),targetOrigin);}else{this.messageQ.push(_msg);}}
catch(e){}},sendQueuedMessages:function(){var t=this;_.forEach(this.messageQ,function(m){t.postMessage(m);});this.messageQ=[];}});})(jQuery);