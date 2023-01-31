<?php
    namespace Drupal\movie_directory\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

    class MovieAPI extends FormBase{

        const MOVIE_API_CONFIG_PAGE = 'movie_api_config_page:values';

        public function getFormId()
        {
            return 'movie_api_config_page';      
        }
        public function buildForm(array $form, FormStateInterface $form_state)
        {
            $values = \Drupal::state()-> get(key:self::MOVIE_API_CONFIG_PAGE);
            // this is the actual form that we will see on the page
            $SForm = [];
            $sForm['api_base_url'] = [
                '#type' => 'textfield',
                '#title' => $this->t(string:'API base URL'),
                '#description' => $this-> t(string: 'This is the API base url'),
                '#required' => TRUE,
                '#default_value' => $values['api_base_url'],
            ];

            $sForm['api_key'] = [
                '#type' => 'textfield',
                '#title' => $this -> t(string: 'API key'),
                '#description' => $this->t(string: 'this is where we put our access token for the API'),
                '#required' => TRUE,
                '#default_value' => $values['api_key'],

            ];

            $sForm['actions']['#type'] = 'actions';
            $sForm['actions']['submit'] = [
                '#type' => 'submit',
                '#value' => $this->t(string: 'Save'),
                '#button_type' => 'primary'
            ];

            return $sForm;
        }
        public function submitForm(array &$form, FormStateInterface $form_state)
        {
            $submitted_values = $form_state -> cleanValues()-> getValues();

            \Drupal::state()-> set(SELF::MOVIE_API_CONFIG_PAGE , $submitted_values);

            $messenger = \Drupal::service(id:'messenger');
            $messenger->addMessage($this->t(string: 'Your new configuration has been saved!'));

        }
    }
?>