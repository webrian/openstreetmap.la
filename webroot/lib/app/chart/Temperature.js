Ext.define('Osm.chart.Temperature', {
    extend: 'Ext.chart.Chart',
    alias: 'widget.temperaturechart',

    requires: [
    'Osm.model.Temperature',
    'Osm.store.Temperature'
    ],
    
    animate: true,
    
    legend: {
        position: 'bottom'
    },
    
    region: 'center',
        
    title: 'Temperature',


    initComponent: function() {

        var temperatureStore = Ext.create('Osm.store.Temperature');

        this.store = temperatureStore;
    
        this.axes = [{
            type: 'Numeric',
            position: 'left',
            fields: ['t', 'min', 'max'],
            grid: true,
            minimum: 0,
            maximum: 40,
            title: 'Temperature in Celsius'
        },{
            type: 'Category',
            position: 'bottom',
            fields: ['day'],
            title: 'Date / Week in Year'
        }];


        this.series = [{
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
                width: 115,
                height: 25,
                renderer: function(storeItem, item) {
                    var t = storeItem.get('day');
                    var v = Ext.util.Format.number(storeItem.get('max'), '0.00');
                    this.setTitle(t + ": " + v + "°");
                }
            },
            title: 'Highest Temp per Day / Week',
            yField: 'max',
            xField: 'day'
        },{
            axis: 'left',
            highlight: true,
            showMarkers: false,
            smooth: true,
            tips: {
                trackMouse: true,
                width: 115,
                height: 25,
                renderer: function(storeItem, item) {
                    var t = storeItem.get('day');
                    var v = Ext.util.Format.number(storeItem.get('t'), '0.00');
                    this.setTitle(t + ": " + v + "°");
                }
            },
            title: 'Average Temp per Day / Week',
            type: 'line',
            yField: 't',
            xField: 'day'
        },{
            axis: 'left',
            highlight: true,
            showMarkers: false,
            smooth: true,
            style: {
                stroke: '#0000ff'
            },
            tips: {
                trackMouse: true,
                width: 115,
                height: 25,
                renderer: function(storeItem, item) {
                    var t = storeItem.get('day');
                    var v = Ext.util.Format.number(storeItem.get('min'), '0.00');
                    this.setTitle(t + ": " + v + "°");
                }
            },
            title: 'Lowest Temp per Day / Week',
            type: 'line',
            yField: 'min',
            xField: 'day'
        }];
        
        this.callParent(arguments);
    }

});