Ext.define('Osm.controller.Temperature', {
    extend: 'Ext.app.Controller',

    requires: [
      'Date'
    ],

    charts: [
    'Temperature'
    ],

    stores: [
    'Temperature'
    ],

    views: [
    'Temperature'
    ],

    refs: [{
        ref: 'temperatureChart',
        selector: 'temperaturechart'
    },{
        ref: 'startDatePicker',
        selector: '#startpicker'
    },{
        ref: 'endDatePicker',
        selector: '#endpicker'
    }],

    init: function() {
        this.control({
            'temperaturechart': {
                render: this.onPanelRendered
            },
            '#updatebutton': {
                click: this.onClick
            }
        });
    },

    onPanelRendered: function() {
        this.getStartDatePicker().setValue(this.initDate());
        this.reload();
    },

    onClick: function(){
        this.reload();
    },

    reload: function(picker, date){

        var tc = this.getTemperatureChart();

        tc.setLoading(true);
        var sDate = this.getStartDatePicker().getValue();
        var sMonth = new String(sDate.getMonth()+1);
        if(sMonth < 10) {
            sMonth = '0' + new String(sMonth);
        }
        var sDay = new String(sDate.getDate());
        if(sDay < 10){
            sDay = '0' + new String(sDay);
        }

        var eDate = this.getEndDatePicker().getValue();
        var eMonth = new String(eDate.getMonth()+1);
        if(eMonth < 10) {
            eMonth = '0' + new String(eMonth);
        }
        var eDay = new String(eDate.getDate());
        if(eDay < 10){
            eDay = '0' + new String(eDay);
        }

        tc.getStore().load({
            callback: function(records, operation, success) {
                tc.setLoading(false);
            },
            params: {
                startdate: sDate.getFullYear() + sMonth + sDay,
                enddate: eDate.getFullYear() + eMonth + eDay
            }
        });

    },

    initDate: function(){
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth();
        var day = today.getDate();
        if(month == 0){
            month = 11;
            year = (year-1);
        } else {
            month = (month - 1);
        }
        return new Date(year, month, day);
    }

});