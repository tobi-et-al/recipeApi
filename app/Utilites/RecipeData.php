<?php

    namespace App\Utilities;

    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;

    /**
     * @property  csvData
     */
    class RecipeData
    {

        const CACHEKEY = "recipe.data";

        public function __construct($fileName) {

            $this->setFileName(($fileName));
                 try{
                        if(file_exists($this->getFileName())){
                            $this->load();
                        }else{
                            throw new \FileException("File not found");
                        }
                    }catch (\Exception $e){
                        Log::error($e);
                        abort(500, 'My custom 500 message');
                    }
        }

        /**
         * load CSV into cache
         */
        private function load() {

            $csv = array_map('str_getcsv', file($this->getFileName()));
            $header = $csv[0];
            unset($csv[0]);
            $result = [];


            array_walk( $csv, function($value) use(&$header, &$result){
                $result[] = array_combine($header, $value);

            });
            Cache::forever(self::CACHEKEY, ($result));
        }

        /**
         * return cached recipe array
         */
        public static function getRecipe(){
            return Cache::get(self::CACHEKEY);
        }

        /**
         * return reset and update cached data
         */
        public static function setRecipe($recipe){

            $recipeCsvPath = storage_path(getenv("RECIPE_DATA"));

            $temp_file = fopen($recipeCsvPath, 'w');

            $header = array_keys($recipe[0]);
            fputcsv($temp_file, $header);

            // loop through the array
            foreach ($recipe as $line) {
                // use the default csv handler
                fputcsv($temp_file, $line);
            }
            fclose($temp_file);
            Cache::forget(self::CACHEKEY);
            Cache::forever(self::CACHEKEY, $recipe);
        }

        /**
         * @return mixed
         */
        public function getFileName()
        {
            return $this->fileName;
        }

        /**
         * @param mixed $fileName
         */
        private function setFileName($fileName): void
        {
            $this->fileName = $fileName;
        }

    }