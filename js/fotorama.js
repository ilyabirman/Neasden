/*! Fotorama 1.5 (v1242) | http://fotoramajs.com/license.txt */
window.Mdrnzr=function(c,n,I){function A(b,c){var d=b.charAt(0).toUpperCase()+b.substr(1),d=(b+" "+Q.join(d+" ")+d).split(" ");return z(d,c)}function z(b,c){for(var d in b)if(K[b[d]]!==I)return"pfx"==c?b[d]:!0;return!1}var f={},V=n.documentElement;n.head||n.getElementsByTagName("head");var d=n.createElement("Mdrnzr"),K=d.style,c=" -webkit- -moz- -o- -ms- -khtml- ".split(" "),Q="Webkit Moz O ms Khtml".split(" "),d={},j=[],W=function(b,c,d,j){var f,z,A=n.createElement("div");if(parseInt(d,10))for(;d--;)z=
n.createElement("div"),z.id=j?j[d]:"Mdrnzr"+(d+1),A.appendChild(z);f=["&shy;<style>",b,"</style>"].join("");A.id="Mdrnzr";A.innerHTML+=f;V.appendChild(A);b=c(A,b);A.parentNode.removeChild(A);return!!b},X,N={}.hasOwnProperty,b;typeof N!==I&&typeof N.call!==I?b=function(b,c){return N.call(b,c)}:b=function(b,c){return c in b&&typeof b.constructor.prototype[c]===I};(function(b,c){var d=b.join(""),j=c.length;W(d,function(b){for(var b=b.childNodes,c={};j--;)c[b[j].id]=b[j];f.csstransforms3d=9===c.csstransforms3d.offsetLeft},
j,c)})([,["@media (",c.join("transform-3d),("),"Mdrnzr){#csstransforms3d{left:9px;position:absolute}}"].join("")],[,"csstransforms3d"]);d.canvas=function(){var b=n.createElement("canvas");return!!b.getContext&&!!b.getContext("2d")};d.csstransforms3d=function(){var b=!!z(["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"]);b&&"webkitPerspective"in V.style&&(b=f.csstransforms3d);return b};d.csstransitions=function(){return A("transitionProperty")};for(var fa in d)b(d,
fa)&&(X=fa.toLowerCase(),f[X]=d[fa](),j.push((f[X]?"":"no-")+X));K.cssText="";d=null;f._version="2.0.6";f._prefixes=c;f._domPrefixes=Q;f.testProp=function(b){return z([b])};f.testAllProps=A;f.testStyles=W;return f}(this,this.document);
jQuery.extend({bez:function(c){var n="bez_"+$.makeArray(arguments).join("_").replace(".","p");if("function"!=typeof jQuery.easing[n]){var I=function(c,z){var f=[null,null],n=[null,null],d=[null,null],K=function(Q,j){return d[j]=3*c[j],n[j]=3*(z[j]-c[j])-d[j],f[j]=1-d[j]-n[j],Q*(d[j]+Q*(n[j]+Q*f[j]))};return function(c){for(var j=c,A=0,z;14>++A;){z=K(j,0)-c;if(0.001>Math.abs(z))break;j-=z/(d[0]+j*(2*n[0]+3*f[0]*j))}return K(j,1)}};jQuery.easing[n]=function(A,z,f,n,d){return n*I([c[0],c[1]],[c[2],c[3]])(z/
d)+f}}return n}});
(function(c){function n(b){for(var a={},c=0;c<P.length;c++){var d=P[c][0],j=P[c][1];if(b){var f=b.attr("data-"+d);f&&("number"==j?(f=Number(f),isNaN(f)||(a[d]=f)):"boolean"==j?a[d]="true"==f?!0:!1:"string"==j&&(a[d]=f))}else a[d]=P[c][2]}return a}function I(b,a){for(var c={},d=0;d<la.length;d++)c[la[d]+b]=a;return c}function A(b,a){if(K)return I("transform",a?"translate3d(0,"+b+"px,0)":"translate3d("+b+"px,0,0)");var c={};c[a?"top":"left"]=b;return c}function z(b){return I("transition-duration",b+
"ms")}function f(b){b.mousemove(function(a){a.preventDefault()}).mousedown(function(a){a.preventDefault()})}function V(t,a){function n(){L.css({backgroundPosition:"24px "+(24-56*va)+"px"});va++;7<va&&(va=0)}function I(){Y||(Wa=Y=1E3*(v/u));a.thumbs&&!M&&(M=a.vertical?C.width():C.height());if(a.resize){Y=Wa;var e=fa.height();v=t.width()-(a.vertical&&M?M:0);u=Math.round(1E3*(v/Y));if(u>e-0-(!a.vertical&&M?M:0))u=e-0-(!a.vertical&&M?M:0),Y=1E3*(v/u)}}function P(e,b){if(v&&u&&(!wa||e)){b||I();a.vertical?
(l=u,xa=v):(l=v,xa=u);r.add(x).css({width:v,height:u});a.vertical&&a.thumbs&&(a.verticalThumbsRight?C.css({left:v}):r.css({left:M}));if(a.touchStyle){Ka=(l+a.margin)*s-a.margin;Xa=xa;var ya={};ya[E]=Ka;ya[Z]=Xa;J.css(ya).data(ya).data({minPos:-(Ka-l),maxPos:0})}else J.css({width:v,height:u});a.thumbs&&((a.thumbsPreview||!a.vertical)&&C.css(E,l),C.css({visibility:"visible"}));Q&&!a.vertical&&(a.arrows&&ga.css({top:u/2}),L.css(za,xa/2));("loading"==Aa||"error"==Aa)&&L.css(F,R*(l+a.margin)+l/2);la();
S&&a.touchStyle&&oa(J,-R*(l+a.margin),0);wa=!0}if(e){Ba(S,R);var h=0;c(La).each(function(){clearTimeout(this)});La=[];x.each(function(a){if(a!=R){var e=c(this);e.data("img")&&e.data("img").css({visibility:"hidden"});var b=setTimeout(function(){Ba(e,a)},50*h+50);La.push(b);h++}})}}function na(e,w,c){function h(){wa&&(a.touchStyle||(w=0),L.css(F,w*(l+a.margin)+l/2),Ya=setTimeout(function(){L.stop().show().fadeTo(0,1)},100))}clearTimeout(Ya);switch(e){case "loading":h();t.addClass(b+"_loading").removeClass(b+
"_error");clearInterval(Ca);Ja?(L.css({backgroundImage:"url("+jb+")"}),Ca=setInterval(n,100)):L.html("<span>&middot;&middot;&middot;</span>");break;case "error":h();t.addClass(b+"_error").removeClass(b+"_loading");clearInterval(Ca);Ja?L.css({backgroundImage:"url("+Va+")",backgroundPosition:"24px 24px"}):L.text("?");break;case "loaded":t.removeClass(b+"_loading "+b+"_error"),L.stop().fadeTo(c,0,function(){L.hide()}),clearInterval(Ca)}Aa=e}function oa(e,b,c,h){var g=isNaN(b)?0:b;clearTimeout(e.data("backAnimate"));
h&&(g=h,e.data({backAnimate:setTimeout(function(){oa(e,b,Math.max(N,c/2))},c)}));c&&(clearTimeout(Za),Da=!0);K?(e.css(z(c)),setTimeout(function(){e.css(A(g,a.vertical))},1)):e.stop().animate(A(g,a.vertical),c,X);Za=setTimeout(function(){Da=!1},c)}function V(e,b,c){if(G){if(!c||G<l)Ea=!1;var h=aa.position()[F];if(c=aa.data()[E]){da.show();if(G>l){var g=h+c/2,d=l/2,p=H.index(aa),k=p-$a;void 0==T&&(T=o.position()[F]);if(Ma&&b&&b>Math.max(36,2*a.thumbMargin)&&b<l-Math.max(36,2*a.thumbMargin)&&(0<k&&b>
0.75*d||0>k&&b<1.25*d)){var m;m=0<k?p+1:p-1;0>m?m=0:m>s-1&&(m=s-1);p!=m&&(g=H.eq(m),g=g.position()[F]+g.data()[E]/2,d=b)}b=-(G-l);g=Math.round(-(g-d)+a.thumbMargin);if(0<k&&g>T||0>k&&g<T)g=h+T<a.thumbMargin?-(h-a.thumbMargin):h+T+c>l?-(2*h-l+c+a.thumbMargin):T;if(g<=b)g=b;else if(g>=a.thumbMargin)g=a.thumbMargin;o.data({minPos:b});Fa(g);ea||o.data({maxPos:a.thumbMargin})}else g=l/2-G/2,o.data({minPos:g}),ea||o.data({maxPos:g});!Ea&&!ea?(oa(o,g,e),Ga&&(Ea=!0),T=g):Ga=!0;var D=c-(j?0:2*a.thumbBorderWidth);
K?(da.css(z(e)),setTimeout(function(){da.css(A(h,a.vertical)).css(E,D)},1)):a.vertical?da.stop().animate({top:h,height:D},e,X):da.stop().animate({left:h,width:D},e,X)}else da.hide()}}function Fa(e){a.shadows&&G>l&&(C.addClass(b+"__thumbs_shadow"),e&&(e<=o.data("minPos")?C.removeClass(b+"__thumbs_shadow_no-left").addClass(b+"__thumbs_shadow_no-right"):e>=a.thumbMargin?C.removeClass(b+"__thumbs_shadow_no-right").addClass(b+"__thumbs_shadow_no-left"):C.removeClass(b+"__thumbs_shadow_no-left "+b+"__thumbs_shadow_no-right")))}
function la(){!ha&&!ea&&!pa&&!Da&&(Fa(),V(0,!1,!0))}function Ba(e,b){var c=e.data("img"),h=e.data("detached");if(c&&!h){var g=e.data("srcKey"),h=y[g].imgWidth,d=y[g].imgHeight,p=y[g].imgRatio,k=g=0;a.touchStyle&&e.css(F,b*(l+a.margin));if(h!=v||d!=u||a.alwaysPadding){var m=0;if(Math.round(p)!=Math.round(Y)||a.alwaysPadding)m=2*a.minPadding;p>=Y?a.cropToFit?(d=u,h=Math.round(d*p/1E3)):(h=Math.round(v-m)<h||a.zoomToFit?Math.round(v-m):h,d=Math.round(1E3*(h/p))):a.cropToFit?(h=v,d=Math.round(1E3*(h/
p))):(d=Math.round(u-m)<d||a.zoomToFit?Math.round(u-m):d,h=Math.round(d*p/1E3))}c.css({visibility:"visible"});h&&d&&(p={width:h,height:d},c.attr(p).css(p),d!=u&&(g=Math.round((u-d)/2)),h!=v&&(k=Math.round((v-h)/2)),c.css({top:g,left:k}))}else c&&h&&e.data({needToResize:!0})}function ma(e,w,d,h){function g(c){function f(){k.css({visibility:"hidden"});p.src=c;0==D&&(k.appendTo(w),"thumb"==h&&(G+=B+a.thumbMargin,o.css(E,G).data(E,G),w.css(E,B).data(E,B)))}function l(){ia[c]="loaded";w.trigger("load."+
b).data({state:"loaded"});setTimeout(function(){if(!y[c])y[c]=[],y[c].imgWidth=k.width(),y[c].imgHeight=k.height(),y[c].imgRatio=1E3*(y[c].imgWidth/y[c].imgHeight);d(p,y[c].imgWidth,y[c].imgHeight,y[c].imgRatio,c,e)},100);"thumb"==h&&(Ha++,Ha==s&&(Ma=!0))}function q(a){ia[c]="error";k.unbind("error load");D<m.length&&a?(g(m[D]),D++):(w.trigger("error."+b).data({state:"error"}),"thumb"==h&&(Ha++,Ha==s&&(Ma=!0)))}if(ia[c]){var n=function(){"error"==ia[c]?q(!1):"loaded"==ia[c]?l():setTimeout(n,100)};
f();n()}else ia[c]="loading",j.data({loading:!0}),k.unbind("error load").error(function(){q(!0)}).load(l),f()}var j=x.eq(e),p=new Image,k=c(p),m=[],D=0,f=y[e].imgHref,l=y[e].imgSrc,q=y[e].thumbSrc;"img"==h?(f&&(m.push(f),m.push(f+"?"+ja)),l&&(m.push(l),m.push(l+"?"+ja)),q&&(m.push(q),m.push(q+"?"+ja))):(q&&(m.push(q),m.push(q+"?"+ja)),l&&(m.push(l),m.push(l+"?"+ja)),f&&(m.push(f),m.push(f+"?"+ja)));g(m[D]);D++}function Na(e,w){if(e.data("wraped"))a.detachSiblings&&e.data("detached")&&(e.data({detached:!1}).appendTo(J),
e.data("needToResize")&&(Ba(e,w),e.data({needToResize:!1})));else if(J.append(e),e.data({wraped:!0,detached:!1}),ma(w,e,function(a,h,d,f,p){a=c(a);a.addClass(b+"__img");e.data({img:a,srcKey:p});if((!v||!u)&&!wa)v=h,u=d,I();e.css({visibility:"visible"});Ba(e,w);P()},"img"),Oa&&U[w].html&&U[w].html.length||a.html&&a.html[w]&&a.html[w].length)e.append(U[w].html||a.html[w])}function kb(e,b){var d=0,h=!1,g=[];for(i=0;i<2*a.preload+1;i++){var f=b-a.preload+i;if(0<=f&&f<s){if(!x.eq(f).data("wraped")||x.eq(f).data("detached"))d++,
g.push(f)}else h=!0}if(d>=a.preload||h)c(g).each(function(a){setTimeout(function(){Na(x.eq(g[a]),g[a])},50*a)}),a.detachSiblings&&(d=b-a.preload,0>d&&(d=0),h=b+a.preload+1,h>s-1&&(h=s-1),x.slice(0,d).add(x.slice(h,s-1)).data({detached:!0}).detach())}function ka(e,d,f,h,g,j){function p(){if(a.caption)(n=U[q].caption)?Pa.html(n).show():Pa.html("").hide()}function k(){if(a.shadows||!a.touchStyle)m.removeClass(b+"__frame_active"),e.addClass(b+"__frame_active")}var m,D,n,t,q=x.index(e);x.each(function(){c(this).unbind("load."+
b+" error."+b)});g||(g=h?0:a.transitionDuration);!h&&d&&d.altKey&&(g*=10);d=e.data("state");"loading"==d||!d?(na("loading",q,g),e.one("load."+b,function(){na("loaded",q,g);p()}),e.one("error."+b,function(){na("error",q,g);p()})):"error"==d?na("error",q,g):d!=Aa&&na("loaded",q,0);p();S?(m=S,t=R,a.thumbs&&(D=aa)):(m=x.not(e),a.thumbs&&(D=H.not(H.eq(q))));a.thumbs&&(aa=H.eq(q),t&&($a=t),D.removeClass(b+"__thumb_selected").data("disabled",!1),aa.addClass(b+"__thumb_selected").data("disabled",!0));a.thumbs&&
a.thumbsPreview&&t!=q&&V(g,f);if(a.touchStyle)f=-q*(l+a.margin),k(),oa(J,f,g,j);else{var r=function(a){if(t!=q){var b=g,c=0;a&&(b=0,c=g);x.not(m.stop()).stop().fadeTo(0,0);setTimeout(function(){k();e.stop().fadeTo(b,1,function(){m.stop().fadeTo(c,0)})},10)}};"loaded"==d?r():"error"==d?r("error"):(e.one("load."+b,function(){r()}),e.one("error."+b,function(){r("error")}))}S=e;R=q;a.arrows&&((0==R||2>s)&&!a.loop?qa.addClass(b+"__arr_disabled").data("disabled",!0):qa.removeClass(b+"__arr_disabled").data("disabled",
!1),(R==s-1||2>s)&&!a.loop?ra.addClass(b+"__arr_disabled").data("disabled",!0):ra.removeClass(b+"__arr_disabled").data("disabled",!1));var o=e.data("detached")||e.data("wraped");clearTimeout(ab);ab=setTimeout(function(){if(!o&&q!=a.startImg&&(Na(e,q),a.onShowImg))a.onShowImg({index:q,img:S,thumb:aa,caption:n});kb(e,q)},g+10);if(o||q==a.startImg)if(Na(e,q),a.onShowImg)a.onShowImg({index:q,img:S,thumb:aa,caption:n})}function ba(e,b){b.stopPropagation();b.preventDefault();var c=R+e;0>c&&(c=a.loop?s-
1:0);c>s-1&&(c=a.loop?0:s-1);ka(x.eq(c),b,!1)}function bb(){clearTimeout(Qa);Qa=setTimeout(function(){P(!0)},50)}function cb(){t.css({overflow:a.resize?"hidden":""});if(d)window[(a.resize?"add":"remove")+"EventListener"]("orientationchange",bb,!1);else fa[a.resize?"bind":"unbind"]("resize",bb)}t.data({ini:!0});var Aa,ja=(new Date).getTime();c("a",t).live("click",function(a){a.preventDefault()});var U,Oa=a.data&&"object"==typeof a.data;U=Oa?c(a.data).filter(function(){return this.img}):t.children().filter(function(){var a=
c(this);return(a.is("a")&&a.children("img").size()||a.is("img"))&&(a.attr("href")||a.attr("src")||a.children().attr("src"))});var s=U.size();t.data({size:s});if(a.startImg>s-1||"number"!=typeof a.startImg)a.startImg=0;var y=[];U.each(function(b){if(Oa)y[b]={imgHref:this.img,thumbSrc:this.thumb};else{var d=c(this);y[b]={imgHref:d.attr("href"),imgSrc:d.attr("src"),thumbSrc:d.children().attr("src")};if(a.caption)this.caption=d.attr("alt")||d.children().attr("alt")}});t.html("").addClass(b+" "+(a.vertical?
b+"_vertical":b+"_horizontal"));if(a.touchStyle)a.loop=!1;else if(!a.arrows)a.loop=!0;var ia=[],l,xa,v=a.width,u=a.height,Y,Wa,wa=!1,ab,Da=!1,Za;if(a.touchStyle)var Ka=0,Xa,sa=!1,pa=!1,db;if(a.thumbs&&a.thumbsPreview)var ea=!1,Ea=!1,Ga=!1,ha=!1,eb,Ma=!1,Ha=0;var F,za,O,Ia,E,Z;a.vertical?(F="top",za="left",O="pageY",Ia="pageX",E="height",Z="width"):(F="left",za="top",O="pageX",Ia="pageY",E="width",Z="height");var r=c('<div class="'+b+'__wrap"></div>').appendTo(t),J=c('<div class="'+b+'__shaft"></div>').appendTo(r);
f(r);f(J);var L=c('<div class="'+b+'__state"></div>').appendTo(J),Ca,va=0,Ya;if(d)t.addClass(b+"_touch"),a.shadows=!1;a.touchStyle?(r.addClass(b+"__wrap_style_touch"),a.shadows&&r.append('<i class="'+b+"__shadow "+b+'__shadow_prev"></i><i class="'+b+"__shadow "+b+'__shadow_next"></i>')):r.addClass(b+"__wrap_style_fade");K&&t.addClass(b+"_csstransitions");if(a.arrows){var ta,ca;a.vertical?(ta="&#9650;",ca="&#9660;"):(ta="&#9668;",ca="&#9658;");var ga=c('<i class="'+b+"__arr "+b+'__arr_prev">'+ta+'</i><i class="'+
b+"__arr "+b+'__arr_next">'+ca+"</i>").appendTo(r),qa=ga.eq(0),ra=ga.eq(1);if(!d){if(a.touchStyle&&a.pseudoClick&&a.arrows||!a.touchStyle&&a.arrows){var fb,gb,lb=function(){clearTimeout(gb);gb=setTimeout(function(){var e=fb>=l/2;ra[!e?"removeClass":"addClass"](b+"__arr_hover");qa[e?"removeClass":"addClass"](b+"__arr_hover");a.touchStyle||J.css({cursor:e&&ra.data("disabled")||!e&&qa.data("disabled")?"default":""})},10)};r.mousemove(function(a){fb=a[O]-r.offset()[F];lb()})}var Ra=!1,Sa,mb=function(){Ra=
!0;clearTimeout(Sa);ga.css(z(0));r.removeClass(b+"__wrap_mouseout");setTimeout(function(){ga.css(z(a.transitionDuration));setTimeout(function(){r.addClass(b+"__wrap_mouseover")},1)},1)},hb=function(){clearTimeout(Sa);Sa=setTimeout(function(){!sa&&!Ra&&r.removeClass(b+"__wrap_mouseover").addClass(b+"__wrap_mouseout")},3*a.transitionDuration)};r.mouseenter(function(){mb()});r.mouseleave(function(){Ra=!1;hb()})}}var S,R,x=c();U.each(function(){var a=c('<div class="'+b+'__frame" style="visibility: hidden;"></div>');
x=x.add(a)});if(a.thumbs){var B=a.thumbSize;B||(B=a.vertical?64:48);var aa,$a=0,C=c('<div class="'+b+'__thumbs" style="visibility: hidden;"></div>').appendTo(t),M;a.thumbsPreview&&(M=B+2*a.thumbMargin,C.addClass(b+"__thumbs_previews").css(Z,M));var o=c('<div class="'+b+'__thumbs-shaft"></div>').appendTo(C);if(a.thumbsPreview){var G=0,T=void 0;a.shadows&&c('<i class="'+b+"__shadow "+b+'__shadow_prev"></i><i class="'+b+"__shadow "+b+'__shadow_next"></i>').appendTo(C);ta=a.thumbMargin;ca={};ca[Z]=B-
(j?0:2*a.thumbBorderWidth);ca[za]=ta;ca.borderWidth=a.thumbBorderWidth;var da=c('<i class="'+b+'__thumb-border"></i>').hide().css(ca).appendTo(o)}U.each(function(){var e;if(a.thumbsPreview){e=c('<div class="'+b+'__thumb"></div>');var d={};d[Z]=B;d.margin=a.thumbMargin;e.css(d)}else e=c('<i class="'+b+'__thumb"><i class="'+b+'__thumb__dot"></i></i>');e.appendTo(o)});var H=c("."+b+"__thumb",t);if(a.thumbsPreview)var nb=function(e,d,f,h,g,j){d=c(e);h=a.vertical?Math.round(1E3*(B/h)):Math.round(B*h/1E3);
Mdrnzr.canvas?(d.remove(),d=c('<canvas class="'+b+'__thumb__img"></canvas>'),d.appendTo(H.eq(j))):d.addClass(b+"__thumb__img");f={};f[E]=h;f[Z]=B;d.attr(f).css(f).css({visibility:"visible"});Mdrnzr.canvas&&(d[0].getContext("2d"),d[0].getContext("2d").drawImage(e,0,0,a.vertical?B:h,a.vertical?h:B));G+=h+a.thumbMargin-(B+a.thumbMargin);o.css(E,G);f[Z]=null;H.eq(j).css(f).data(f);la()},Ta=function(a){!ha&&!ea&&!pa&&!Da?(a||(a=0),ma(a,H.eq(a),nb,"thumb"),setTimeout(function(){a+1<s&&Ta(a+1)},50)):setTimeout(function(){Ta(a)},
100)}}if(a.caption){var Pa=c('<p class="'+b+'__caption"></p>');Pa.appendTo(t)}var La=[];v&&u&&P();ka(x.eq(a.startImg),!1,!1,!0);a.thumbs&&a.thumbsPreview&&Ta(0);a.thumbs&&(a.thumbColor&&!a.thumbsPreview&&H.children().css({backgroundColor:a.thumbColor}),a.thumbsBackgroundColor&&C.css({backgroundColor:a.thumbsBackgroundColor}),a.thumbsPreview&&a.thumbBorderColor&&da.css({borderColor:a.thumbBorderColor}));a.backgroundColor&&r.add(x).css({backgroundColor:a.backgroundColor});a.arrowsColor&&a.arrows&&ga.css({color:a.arrowsColor});
var Qa=!1;cb();t.bind("showimg",function(b,c){if(c>s-1||"number"!=typeof c)c=0;(!a.touchStyle||!pa)&&ka(x.eq(c),b,!1)});t.bind("rescale",function(b,c,d,h){c&&(v=c);d&&(u=d);Y=1E3*(v/u);a.resize=h;P(!0,!h);cb();clearTimeout(Qa)});if(a.thumbs){var Ua=function(a){a.stopPropagation();if(!c(this).data("disabled")){var b=H.index(c(this)),d=a[O]-C.offset()[F];ka(x.eq(b),a,d)}};H.bind("click",Ua)}a.arrows&&(qa.click(function(a){c(this).data("disabled")||ba(-1,a)}),ra.click(function(a){c(this).data("disabled")||
ba(1,a)}));!a.touchStyle&&!d&&r.click(function(b){var c=b[O]-r.offset()[F]>=l/2;!b.shiftKey&&c&&a.arrows||b.shiftKey&&!c&&a.arrows||!a.arrows&&!b.shiftKey?ba(1,b):ba(-1,b)});if(a.touchStyle||d||a.thumbs&&a.thumbsPreview)var ib=function(b,c,f,h){function g(h){if((d||2>h.which)&&S){var g=function(){r=(new Date).getTime();n=m;t=l;o=[[r,m]];clearTimeout(b.data("backAnimate"));K?b.css(z(0)):b.stop();k=b.position()[F];b.css(A(k,a.vertical));q=k;c()};if(d)if(d&&1==h.targetTouches.length)m=h.targetTouches[0][O],
l=h.targetTouches[0][Ia],g(),b[0].addEventListener("touchmove",j,!1),b[0].addEventListener("touchend",p,!1);else{if(d&&1<h.targetTouches.length)return!1}else m=h[O],h.preventDefault(),g(),ua.mousemove(j),ua.mouseup(p)}}function j(c){function h(){c.preventDefault();u=(new Date).getTime();o.push([u,m]);var d=n-m;k=q-d;k>b.data("maxPos")?(k=Math.round(k+(b.data("maxPos")-k)/1.5),B="left"):k<b.data("minPos")?(k=Math.round(k+(b.data("minPos")-k)/1.5),B="right"):B=!1;a.touchStyle&&b.css(A(k,a.vertical));
f(k,d,B)}d?d&&1==c.targetTouches.length&&(m=c.targetTouches[0][O],l=c.targetTouches[0][Ia],C?y&&h():(-5<=Math.abs(m-n)-Math.abs(l-t)&&(y=!0,c.preventDefault()),C=!0)):(m=c[O],h())}function p(a){if(!d||!a.targetTouches.length){C=y=!1;d?(b[0].removeEventListener("touchmove",j,!1),b[0].removeEventListener("touchend",p,!1)):(ua.unbind("mouseup"),ua.unbind("mousemove"));s=(new Date).getTime();var c=-k,g=s-W,f,l,w,n;for(i=0;i<o.length;i++)f=Math.abs(g-o[i][0]),0==i&&(l=f,w=s-o[i][0],n=o[i][1]),f<=l&&(l=
f,w=o[i][0],n=o[i][1]);g=n-m;f=0<=g;w=s-w;h(c,w,w<=W,s-x,f===v,g,a);x=s;v=f}}var k,m,l,n,t,q,r,o=[],u,v,s,x=0,y=!1,C=!1,B=!1;d?b[0].addEventListener("touchstart",g,!1):b.mousedown(g)};if(a.touchStyle||d)ib(J,function(){pa=!0},function(c,f,l){clearTimeout(db);sa||(a.shadows&&r.addClass(b+"__wrap_shadow"),d||J.addClass(b+"__shaft_grabbing"),sa=!0);a.shadows&&(l?(c="left"==l?"right":"left",r.addClass(b+"__wrap_shadow_no-"+l).removeClass(b+"__wrap_shadow_no-"+c)):a.shadows&&r.removeClass(b+"__wrap_shadow_no-left "+
b+"__wrap_shadow_no-right"))},function(e,f,j,h,g,n,p){pa=!1;db=setTimeout(function(){sa=!1;!d&&a.arrows&&hb()},W);d||J.removeClass(b+"__shaft_grabbing");a.shadows&&r.removeClass(b+"__wrap_shadow");var g=h=!1,k=c(p.target),m=k.filter("a");m.length||k.parents("a");if(a.touchStyle)if(sa){j&&(-10>=n?h=!0:10<=n&&(g=!0));j=N;k=Math.round(e/(l+a.margin));if(h||g){var f=-n/f,n=Math.round(-e+250*f),o;h?(k=Math.ceil(e/(l+a.margin))-1,e=-k*(l+a.margin),n>e&&(o=Math.abs(n-e),j=Math.abs(j/(250*f/(Math.abs(250*
f)-0.97*o))),o=e+0.03*o)):g&&(k=Math.floor(e/(l+a.margin))+1,e=-k*(l+a.margin),n<e&&(o=Math.abs(n-e),j=Math.abs(j/(250*f/(Math.abs(250*f)-0.97*o))),o=e-0.03*o))}0>k&&(k=0,o=!1,j=N);k>s-1&&(k=s-1,o=!1,j=N);ka(x.eq(k),p,!1,!1,j,o)}else m.length?document.location=m.attr("href"):a.pseudoClick&&!d&&f<W?(o=p[O]-r.offset()[F]>=l/2,!p.shiftKey&&o&&a.arrows||p.shiftKey&&!o&&a.arrows||!a.arrows&&!p.shiftKey?ba(1,p):ba(-1,p)):ka(S,p);else 0==n&&m.length?document.location=m.attr("href"):0<=n?ba(1,p):0>n&&ba(-1,
p)}),a.touchStyle&&a.thumbs&&a.thumbsPreview&&ib(o,function(){Ea=ea=!0},function(a,b){!ha&&5<=Math.abs(b)&&(H.unbind("click",Ua),clearTimeout(eb),ha=!0);Fa(a)},function(a,b,c,d,g,f,j){ea=!1;eb=setTimeout(function(){ha=!1;H.bind("click",Ua)},W);var d=a=-a,k,g=2*N;Ga&&ha&&(V(0,!1,!1),Ga=!1);a>o.data("maxPos")?(d=o.data("maxPos"),g/=2):a<o.data("minPos")?(d=o.data("minPos"),g/=2):c&&(b=-f/b,d=Math.round(a+250*b),d>o.data("maxPos")?(k=Math.abs(d-o.data("maxPos")),g=Math.abs(g/(250*b/(Math.abs(250*b)-
0.96*k))),d=o.data("maxPos"),k=d+0.04*k):d<o.data("minPos")&&(k=Math.abs(d-o.data("minPos")),g=Math.abs(g/(250*b/(Math.abs(250*b)-0.96*k))),d=o.data("minPos"),k=d-0.04*k));j.altKey&&(g*=10);T=d;d!=a&&(oa(o,d,g,k),Fa(d))})}var d="ontouchstart"in document,K=Mdrnzr.csstransforms3d&&Mdrnzr.csstransitions,Q=c.browser.msie,j="CSS1Compat"!=document.compatMode&&Q,W=300,X=c.bez([0.1,0,0.25,1]),N=333,b="fotorama",fa=c(window),ua=c(document),P=[["data","array",null],["width","number",null],["height","number",
null],["startImg","number",0],["transitionDuration","number",N],["touchStyle","boolean",!0],["pseudoClick","boolean",!0],["loop","boolean",!1],["backgroundColor","string",null],["margin","number",5],["minPadding","number",10],["alwaysPadding","boolean",!1],["preload","number",3],["resize","boolean",!1],["zoomToFit","boolean",!0],["cropToFit","boolean",!1],["vertical","boolean",!1],["verticalThumbsRight","boolean",!1],["arrows","boolean",!0],["arrowsColor","string",null],["thumbs","boolean",!0],["thumbsBackgroundColor",
"string",null],["thumbColor","string",null],["thumbsPreview","boolean",!0],["thumbSize","number",null],["thumbMargin","number",5],["thumbBorderWidth","number",3],["thumbBorderColor","string",null],["caption","boolean",!1],["html","array",null],["onShowImg","function",null],["shadows","boolean",!0],["detachSiblings","boolean",!0]];c.fn[b]=function(b){"undefined"==typeof fotoramaDefaults&&(fotoramaDefaults={});var a=c.extend({},n(),c.extend({},fotoramaDefaults,b));this.each(function(){var b=c(this);
b.data("ini")||V(b,a)});return this};c(function(){c("."+b).each(function(){var d=c(this);d[b](n(d))})});var la=["-webkit-","-moz-","-o-","-ms-",""],jb="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAGoCAMAAAAQMBfHAAABtlBMVEX///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8cWrVBAAAAkXRSTlMAKUfghaPCZkAlPw7nsHiUy1w3VBcoLEoiNsAw/EbdoYNlGSYIFEUdDEgS2yENIMi9CnfkLhUnkhutW5/5gGSCAgsyaZlEsVA4yQQfAxM9Khh/AWEPm7nVEfNCtB4kNDnGSU+JM5dznmAtXkuQO3JnTlhiWp2nIwf2rtgcPha7UYxf4avt0DUGpjp1eXwvTBBjhUW/zwAABtxJREFUeF7tmtlXE0kUxm/2zr5gwhI3QiAsCXtCQFED4gKCIKIgoo7ruI0zOjNuo7Pva/7jqTq3iy9dOc05eclT/94434/qk6fbt74mK6GLF0N0EJF6PXKgUBcgAJfLzcJyHvkjz/VOXQhXXAWVX/Z4PCOdViHsEqgz1q+zAYHzyjKZdB4VxtEbIpiq16dEPuiShEkCIyGSoUhkSAh7Ik6bOYwx/BlUORhIJNYg1ILBErWP7Fm/5GzWGgxfcEsuDFPUz0StguFmDJo1hVmrEDKFEKX4iGjKKvTzEUY/tY/pjY00gkLy+z1r3n3M54tD+CIQ6O3Tc9+MSIqLi0UWlIH8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",
Va="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAAAAABWESUoAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAAJ0Uk5TAEQHIkixAAABv0lEQVQYGWXBQWcjcRzH4d+L+xpDRVRYETGXqt5W9FI9xFIVvfae64i11IgosfoGeqtaZawcwlgrlxE1Qvx99j+zk5lUn8fU6N48pmtc+vpwrZapNkgKGtvFUDVTJYgdH+xnJ6qYStEbn6wGKpm8wYZSHl+fS9Hl9w2l7bk8k9TP8HZ3oQ4mG7w8kmRSuKIyU6uzxHsLJZOm1CY6kuDFkqlfAE9vwH6kI0vADWRaAH86/Q2QR2p1MiCRdQrgVrrYAauuWjdAcWoTIAskjfGeAzWCDBjbHJipNMWbqxUDc/sFjFRZ4t2rMQJSy4EvqoQvgLvSQQ/IbQ8u0H+9DHg/Uy1w4MyBC1Q7eweynmp72FsO9HRw5YCXUJUusLUUGKlxj/dTlRGQ2hyI1UrwpirNgIWNgXWgRvCMN5a3BiZ2WgDf1OqugN2FNAaKjikBso5aUQ5s+p01sJBp4IBHHfm6B9InoOjLpBjvQUcm1KaSSWGKtzxRa0ZlFUomKcrx1mMdhHc7vKwvyeRdbCn9jS+H0vl1nFPaDOWZStGaT35HKpkqJz8cH7g4VMVUGz4WNHbJUDVTo3uTvKaOLF3enqrxD+aQUnwgKhDtAAAAAElFTkSuQmCC",
ma=new Image,Ja=!0;ma.onerror=function(){if(1!=this.width||1!=this.height)Ja=!1};ma.src=Va})(jQuery);