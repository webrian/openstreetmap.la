Ext.define('Osm.view.CurrentWeather' ,{
    extend: 'Ext.container.Container',
    alias: 'widget.currentweatherpanel',

    requires: [
    'Osm.store.CurrentWeather',
    'Osm.model.CurrentWeather'
    ],

    config: {
        store: null
    },

    layout: 'border',

    title: 'Daily Weather',

    initComponent: function(){

        var store = Ext.create('Osm.store.CurrentWeather');

        var temperatureChart = Ext.create('Ext.chart.Chart',{
            animate: true,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['temperature_celsius', 'dew_point_celsius'],
                grid: true,
                minimum: 0,
                maximum: 40,
                title: 'Temperature'
            },{
                type: 'Category',
                position: 'bottom',
                fields: ['time'],
                title: 'Time'
            }],
            legend: {
                position: 'bottom'
            },
            series: [{
                type: 'line',
                axis: 'left',
                highlight: true,
                showMarkers: false,
                smooth: true,
                style: {
                    stroke: '#ee0000'
                },
                tips: {
                    trackMouse: true,
                    width: 110,
                    height: 25,
                    renderer: function(storeItem, item) {
                        var v = Ext.util.Format.number(storeItem.get('temperature_celsius'), '0');
                        var t = storeItem.get('time');
                        this.setTitle(t + ": " + v + "°");
                    }
                },
                title: 'Temperature [Celsius]',
                yField: 'temperature_celsius',
                xField: 'time'
            },{
                axis: 'left',
                highlight: true,
                showMarkers: false,
                smooth: true,
                tips: {
                    trackMouse: true,
                    width: 110,
                    height: 25,
                    renderer: function(storeItem, item) {
                        var v = Ext.util.Format.number(storeItem.get('dew_point_celsius'), '0');
                        var t = storeItem.get('time');
                        this.setTitle(t + ": " + v + "°");
                    }
                },
                title: 'Dew Point [Celsius]',
                type: 'line',
                yField: 'dew_point_celsius',
                xField: 'time'
            }],
            store: store,
            flex: 1
        });

        var pressureChart = Ext.create('Ext.chart.Chart',{
            flex: 1,
            animate: true,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['pressure'],
                grid: true,
                minimum: 1000,
                maximum: 1020,
                title: 'Pressure'
            },{
                type: 'Category',
                position: 'bottom',
                fields: ['time'],
                title: 'Time'
            }],
            legend: {
                position: 'bottom'
            },
            series: [{
                axis: 'left',
                highlight: true,
                showMarkers: false,
                smooth: true,
                tips: {
                    trackMouse: true,
                    width: 150,
                    height: 25,
                    renderer: function(storeItem, item) {
                        var t = storeItem.get('time');
                        var v = Ext.util.Format.number(storeItem.get('pressure'), '0.00');
                        this.setTitle(t + ": " + v + " mbar");
                    }
                },
                title: 'Pressure [mbar]',
                type: 'line',
                yField: 'pressure',
                xField: 'time'
            }],
            store: store,
            title: 'Pressure'
        });

        var humidityChart = Ext.create('Ext.chart.Chart',{
            animate: true,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['humidity'],
                minimum: 20,
                maximum: 100,
                grid: true,
                title: 'Humidity'
            },{
                type: 'Category',
                position: 'bottom',
                fields: ['time'],
                title: 'Time'
            }],
            flex: 1,
            legend: {
                position: 'bottom'
            },
            series: [{
                axis: 'left',
                highlight: true,
                showMarkers: false,
                smooth: true,
                tips: {
                    trackMouse: true,
                    width: 115,
                    height: 25,
                    renderer: function(storeItem, item) {
                        var v = Ext.util.Format.number(storeItem.get('humidity'), '0');
                        var t = storeItem.get('time');
                        this.setTitle(t + ": " + v + "%");
                    }
                },
                title: 'Humidity [%]',
                type: 'line',
                yField: 'humidity',
                xField: 'time'
            }],
            store: store
        });

        var windspeedChart = Ext.create('Ext.chart.Chart',{
            animate: true,
            axes: [{
                type: 'Numeric',
                position: 'left',
                fields: ['wind_speed'],
                grid: true,
                title: 'Wind Speed'
            },{
                type: 'Category',
                position: 'bottom',
                fields: ['time'],
                title: 'Time'
            }],
            flex: 1,
            legend: {
                position: 'bottom'
            },
            series: [{
                axis: 'left',
                highlight: true,
                showMarkers: false,
                smooth: true,
                tips: {
                    trackMouse: true,
                    width: 140,
                    height: 25,
                    renderer: function(storeItem, item) {
                        var t = storeItem.get('time');
                        var v = Ext.util.Format.number(storeItem.get('wind_speed'), '0.000');
                        this.setTitle(t + ": " + v + " m/s");
                    }
                },
                title: 'Wind Speed [m/s]',
                type: 'line',
                yField: 'wind_speed',
                xField: 'time'
            }],
            store: store
        });

        this.items= [{
            autoScroll: true,
            defaults: {
                style: {
                    margin: '5px'
                }
            },
            items: [{
                contentEl: 'intro-div',
                xtype: 'container'
            },{
                itemId: 'datepicker',
                minDate: new Date(2003,00,01),
                maxDate: new Date(),
                xtype: 'datepicker'
            }],
            layout: {
                align: 'stretch',
                type: 'vbox'
            },
            region: 'west',
            width: 200,
            xtype: 'container'
        },{
            region: 'center',
            layout: {
                align: 'stretch',
                type: 'vbox'
            },
            items: [{
                flex: 1,
                layout: {
                    align: 'stretch',
                    type: 'hbox'
                },
                items: [temperatureChart, humidityChart],
                xtype: 'container'
            },{
                flex: 1,
                layout: {
                    align: 'stretch',
                    type: 'hbox'
                },
                items: [pressureChart, windspeedChart],
                xtype: 'container'
            }],
            xtype: 'container'
        }];

        this.setStore(store);

        //this.charts.push(temperatureChart, humidityChart, pressureChart, windspeedChart);

        this.callParent(arguments);
    }

});
