<?php

class LangController extends AppController {

    public function beforeFilter() {
        $this->autoRender = false;
        $this->response->type('javascript');
        Configure::write('Config.language', $this->Session->read('Config.language'));
    }

    public function index() {
        $msg = "Ext.namespace('Ext.ux');";

        $translation = array(
            'Map' => __('Map'),
            'Edit' => __('Edit'),
            'Downloads' => __('Downloads'),
            'About this page' => __('About this page')
        );

        // using getKey
        $msg .= "Ext.ux.ts = new Ext.util.MixedCollection();";
        $msg .= "Ext.ux.ts.tr = function(string) {
    if(this.containsKey(string)){
        return this.get(string);
    } else {
        return string;
    }
};";
        $msg .= "Ext.ux.ts.addAll(";
        $msg .= json_encode($translation);
        $msg .= ");";

        return $msg;
    }

}