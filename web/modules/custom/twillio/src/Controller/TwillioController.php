<?php 

namespace Drupal\twillio\Controller;

use Drupal\Core\Controller\ControllerBase;

class TwillioController extends ControllerBase {
    public function view($test){
       
        return [
            '#markup' => 'yurrr'.$test,
            '#content' => 'hello',
        ];
    }
}

?>