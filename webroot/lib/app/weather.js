Ext.require('Date');
Ext.require('Ext.button.Split');
Ext.require('Ext.chart.axis.*');
Ext.require('Ext.chart.Chart');
Ext.require('Ext.chart.series.*');
Ext.require('Ext.container.Viewport');
Ext.require('Ext.data.JsonStore');
Ext.require('Ext.fx.*');
Ext.require('Ext.layout.container.Border');
Ext.require('Ext.picker.Date');
Ext.require('Ext.picker.Month');
Ext.require('Ext.resizer.*');
Ext.require('Ext.tab.Panel');
Ext.require('Osm.controller.CurrentWeather');
Ext.require('Osm.controller.Temperature');
Ext.require('Osm.chart.Temperature');
Ext.require('Osm.model.CurrentWeather');
Ext.require('Osm.model.Temperature');
Ext.require('Osm.store.CurrentWeather');
Ext.require('Osm.store.Temperature');
Ext.require('Osm.view.CurrentWeather');
Ext.require('Osm.view.Temperature');

Ext.onReady(function(){
    
   var loadingMask = Ext.get('loading-mask');
    loadingMask.fadeOut({
        duration: 1000,
        remove: true
    });

    Ext.application({
        name: 'Osm',
        appFolder: '/lib/app',

        controllers: [
        'CurrentWeather',
        'Temperature'
        ],

        views: [
        'Temperature'
        ],

        launch: function() {
            Ext.create('Ext.container.Viewport', {
                border: false,
                layout: {
                    type: 'border',
                    padding: 0
                },
                items: [{
                    contentEl: 'header-div',
                    region: 'north',
                    style: {
                        margin: '3px'
                    },
                    xtype: 'panel'
                },{
                    region: 'center',
                    items: [{
                        xtype: 'currentweatherpanel'
                    },{
                        xtype: 'temperaturepanel'
                    }],
                    xtype: 'tabpanel'
                }]
            });
        }

    });

});