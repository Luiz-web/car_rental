<?php

    namespace App\Repositories;

    use Illuminate\Database\Eloquent\Model;

    class BrandRepository {
        
        public function __construct(Model $model) {
            $this->model = $model;
        }
    
        
    
    }

?>