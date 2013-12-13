Ext.define('Osm.controller.CurrentWeather', {
    extend: 'Ext.app.Controller',

    views: [
    'CurrentWeather'
    ],

    refs: [{
        ref: 'currentWeatherPanel',
        selector: 'currentweatherpanel'
    },{
        ref: 'centerContainer',
        selector: 'currentweatherpanel container[region="center"]'
    },{
        ref: 'datePicker',
        selector: 'currentweatherpanel datepicker'
    }],

    init: function() {
        this.control({
            'currentweatherpanel container[region="center"]': {
                render: this.onPanelRendered
            },
            '#reload-button': {
                click: this.onReloadButtonClick
            },
            'currentweatherpanel datepicker': {
                select: this.onDatePickerSelect
            }
        });
    },

    onPanelRendered: function(comp) {
        this.reload(this.getCurrentWeatherPanel());
    },

    onReloadButtonClick: function(){
        this.reload(this.getCurrentWeatherPanel());
    },

    reload: function(comp, params){

        // Get required components
        var cc = this.getCenterContainer();
        var dp = this.getDatePicker();

        cc.setLoading(true);

        comp.getStore().load({
            callback: function(records, operation, success) {
                cc.setLoading(false);
            },
            params: params
        });
    },

    onDatePickerSelect: function(datepicker, date){

        var month = new String(date.getMonth()+1);
        if(month < 10) {
            month = '0' + new String(month);
        }
        var day = new String(date.getDate());
        if(day < 10){
            day = '0' + new String(day);
        }

        this.reload(this.getCurrentWeatherPanel(), {
            date: date.getFullYear() + month + day
        });

    }

});