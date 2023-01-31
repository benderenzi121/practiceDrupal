<?php
    namespace Drupal\movie_directory\Service;

use Drupal;

    class MovieAPIConnector{
        private $client;
        private $query;

        public function __construct(\Drupal\core\http\ClientFactory $client)
        {
            $movie_api_config = Drupal::state()->get(key: \Drupal\movie_directory\Form\MovieAPI::MOVIE_API_CONFIG_PAGE);
            $api_url = ($movie_api_config['api_base_url']) ?: 'https://api.themoviedb.org/3';
            $api_key = ($movie_api_config['api_key']) ?: '';

            $query = ['api_key' => $api_key];

            $this->query = $query;

            $this-> client = $client->fromOptions(
                [
                    'base_uri' => $api_url,
                    'query' => $query
                ]
                );

            
        }

        public function discoverMovies(){
            $data = [];
            $endpoint = '/3/discover/movie';
            $options = ['query' => $this->query];
            try{
                $request = $this->client->get($endpoint,$options);
                $result = $request->getbody()->getContents();
                $data = json_decode($result);
            }
            catch(\GuzzleHttp\Exception\RequestException $e){
                watchdog_exception('movie_directory',$e, $e->getMessage());
            }
            return $data;
        }

        public function getImageUrl($image_path){
        return "https://image.tmdb.org/t/p/w500/" . $image_path;
        }
    }
?> 
