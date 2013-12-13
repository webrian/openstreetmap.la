Ext.define('Osm.store.CurrentWeather', {
    extend: 'Ext.data.Store',

    autoLoad: false,

    model: 'Osm.model.CurrentWeather',

    proxy: {
        reader: {
            type: 'json',
            root: 'data',
            idProperty: 'gid'
        },
        type: 'ajax',
        url: '/weatherdiagrams/day'
    }

});