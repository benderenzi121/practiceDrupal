<?php

namespace Drupal\smsValidation\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;

use Drupal\rest\ResourceResponse;

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
class ValidatorResource extends ResourceBase {
  /**
   * Responds to entity GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *
   *   description: Returns whether a passed number is a valid mobile number.
   */

  public function get($phoneNumber) {

    // Connects to our twilio api service.
    $twillio_connector_service = \Drupal::Service(id: 'twillio.api_connector');

    // Calls to our twilio service to grab the initial verification information.
    $verifyNumber = $twillio_connector_service->verifyNumber($phoneNumber);

    if (!empty($verifyNumber)) {
      // If information comes back from twilio, we make an array of the validity and country data.
      $phone_number_data = [
        'valid' => $verifyNumber->valid,
        'countryCode' => $verifyNumber->countryCode,
      ];
      // Return a bad response if the number is not valid.
      if ($phone_number_data['valid'] == FALSE) {
        $non_valid_error = [
          'errors' => [
            "status" => '400',
            "title" => 'Invalid Phone Number',
            "detail" => 'the provided number is not recognized as a valid phone number',
          ],
        ];
        return new ResourceResponse($non_valid_error, 400);
      }

      // Return a bad response if the phone is not registered in the US..
      elseif ($phone_number_data['countryCode'] != 'US') {
        $non_us_error = [
          'errors' => [
            "status" => '400',
            "title" => 'Invalid Phone Number',
            "detail" => 'the provided number must must reside in the US',
          ],
        ];
        return new ResourceResponse($non_us_error, 400);
      }
      // Ensures nothing got past the previous checks.
      // (Kind of redundant should possibly remove?)
      if ($phone_number_data['valid'] == TRUE && $phone_number_data['countryCode'] == 'US') {

        // Calls to the verify SMS function of the twilio service.
        // This is a paid request that Looks for mobile carrier information
        // And returns it.
        $result = $twillio_connector_service->verifySms($phoneNumber);
        if ($result === 'mobile') {
          // Good response for mobile numbers.
          return new ResourceResponse(202);
        }
        else {
          // Bad response for no mobile numbers.
          $non_mobile_error = [
            'errors' => [
              "status" => '400',
              "title" => 'Invalid Phone Number',
              "detail" => 'the provided number must be a valid mobile number to recieve sms',
            ],
          ];
          return new ResourceResponse($non_mobile_error, 500);
        }
      }
      else {
        // We shouldnt have ever made it here (planning on removing)
        return new ResourceResponse('501');
      }
    }
    else {
      // Communication with twilio didnt work.
      return new ResourceResponse('501');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

}
