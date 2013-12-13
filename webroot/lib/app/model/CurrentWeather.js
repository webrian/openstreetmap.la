Ext.define('Osm.model.CurrentWeather', {
    extend: 'Ext.data.Model',

    fields: [{
        mapping: "gid",
        name: 'gid',
        type: 'integer'
    },{
        mapping: "time",
        name: 'time',
        type: 'string'
    },{
        mapping: "temperature_celsius",
        name: 'temperature_celsius',
        type: 'float'
    },{
        mapping: "dew_point_celsius",
        name: 'dew_point_celsius',
        type: 'float'
    },{
        mapping: "humidity",
        name: 'humidity',
        type: 'float'
    },{
        mapping: "pressure",
        name: 'pressure',
        type: 'float'
    },{
        mapping: "wind_speed",
        name: 'wind_speed',
        type: 'float'
    },{
        mapping: "wind_direction",
        name: 'wind_direction',
        type: 'float'
    },{
        mapping: "wind_compass",
        name: 'wind_compass',
        type: 'string'
    },{
        mapping: "visibility_kilometers",
        name: 'visibility_kilometers',
        type: 'float'
    }]

});