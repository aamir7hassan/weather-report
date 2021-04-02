<?php
    include_once 'Config.php';
    interface Temperature {
        public function getTemperature(string $city);
    }

    class Api implements Temperature {

        protected $api_key;
        private $search_url = 'http://api.openweathermap.org/data/2.5/weather?';

        public function __construct(string $api_key) {
            $this->api_key = $api_key;
        }

        public function getApiKey() {
            return $this->api_key;
        }

        /*
         *   Return temperature data based on city
         */
        public function getTemperature(string $city) {
            $city = trim($city);
            // validate city name
            $validated = $this->validateCity($city);
            // if validated successfully
            if($validated) {
                $url = $this->search_url.'q='.$city.'&appid='.$this->api_key.'&units=metric';
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $url,
                    CURLOPT_USERAGENT => 'sevenstar',
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
                return json_encode(['success'=>true,'message'=>'Data found','data'=>$response]);
            } else {
                return json_encode(['success'=>false,'message'=>'Invalid city name','data'=>'']);
            }
        }

        /*
         *   validate city name
         */
        public function validateCity(string $city): bool {
            $city = strtolower($city);
            $cities = json_decode(file_get_contents('gb.json'),true);
            if(is_array($cities)) {
                $city_names = array_map('strtolower', array_column($cities,'city'));
                if(in_array($city, $city_names)) {
                    return true;
                } else {
                    return false;
                }
            }
            return false;
        }

    } // end Api class
