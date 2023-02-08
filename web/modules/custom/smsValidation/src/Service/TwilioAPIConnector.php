<?php

namespace Drupal\smsValidation\Service;

use Drupal;
use GuzzleHttp\Exception\RequestException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;
use Drupal\core\http\ClientFactory;
/**
 * Provides a Demo Resource.
 *
 * @RestResource(
 *   id = "validator_resource",
 *   label = @Translation("Twilio Validator resource"),
 *   uri_paths = {
 *     "canonical" = "/v1/smsValidation/{phoneNumber}"
 *   }
 * )
 */
class TwilioAPIConnector {
  /**
   * Twilio  client variable;.
   *
   * @var Twilio\Rest\Client
   */
  protected $twillioClient;

  /**
   * Constructor function that creats an instance of our twilio client.
   *
   * Based on the SID and AuthToken in our settings.php file.
   */
  public function __construct( $client) {
    $sid = Drupal::config('twillio.settings')->get('TWILLIO_SID');
    $token = Drupal::config('twillio.settings')->get('TWILLIO_AUTH_TOKEN');

    $this->twillioClient = new Client($sid, $token);
  }

  /**
   * Our Verify Number function hits the twilio API to lookup a phone number.
   *
   * As long as the request from twilio comes back, it will return the data
   * Provided by twillio.
   *
   * @param string $phone_number
   */

  public function verifyNumber($phone_number) {
    // Initialize an empty array that our data will eventually go into.
    $data = [];

    /*
     *   try to hit the twilio endpoint with the phone number provided
     *       will populate our data array if there is a response from the twilio api
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
    catch (RequestException $e) {
      watchdog_exception('twilio', $e, $e->getMessage());
    }

        return $data;
    }

     /**
     *  verifySms function is used to verify that the number provided is a valid mobile number
     *      from within the US. 
     * 
     *     returns the type of carrier landline/ mobile
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
        catch(RequestException $e){
            watchdog_exception('twillio',$e,$e->getMessage());
        }
        return $validation->carrier['type'];
    }
    }
?> 
