<?php

    namespace App\Http\Traits;


    use Illuminate\Database\Eloquent\Model;

    trait ShouldLoadRelations
    {
        public function loadRelations($for, $relations = null){
            $relations = $relations ?? $this->relations ?? [];

            foreach ($relations as $relation){
                $for->when(
                    $this->parseInclude($relation),
                    fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
                );
            }
            return $for;
        }

        protected function parseInclude(string $relation){
            $include = \request()->query('include');

            if (empty($include)){
                return false;
            }

            $relations = array_map('trim', explode(',', $include));

            return in_array($relation, $relations);
        }

    }
