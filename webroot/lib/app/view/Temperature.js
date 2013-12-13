Ext.define('Osm.view.Temperature' ,{
    extend: 'Ext.panel.Panel',
    alias: 'widget.temperaturepanel',

    requires: [
    'Osm.chart.Temperature'
    ],

    layout: 'border',
    title: 'Temperature Trends',
    
    items: [{
        region: 'center',
        xtype: 'temperaturechart'
    },{
        autoScroll: true,
        defaults: {
            style: {
                margin: '5px'
            }
        },
        items: [{
            contentEl: 'temperature-trends-div',
            xtype: 'container'
        },{
            html: 'Start Date:',
            xtype: 'container'
        },{
            itemId: 'startpicker',
            // Month is zero-based
            minDate: new Date(2003,00,01),
            maxDate: new Date(),
            showToday: false,
            xtype: 'datepicker'
        },{
            html: 'End Date:',
            xtype: 'container'
        },{
            itemId: 'endpicker',
            // Month is zero-based
            minDate: new Date(2003,00,01),
            maxDate: new Date(),
            showToday: false,
            xtype: 'datepicker'
        },{
            //handler: reload,
            itemId: 'updatebutton',
            text: 'Update Chart',
            xtype: 'button'
        }],
        layout: {
            align: 'stretch',
            type: 'vbox'
        },
        region: 'west',
        width: 200,
        xtype: 'container'
    }]

});
