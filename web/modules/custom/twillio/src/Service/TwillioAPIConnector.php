<?php
    namespace Drupal\twillio\Service;

    use Drupal;
    use Twilio\Exceptions\TwilioException;
    use Twilio\Rest\Client;
    use Drupal\Core\Site\Settings;

    class TwillioAPIConnector{
    protected $twillioClient;

        public function verifyNumber($phone_number){
            
            $data = [];

            $sid = Drupal::config('twillio.settings')->get('TWILLIO_SID');
            $token = Drupal::config('twillio.settings')->get('TWILLIO_AUTH_TOKEN');

            $twillioClient = new Client($sid, $token);
        try {
            $validation = $twillioClient->lookups->v2->phoneNumbers($phone_number)->fetch();
            $data = $validation;
            
        }catch (TwilioException $e) {
            throw $e;
          }
        catch(\GuzzleHttp\Exception\RequestException $e){
            watchdog_exception('twillio',$e, $e->getMessage());
        }
        return $data;
        }
  
    }
?> 
