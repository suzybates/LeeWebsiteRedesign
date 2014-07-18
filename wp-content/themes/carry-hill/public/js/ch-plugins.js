/*! 11-04-2014 by krivinarius@gmail.com */
function ssc_init(){"use strict";if(document.body){var a=document.body,b=document.documentElement,c=window.innerHeight,d=a.scrollHeight;if(ssc_root=document.compatMode.indexOf("CSS")>=0?b:a,ssc_activeElement=a,ssc_initdone=!0,top!=self)ssc_frame=!0;else if(d>c&&(a.offsetHeight<=c||b.offsetHeight<=c)&&(ssc_root.style.height="auto",ssc_root.offsetHeight<=c)){var e=document.createElement("div");e.style.clear="both",a.appendChild(e)}ssc_fixedback||(a.style.backgroundAttachment="scroll",b.style.backgroundAttachment="scroll"),ssc_keyboardsupport&&ssc_addEvent("keydown",ssc_keydown)}}function ssc_scrollArray(a,b,c,d){if(d||(d=1e3),ssc_directionCheck(b,c),ssc_que.push({x:b,y:c,lastX:0>b?.99:-.99,lastY:0>c?.99:-.99,start:+new Date}),!ssc_pending){var e=function(){for(var f=+new Date,g=0,h=0,i=0;i<ssc_que.length;i++){var j=ssc_que[i],k=f-j.start,l=k>=ssc_animtime,m=l?1:k/ssc_animtime;ssc_pulseAlgorithm&&(m=ssc_pulse(m));var n=j.x*m-j.lastX>>0,o=j.y*m-j.lastY>>0;g+=n,h+=o,j.lastX+=n,j.lastY+=o,l&&(ssc_que.splice(i,1),i--)}if(b){var p=a.scrollLeft;a.scrollLeft+=g,g&&a.scrollLeft===p&&(b=0)}if(c){var q=a.scrollTop;a.scrollTop+=h,h&&a.scrollTop===q&&(c=0)}b||c||(ssc_que=[]),ssc_que.length?setTimeout(e,d/ssc_framerate+1):ssc_pending=!1};setTimeout(e,0),ssc_pending=!0}}function ssc_wheel(a){ssc_initdone||ssc_init();var b=a.target,c=ssc_overflowingAncestor(b);if(!c||a.defaultPrevented||ssc_isNodeName(ssc_activeElement,"embed")||ssc_isNodeName(b,"embed")&&/\.pdf/i.test(b.src))return!0;var d=a.wheelDeltaX||0,e=a.wheelDeltaY||0;d||e||(e=a.wheelDelta||0),Math.abs(d)>1.2&&(d*=ssc_stepsize/120),Math.abs(e)>1.2&&(e*=ssc_stepsize/120),ssc_scrollArray(c,-d,-e),a.preventDefault()}function ssc_keydown(a){var b=a.target,c=a.ctrlKey||a.altKey||a.metaKey;if(/input|textarea|embed/i.test(b.nodeName)||b.isContentEditable||a.defaultPrevented||c)return!0;if(ssc_isNodeName(b,"button")&&a.keyCode===ssc_key.spacebar)return!0;var d,e=0,f=0,g=ssc_overflowingAncestor(ssc_activeElement),h=g.clientHeight;switch(g==document.body&&(h=window.innerHeight),a.keyCode){case ssc_key.up:f=-ssc_arrowscroll;break;case ssc_key.down:f=ssc_arrowscroll;break;case ssc_key.spacebar:d=a.shiftKey?1:-1,f=.9*-d*h;break;case ssc_key.pageup:f=.9*-h;break;case ssc_key.pagedown:f=.9*h;break;case ssc_key.home:f=-g.scrollTop;break;case ssc_key.end:var i=g.scrollHeight-g.scrollTop-h;f=i>0?i+10:0;break;case ssc_key.left:e=-ssc_arrowscroll;break;case ssc_key.right:e=ssc_arrowscroll;break;default:return!0}ssc_scrollArray(g,e,f),a.preventDefault()}function ssc_mousedown(a){ssc_activeElement=a.target}function ssc_setCache(a,b){for(var c=a.length;c--;)ssc_cache[ssc_uniqueID(a[c])]=b;return b}function ssc_overflowingAncestor(a){var b=[],c=ssc_root.scrollHeight;do{var d=ssc_cache[ssc_uniqueID(a)];if(d)return ssc_setCache(b,d);if(b.push(a),c===a.scrollHeight){if(!ssc_frame||ssc_root.clientHeight+10<c)return ssc_setCache(b,document.body)}else if(a.clientHeight+10<a.scrollHeight&&(overflow=getComputedStyle(a,"").getPropertyValue("overflow"),"scroll"===overflow||"auto"===overflow))return ssc_setCache(b,a)}while(a=a.parentNode)}function ssc_addEvent(a,b,c){window.addEventListener(a,b,c||!1)}function ssc_removeEvent(a,b,c){window.removeEventListener(a,b,c||!1)}function ssc_isNodeName(a,b){return a.nodeName.toLowerCase()===b.toLowerCase()}function ssc_directionCheck(a,b){a=a>0?1:-1,b=b>0?1:-1,(ssc_direction.x!==a||ssc_direction.y!==b)&&(ssc_direction.x=a,ssc_direction.y=b,ssc_que=[])}function ssc_pulse_(a){var b,c,d;return a*=ssc_pulseScale,1>a?b=a-(1-Math.exp(-a)):(c=Math.exp(-1),a-=1,d=1-Math.exp(-a),b=c+d*(1-c)),b*ssc_pulseNormalize}function ssc_pulse(a){return a>=1?1:0>=a?0:(1==ssc_pulseNormalize&&(ssc_pulseNormalize/=ssc_pulse_(1)),ssc_pulse_(a))}!function(a){var b={topSpacing:0,bottomSpacing:0,className:"is-sticky",wrapperClassName:"sticky-wrapper",center:!1,getWidthFrom:""},c=a(window),d=a(document),e=[],f=c.height(),g=function(){for(var b=c.scrollTop(),g=d.height(),h=g-f,i=b>h?h-b:0,j=0;j<e.length;j++){var k=e[j],l=k.stickyWrapper.offset().top,m=l-k.topSpacing-i;if(m>=b)null!==k.currentTop&&(k.stickyElement.css("position","").css("top",""),k.stickyElement.parent().removeClass(k.className),k.currentTop=null);else{var n=g-k.stickyElement.outerHeight()-k.topSpacing-k.bottomSpacing-b-i;0>n?n+=k.topSpacing:n=k.topSpacing,k.currentTop!=n&&(k.stickyElement.css("position","fixed").css("top",n),"undefined"!=typeof k.getWidthFrom&&k.stickyElement.css("width",a(k.getWidthFrom).width()),k.stickyElement.parent().addClass(k.className),k.currentTop=n)}}},h=function(){f=c.height()},i={init:function(c){var d=a.extend(b,c);return this.each(function(){var b=a(this),c=b.attr("id"),f=a("<div></div>").attr("id",c+"-sticky-wrapper").addClass(d.wrapperClassName);b.wrapAll(f),d.center&&b.parent().css({width:b.outerWidth(),marginLeft:"auto",marginRight:"auto"}),"right"==b.css("float")&&b.css({"float":"none"}).parent().css({"float":"right"});var g=b.parent();g.css("height",b.outerHeight()),e.push({topSpacing:d.topSpacing,bottomSpacing:d.bottomSpacing,stickyElement:b,currentTop:null,stickyWrapper:g,className:d.className,getWidthFrom:d.getWidthFrom})})},update:g};window.addEventListener?(window.addEventListener("scroll",g,!1),window.addEventListener("resize",h,!1)):window.attachEvent&&(window.attachEvent("onscroll",g),window.attachEvent("onresize",h)),a.fn.sticky=function(b){return i[b]?i[b].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof b&&b?(a.error("Method "+b+" does not exist on jQuery.sticky"),void 0):i.init.apply(this,arguments)},a(function(){setTimeout(g,0)})}(jQuery),function(a){for(var b,c=["Width","Height"];b=c.pop();)!function(b,c){a.fn[b]=b in new Image?function(){return this[0][b]}:function(){var a,b,d=this[0];return"img"===d.tagName.toLowerCase()&&(a=new Image,a.src=d.src,b=a[c]),b}}("natural"+b,b.toLowerCase())}(jQuery);var ssc_framerate=150,ssc_animtime=500,ssc_stepsize=150,ssc_pulseAlgorithm=!0,ssc_pulseScale=6,ssc_pulseNormalize=1,ssc_keyboardsupport=!0,ssc_arrowscroll=50,ssc_frame=!1,ssc_direction={x:0,y:0},ssc_initdone=!1,ssc_fixedback=!0,ssc_root=document.documentElement,ssc_activeElement,ssc_key={left:37,up:38,right:39,down:40,spacebar:32,pageup:33,pagedown:34,end:35,home:36},ssc_que=[],ssc_pending=!1,ssc_cache={};setInterval(function(){ssc_cache={}},1e4);var ssc_uniqueID=function(){var a=0;return function(b){return b.ssc_uniqueID||(b.ssc_uniqueID=a++)}}(),ischrome=/chrome/.test(navigator.userAgent.toLowerCase());ischrome&&(ssc_addEvent("mousedown",ssc_mousedown),ssc_addEvent("mousewheel",ssc_wheel),ssc_addEvent("load",ssc_init));