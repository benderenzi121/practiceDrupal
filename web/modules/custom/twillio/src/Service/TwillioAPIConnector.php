<?php
    namespace Drupal\twillio\Service;

    use Drupal;
    use Twilio\Exceptions\TwilioException;
    use Twilio\Rest\Client;

    class TwillioAPIConnector{
    protected $twillioClient;

        public function verifyNumber($phone_number){
            $data = [];
            $sid = 'AC91121581a65a14958ee7521f8576a3a5';
            $token = 'a4b839291fd88eb187b22d31364a16ac';


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
