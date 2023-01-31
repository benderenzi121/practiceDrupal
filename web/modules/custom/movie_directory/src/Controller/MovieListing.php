<?php 
namespace Drupal\movie_directory\Controller;

use Drupal\Core\Controller\ControllerBase;
class MovieListing extends ControllerBase{

    
    public function view(){
       
        $this-> listMovies();
        $content = []; 
        $content['movies'] = $this->createMovieCard();
        return [
            '#theme' => 'movie-listing',
            '#content' => $content,
        ];
    }

    public function listMovies(){
        $movie_api_connector_service = \Drupal::Service(id: 'movie_directory.api_connector');
        $movie_list = $movie_api_connector_service->discoverMovies();
        
        if(!empty($movie_list -> results)){
            return $movie_list->results;
        }
    }

    public function createMovieCard(){
        $movie_cards = [];
        $movies = $this->listMovies();
        $movie_api_connector_service = \Drupal::Service(id: 'movie_directory.api_connector');

        if (!empty($movies)) {
            foreach ($movies as $movie) {
                $content = [
                    'title' => $movie->title,
                    'description' => $movie->overview,
                    'movie_id' => $movie->id,
                    'image' => $movie_api_connector_service->getImageUrl('https://image.tmdb.org/t/p/w500/' . $movie->poster_path)

                ];


                $movie_cards[] = [
                    '#theme' => 'movie-card',
                    '#content' => $content
                ];
            }
        }
        return $movie_cards;
    }
}

?>