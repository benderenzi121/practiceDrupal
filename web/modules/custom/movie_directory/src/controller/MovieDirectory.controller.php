<?php 
namespace Drupal\movie_directory\Controller;

use Drupal\Core\Controller\ControllerBase;

class MovieListing extends ControllerBase{
    public function view(){
        return [
            '#type' => 'markup',
            '#markup' => $this -> t(string: 'This is where we will list our movies')
        ];
    }
}

?>