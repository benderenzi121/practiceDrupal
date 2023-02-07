<?php 

namespace Drupal\twillio\Controller;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class TwillioController extends ControllerBase {
    
    public function view($phone_number){
        $valid = $this->verifyNumber($phone_number);

      
        $phone_number_data = array(
            'valid' => $valid -> valid,
            'countryCode' => $valid -> countryCode
          );
          if($phone_number_data['valid'] == false){
            // dump($verify_number_response_invalid_number);
            return new Response('400');
          }
          elseif($phone_number_data['countryCode'] != 'US'){
            return new Response('405');
          }

          if ($phone_number_data['valid'] == true && $phone_number_data['countryCode'] == 'US'){
            $test = $this->verifySms($phone_number);
          }

        $response = new Response($test);
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

    public function verifySms($phone_number){
        $twillio_connector_service = \Drupal::Service(id: 'twillio.api_connector');
        $response = $twillio_connector_service->verifySms($phone_number);
        if(!empty($response)){
            return $response;
        } else {
            return 'error';
        }
    }

}

?>