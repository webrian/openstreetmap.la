Ext.define('Osm.store.Temperature', {
    extend: 'Ext.data.Store',
    autoLoad: false,

    requires: [
    'Osm.model.Temperature'
    ],

    model: 'Osm.model.Temperature',

    proxy: {
        extraParams: {
            enddate: '20110315',
            startdate: '20110301'
        },
        reader: {
            type: 'json',
            root: 'data',
            idProperty: 'day'
        },
        type: 'ajax',
        url: '/weatherdiagrams/temperature'
    }

});