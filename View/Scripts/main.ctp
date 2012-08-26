L.Control.Status=L.Control.Attribution.extend({options:{tpl:null},initialize:function(a){L.Util.setOptions(this,a)},onAdd:function(a){this._container=L.DomUtil.create("div","leaflet-control-status");this._map=a;this._attributions={};this._update()},getPosition:function(){return L.Control.Position.TOP_RIGHT},setText:function(a){this.options.tpl.overwrite(this.getContainer(),[a])}});L.OsmMarker=L.Marker.extend({options:{mouseoverText:"Drag to change route or double click to remove",mouseoutText:"",draggable:true,ctrl:null},initialize:function(b,a){L.Util.setOptions(this,a);this._latlng=b;this.on("mouseover",function(c){this.options.ctrl.setText(this.options.mouseoverText)});this.on("mouseout",function(c){this.options.ctrl.setText(this.options.mouseoutText)})},onRemove:function(a){this._removeIcon();a.off("viewreset",this._reset,this);this.options.ctrl.setText(this.options.mouseoutText)}});
/*!
 * Ext JS Library 3.1.1
 * Copyright(c) 2006-2010 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){var D=null;var G=null;var C=new Array();var u=null;var J=null;var l=initLang;var M='<a class="external" href="http://www.openstreetmap.org/edit?lat={lat}&lon={lon}&zoom={zoom}">edit the map</a>';var N=new Ext.Template(M);N.compile();var R=Ext.DomHelper.createTemplate({html:"{0}",tag:"div"});R.compile();Ext.Ajax.on("beforerequest",function(i){E.setText("Loading ...")},this);Ext.Ajax.on("requestcomplete",function(i){E.setText("")},this);var x='CC-By-SA <a href="http://www.osm-tools.org">www.osm-tools.org</a>, <a href="http://www.openstreetmap.org">OpenStreetMap</a>';var d="http://{s}.laostile.osm-tools.org/osm_en/{z}/{x}/{y}.png";var n=new L.TileLayer(d,{attribution:x,maxZoom:18});var t="http://{s}.laostile.osm-tools.org/osm_th/{z}/{x}/{y}.png";var K=new L.TileLayer(t,{attribution:x,maxZoom:18});var B={en:n,lo:K};Ext.encode(B);var r=new L.Map("map");r.addLayer(B[l]);var E=new L.Control.Status({tpl:R});r.addControl(E);E.setText("");var Q=new Ext.ux.LocationComboBox({listeners:{select:function(V,i,T){var W=Fgh.decode(i.data.hash);var U=new L.LatLng(W.lat,W.lon);F(U,true)}},map:r,width:350});var O=[Q,"->",{handler:function(ai){var ae=r.getCenter();var af=r.getZoom();var ac="";if(u){var ag=u.getLatLng();ac="&mlat="+ag.lat+"&mlon="+ag.lng}var W="";if(D){var Z=D.getLatLng();var aa=Fgh.encode(Z.lat,Z.lng,52);W="&start="+aa}var ah="";if(G){var ab=G.getLatLng();var V=Fgh.encode(ab.lat,ab.lng,52);ah="&dest="+V}var ad="";if(C.length>0){ad+="&via=";for(var Y=0;Y<C.length;Y++){var U=C[Y].getLatLng();var X=Fgh.encode(U.lat,U.lng,52);ad+=X;if(Y<(C.length-1)){ad+=","}}}var T="&lang="+l;window.location.href="/?lat="+ae.lat+"&lon="+ae.lng+"&zoom="+af+ac+W+ah+ad+T},icon:"/img/actionPermaLink.png",listeners:{mouseover:function(T,i){E.setText("Permanent link to current map view")},mouseout:function(T,i){E.setText("")}}},{enableToggle:true,handler:function(i){r.removeLayer(B.en);r.addLayer(B.lo);l="lo"},listeners:{mouseover:function(T,i){E.setText("\u0e9e\u0eb2\u0eaa\u0eb2\u0ea5\u0eb2\u0ea7")},mouseout:function(T,i){E.setText("")}},icon:"/img/lo.png",pressed:l=="lo",toggleGroup:"languageToggleGroup"},{enableToggle:true,handler:function(i){r.removeLayer(B.lo);r.addLayer(B.en);l="en"},listeners:{mouseover:function(T,i){E.setText("English")},mouseout:function(T,i){E.setText("")}},icon:"/img/us.png",pressed:l=="en",toggleGroup:"languageToggleGroup"}];var w=new Ext.ux.MapPanel({center:initCenter,contentEl:"map",editEl:"edit-link",editTpl:N,id:"map-tabpanel",map:r,tbar:{defaults:{xtype:"button"},items:O,xtype:"toolbar"},title:"Map",zoom:initZoom});var g=new Ext.TabPanel({activeTab:"map-tabpanel",items:[w,{autoScroll:true,contentEl:"edit-tab",id:"edit-tabpanel",title:"Edit",xtype:"panel"},{autoScroll:true,contentEl:"downloads-tab",id:"downloads-tabpanel",title:"Downloads",xtype:"panel"},{autoScroll:true,contentEl:"about-tab",id:"about-tabpanel",title:"About this page",xtype:"panel"}],listeners:{afterrender:function(i){i.setActiveTab(Ext.ux.activeTab+"-tabpanel")}},region:"center"});var H=L.Icon.extend({iconUrl:"/img/startmarker.png"});var A=L.Icon.extend({iconUrl:"/img/endmarker.png"});var P=L.Icon.extend({iconUrl:"/img/viamarker.png",shadowUrl:"/img/viamarker-shadow.png",iconSize:new L.Point(12,12),shadowSize:new L.Point(12,12),iconAnchor:new L.Point(6,6),popupAnchor:new L.Point(-6,-6)});var q=new Ext.ux.LocationComboBox({emptyText:"Search village, point of interest, etc.",listeners:{select:function(V,i,T){var W=Fgh.decode(i.data.hash);var U=new L.LatLng(W.lat,W.lon);a(U,true)}},map:r});var h=new Ext.ux.LocationComboBox({emptyText:"Search village, point of interest, etc.",listeners:{select:function(V,i,T){var W=Fgh.decode(i.data.hash);var U=new L.LatLng(W.lat,W.lon);p(U,true)},scope:this},map:r});var k=Ext.DomHelper;var v=k.createTemplate({tag:"tr",id:"row{0}",children:[{tag:"td",html:"{0}.",cls:"{1}"},{tag:"td",html:"{2} {3}",cls:"{1}"},{tag:"td",html:"{4}",cls:"{1}"}]});var m=k.createTemplate({tag:"div",html:"Distance: <b>{0} km</b><br>Duration: <b>{1} h {2} mins</b>"});var y=new Ext.Panel({autoScroll:true,region:"center",contentEl:"route-result-panel",id:"routingpanel",baseCls:"x-panel",ctCls:"x-panel-mc"});var z=new Ext.Panel({items:[{align:"stretch",height:350,items:[{baseCls:"x-panel",contentEl:"sidepanel-header",ctCls:"x-panel-mc",width:250,xtype:"panel"},{bodyStyle:"padding: 10px 0px 0px 10px",buttonAlign:"right",defaults:{width:230},hideLabels:true,items:[{html:"<h1>Get directions</h1>",style:"padding: 5px",xtype:"label"},{hideLabel:true,items:[q,{handler:function(T,i){a(r.getCenter(),false);q.setLocationValue(r.getCenter())},iconCls:"startmarker",tooltip:"Add start",tooltipType:"title",xtype:"button"}],width:"auto",xtype:"compositefield"},{hideLabel:true,items:[h,{handler:function(T,i){p(r.getCenter(),false);h.setLocationValue(r.getCenter())},iconCls:"endmarker",tooltip:"Add destination",tooltipType:"title",xtype:"button"}],width:"auto",xtype:"compositefield"},{handler:function(V,T){s();b();for(var U=0;U<C.length;U++){r.removeLayer(C[U]);r.closePopup()}C=new Array();q.clearValue();h.clearValue();j(false)},text:"Clear",width:100,xtype:"button"}],width:250,xtype:"form"},{baseCls:"x-panel",contentEl:"route-summary-panel",ctCls:"x-panel-mc",width:250,xtype:"panel"}],layout:"vbox",region:"north",xtype:"panel"},y],layout:"border",region:"west",width:250});var S=new Ext.Viewport({layout:"border",items:[z,g]});S.render("main");if(initStart){a(initStart,false);q.setLocationValue(initStart)}if(initDest){p(initDest,false);h.setLocationValue(initDest)}if(initVias){for(var I=0;I<initVias.length;I++){e(initVias[I])}}if(initMarker){F(initMarker,false);Q.setLocationValue(u.getLatLng())}function a(T,i){if(D){r.removeLayer(D)}D=new L.OsmMarker(T,{ctrl:E,icon:new H()});D.on("dblclick",function(U){s();j(false)});D.on("dragend",function(U){j(false);q.setLocationValue(this.getLatLng())});r.addLayer(D);j(i)}function s(){if(D){r.removeLayer(D)}D=null;q.clearValue()}function p(T,i){if(G){r.removeLayer(G)}G=new L.OsmMarker(T,{ctrl:E,icon:new A()});G.on("dblclick",function(U){b();j(false)});G.on("dragend",function(U){j(false);h.setLocationValue(this.getLatLng())});r.addLayer(G);j(i)}function b(){if(G){r.removeLayer(G)}G=null;h.clearValue()}function e(T){var i=new L.OsmMarker(T,{ctrl:E,draggable:true,icon:new P()});i.on("dragend",function(U){j(false)});i.on("dblclick",function(U){C.remove(this);r.removeLayer(this);j(false)});C.push(i);r.addLayer(i);j(false)}function f(){for(var T=0;T<C.length;T++){r.removeLayer(C[T])}C=new Array()}function F(i,T){if(u){r.removeLayer(u)}u=new L.OsmMarker(i,{ctrl:E,mouseoverText:"Double click to remove"});u.on("dblclick",function(U){o()});u.on("dragend",function(U){Q.setLocationValue(u.getLatLng())});r.addLayer(u);if(T){r.panTo(i)}}function o(){if(u){r.removeLayer(u);u=null}Q.clearValue()}function c(){var U=new Array();for(var T=0;T<C.length;T++){U.push(Fgh.encode(C[T].getLatLng().lat,C[T].getLatLng().lng,52))}return U.join(",")}function j(i){if(D&&G){Ext.Ajax.request({failure:function(){alert("No route found")},method:"GET",params:{start:Fgh.encode(D.getLatLng().lat,D.getLatLng().lng,52),dest:Fgh.encode(G.getLatLng().lat,G.getLatLng().lng,52),via:c()},success:function(Z){if(J){r.removeLayer(J)}var Y=Ext.decode(Z.responseText);var U=[];Ext.each(Y.features[0].geometry.coordinates,function(al,aj,ak){U.push(new L.LatLng(al[1],al[0]))});J=new L.Polyline(U,{color:"blue"});J.on("mouseover",function(aj){E.setText("Click to add new via point")});J.on("mouseout",function(aj){E.setText("")});J.on("click",function(aj){e(aj.latlng)});r.addLayer(J);if(i){r.fitBounds(new L.LatLngBounds(U))}var V=k.overwrite("route-result-panel",{tag:"table",cls:"result-table"});var aa=Y.features[0].properties.route_instructions;var ae=0;for(var ad=0;ad<aa.length;ad++){var ai=(ad%2==0)?"route-instructions-even":"";var X=(aa[ad][1]!="")?"on "+aa[ad][1]:"";var W=aa[ad][5];ae+=parseInt(W);var ag=W>1000?Number(parseInt(W/10)/100)+" km":W+" m";v.append(V,[(ad+1),ai,aa[ad][0],X,ag])}var af=Y.features[0].properties.total_time;var ac=parseInt(af/3600);var ab=parseInt(((af/3600)-ac)*60);var T=Number(parseInt(ae/10)/100);var ah=k.overwrite("route-summary-panel",{tag:"div"});m.append(ah,[T,ac,ab])},url:"route"})}else{f();k.overwrite("route-summary-panel","");k.overwrite("route-result-panel","");if(J){r.removeLayer(J)}}}});Ext.namespace("Ext.ux");Ext.ux.LocationComboBox=Ext.extend(Ext.form.ComboBox,{map:null,initComponent:function(){var a={emptyText:"Search village, hotel, restaurant, shop, etc.",store:new Ext.data.JsonStore({proxy:new Ext.data.HttpProxy({method:"GET",url:"search"}),root:"data",idProperty:"hash",fields:["name","hash","feature"]}),valueField:"hash",displayField:"name",iconClsField:"feature",typeAhead:false,minChars:3,mode:"remote",queryParam:"q",hideTrigger:true,selectOnFocus:true,tpl:'<tpl for="."><div class="x-combo-list-item"><table><tbody><tr><td><div class="x-poi-{feature} x-icon-combo-icon"></div></td><td>{name}</td></tr></tbody></table></div></tpl>',xtype:"combo"};Ext.apply(this,a);Ext.ux.LocationComboBox.superclass.initComponent.call(this)},setLocationValue:function(b){var a="Lat: "+Math.round(b.lat*10000)/10000+", Lon: "+Math.round(b.lng*10000)/10000;this.setValue(a)}});Ext.reg("ux_locationcombo",Ext.ux.LocationComboBox);Ext.ux.MapPanel=Ext.extend(Ext.Panel,{center:null,editTpl:null,editEl:null,map:null,zoom:null,initComponent:function(){var a={};Ext.applyIf(this,a);Ext.ux.MapPanel.superclass.initComponent.call(this)},afterRender:function(){var a=this.ownerCt.getSize();Ext.applyIf(this,a);Ext.ux.MapPanel.superclass.afterRender.call(this);this.map.setView(this.center,this.zoom);this.map.on("dragend",function(b){this.center=this.map.getCenter();this.updateTpl()},this);this.map.on("zoomend",function(b){this.zoom=this.map.getZoom();this.updateTpl()},this);this.updateTpl()},onResize:function(a,b){Ext.ux.MapPanel.superclass.onResize.call(this,a,b);if(this.map){this.map.setView(this.center,this.zoom);this.map.invalidateSize()}},setSize:function(c,a,b){Ext.ux.MapPanel.superclass.setSize.call(this,c,a,b);if(this.map){this.map.setView(this.center,this.zoom);this.map.invalidateSize()}},getMap:function(){return this.map},updateTpl:function(){if(this.editTpl){this.editTpl.overwrite(this.editEl,{zoom:this.map.getZoom(),lat:this.center.lat,lon:this.center.lng})}}});Ext.reg("mappanel",Ext.ux.MapPanel);(function(){var a="0123456789bcdefghjkmnpqrstuvwxyz";var d=[0,1,0,1,2,3,2,3,0,1,0,1,2,3,2,3,4,5,4,5,6,7,6,7,4,5,4,5,6,7,6,7];var b=[0,1,4,5,16,17,20,21,64,65,68,69,80,81,84,85,256,257,260,261,272,273,276,277,320,321,324,325,336,337,340,341];function f(g,h){return(a.indexOf(g.charAt(h))<<5)|(a.indexOf(g.charAt(h+1)))}function c(g){return d[g&31]|(d[(g>>6)&15]<<3)}function e(i){var g=0,h=0;while(i>0){low=i&255;g|=b[low]<<h;i>>=8;h+=16}return g}window.Fgh={decode:function(m){var h=m.length,k,j,l=0,g=0;if(h&1){j=(a.indexOf(m.charAt(h-1))<<5)}else{j=f(m,h-2)}g=(c(j))/32;l=(c(j>>1))/32;for(k=(h-2)&~1;k>=0;k-=2){j=f(m,k);g=(c(j)+g)/32;l=(c(j>>1)+l)/32}return{lat:180*(g-0.5),lon:360*(l-0.5)}},encode:function(p,h,s){p=p/180+0.5;h=h/360+0.5;var g="",m=Math.ceil(s/10),t,k,q,j,o,n;for(n=0;n<m;++n){p*=32;h*=32;t=Math.min(31,Math.floor(p));k=Math.min(31,Math.floor(h));p-=t;h-=k;q=e(t)|(e(k)<<1);j=q>>5;o=q&31;g+=a.charAt(j)+a.charAt(o)}g=g.substr(0,Math.ceil(s/5));return g},checkValid:function(g){return !!g.match(/^[0-9b-hjkmnp-z]+$/)}}})();