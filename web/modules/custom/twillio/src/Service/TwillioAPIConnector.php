<?php
    namespace Drupal\twillio\Service;

    use Drupal;
    use Symfony\Component\HttpFoundation\Response;
    use Twilio\Exceptions\TwilioException;
    use Twilio\Rest\Client;
    use Drupal\Core\Site\Settings;
    use Symfony\Component\HttpFoundation\JsonResponse;

    class TwillioAPIConnector{
        
    /**
     * Twilio  client variable;.
     *
     * @var Twilio\Rest\Client
     */    
    protected $twillioClient;

     /**
     * Constructor function that creats an instance of our twilio client
     *  based on the SID and AuthToken in our settings.php file
     * 
     */    

    public function __construct(\Drupal\core\http\ClientFactory $client)
    {
        $sid = Drupal::config('twillio.settings')->get('TWILLIO_SID');
        $token = Drupal::config('twillio.settings')->get('TWILLIO_AUTH_TOKEN');

        $this->twillioClient = new Client($sid, $token);

    }

    /**
     * Our Verify Number function hits the twilio API to lookup a phone number
     *  
     * As long as the request from twilio comes back, it will return the data provided by twillio. 
     * @param string $phone_number
     * 
     */

    public function verifyNumber($phone_number){
        // Initialize an empty array that our data will eventually go into
        $data = [];

        /**
        *   try to hit the twilio endpoint with the phone number provided
        *       will populate our data array if there is a response from the twilio api
        *       
        *       If there is no response we return an array with nothing inside of it 
        *           and throow an exception
        */
        try {
            $validation = $this->twillioClient->lookups->v2->phoneNumbers($phone_number)->fetch();
            $data = $validation;
        }
        catch (TwilioException $e) {
            throw $e;
            }
        catch(\GuzzleHttp\Exception\RequestException $e){
            watchdog_exception('twillio',$e, $e->getMessage());
        }

        return $data;
    }

     /**
     *  verifySms function is used to verify that the number provided is a valid mobile number
     *      from within the US. 
     * 
     *      return a 202 response for a valid mobile number to recieve sms
     * 
     *      return a 400 response with for a non valid US number 
     * 
     *  @param string $phone_number
     * 
     */    

    public function verifySms($phone_number){
        try {
            $validation = $this->twillioClient->lookups->v1->phoneNumbers($phone_number)->fetch(["type" => ["carrier"]]);
        }
        catch (TwilioException $e) {
            throw $e;
            }
        catch(\GuzzleHttp\Exception\RequestException $e){
            watchdog_exception('twillio',$e,$e->getMessage());
        }
        return $validation->carrier['type'];
    }
    }
?> 
