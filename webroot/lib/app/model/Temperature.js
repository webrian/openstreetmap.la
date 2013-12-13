Ext.define('Osm.model.Temperature', {
    extend: 'Ext.data.Model',

    fields: [{
        mapping: "day",
        name: 'day',
        type: 'string'
    },{
        mapping: "t",
        name: 't',
        type: 'float'
    },{
        mapping: "min",
        name: 'min',
        type: 'float'
    },{
        mapping: "max",
        name: 'max',
        type: 'float'
    }]

});