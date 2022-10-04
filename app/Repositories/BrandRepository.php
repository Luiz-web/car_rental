<?php

    namespace App\Repositories;

    use Illuminate\Database\Eloquent\Model;

    class BrandRepository {
        
        public function __construct(Model $model) {
            $this->model = $model;
        }
    
        public function selectAttributes($attr) {
            $this->model = $this->model->with($attr);
        }
           
    
    }

?>