<?php 

namespace Drupal\twillio\Controller;

use Drupal\Core\Controller\ControllerBase;

class TwillioController extends ControllerBase {
    public function view($test){
        $valid = $this->verifyNumber($test);
        return [
            '#markup' => $valid->valid ? 'true': 'false',
            '#content' => 'hello',
        ];
    }


    public function verifyNumber($phone_number){
        $twillio_connector_service = \Drupal::Service(id: 'twillio.api_connector');
        $response = $twillio_connector_service->verifyNumber($phone_number);
       
        if(!empty($response)){
            return $response;
        } else {
            return 'error';
        }
    }
}

?>