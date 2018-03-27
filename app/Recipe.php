<?php

    namespace App;

    use App\Utilities\RecipeData;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Collection;

    class Recipe extends Model
    {
        /**
         * The primary key for the model.
         *
         * @var string
         */
        protected $primaryKey = 'id';

        /**
         * The "type" of the auto-incrementing ID.
         *
         * @var string
         */
        protected $keyType = 'string';

        /**
         * Indicates if the IDs are auto-incrementing.
         *
         * @var bool
         */
        public $incrementing = true;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'id','created_at','updated_at','box_type','title','slug','short_title','marketing_description','calories_kcal','protein_grams','fat_grams','carbs_grams','bulletpoint1','bulletpoint2','bulletpoint3','recipe_diet_type_id','season','base','protein_source','preparation_time_minutes','shelf_life_days','equipment_needed','origin_country','recipe_cuisine','in_your_box','gousto_reference'
        ];

        /**
         * override default modal to save to the cache
         * @param array $options
         * @return bool
         */
        public function save(array $options = [])
        {
            $recipeData = (RecipeData::getRecipe());

            $newRecord = $this->fill(array_merge(["id" => $this->getAvailableId()],
                                                $this->getAttributes()));
            $newRecord->updateTimestamps();

            $properOrderedArray = array_replace(array_flip($this->getFillable()), $newRecord->attributesToArray());

            if(!empty($this->getAttribute('title'))) {
                $recipeData[] =  $properOrderedArray;
                RecipeData::setRecipe($recipeData);
            }
            return $this->getAttributes();
        }

        /** update cache object where id matches
         * @param array $attributes
         * @param array $options
         * @return bool
         */
        public function update(array $attributes = [], array $options = [])
        {
            $recipeData = (RecipeData::getRecipe());
            $record = Collect($recipeData)->where('id',$this->getKey());
            if (count($record->keys()))
            {
                $key = $record->keys()->first();
                $recipeData[$key] = array_merge($recipeData[$key], $this->getAttributes());

                RecipeData::setRecipe($recipeData);
                return $recipeData[$key];
            }

            return false;
        }

        /** generate next available id from max id value
         * @return int|mixed
         */
        public function getAvailableId(){
            $idArray = [];
             Collect(RecipeData::getRecipe())->map(function($record) use (&$idArray){
                $idArray[] =  ($record['id']);
             });

            return max($idArray) + 1;
        }



        /**
         * The attributes excluded from the model's JSON form.
         *
         * @var array
         */
        protected $hidden = [];
    }
