<?php 

namespace Drupal\twillio\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class TwillioController extends ControllerBase {
    public function view($test){
        $valid = $this->verifyNumber($test);

        dump($valid);
        
        
        $data = array(
            'valid' => $valid -> valid,
            'country_code' => $valid -> countryCode
          );
          $response = new JsonResponse($data);
          return $response;
       
            
        
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