<?php
class ScriptsController extends AppController {

    public function main(){
        // Default tab is map
        $tab = 'map';
        
        if(isset($this->request->query['q'])){
            $tab = $this->request->query['q'];
        }
        $this->response->type('javascript');
        $this->set('tab', $tab);
    }

}

?>
