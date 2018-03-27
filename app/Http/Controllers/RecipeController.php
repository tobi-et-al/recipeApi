<?php

    namespace App\Http\Controllers;

     use App\Utilities\RatingData;
     use App\Utilities\RecipeData;
     use Illuminate\Http\Request;
     use App\Recipe;

     // TODO: rename controller

    class RecipeController extends Controller
    {
        private $data;

        public function __construct() {
            $this->data =  collect(RecipeData::getRecipe());
        }

        public function showCuisineRecipe($cuisine)
        {
            $record =  $this->data->where('recipe_cuisine' , $cuisine);
            return $record->count() > 0 ? response()->json($record) : null;
        }

        public function showOneRecipe($id)
        {
            $record = $this->data->where('id' , $id);
            return $record->count() > 0 ? response()->json($record) : null;
        }

        public function showAll()
        {
            $record = $this->data;
            return $record->count() > 0 ? response()->json($record) : null;
        }

        public function createRecipe(Request $request)
        {
            $this->validate($request, [
                'title' => 'required',
                'slug' => 'required',
                'calories_kcal' => 'required'
            ]);

            $request->all();
            $recipe = new Recipe($request->all());
            $newRecipe =  $recipe->save();

            return response()->json($newRecipe, 201);
        }

        public function updateRecipe($id, Request $request)
        {
            $request->all();
            $recipe = new Recipe($request->all());
            $recipe->setAttribute('id', $id);
            $newRecipe = $recipe->update();

            return response()->json($newRecipe, 201);
        }

        public function rateRecipe($id, Request $request)
        {
            $post = $request->all();
            $this->validate($request, [
                'rating' => 'required'
            ]);
            $availableRecords = $this->data->where('id' , $id);

                $rating = $post['rating'];
                if ( $availableRecords->isNotEmpty() && $rating > 0 && $rating <= 5) {
                    $ratingCollection = Collect(\App\Utilites\RatingData::getRating())->where('recipeId', $id);
                    $ratingArray =  Collect(\App\Utilites\RatingData::getRating())->toArray();
                    $record = null;

                    if($ratingCollection->isEmpty()){
                        $record = [ 'recipeId' =>  $id,
                                    'rating' => $rating];
                        $ratingArray[] = $record;

                    }else{
                        foreach ($ratingCollection->keys() as $key){
                            $ratingArray[$key]['rating'] = $rating;
                            $record = $ratingArray[$key];
                        }
                    }

                    \App\Utilites\RatingData::setRating($ratingArray);
                    return $record;
                }
            return null;
        }

    }