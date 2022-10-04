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

        public function filter($filters) {
            $filters = explode(';', $request->filter);
            
            foreach($filter as $key => $condition) {
                $c = explode(':', $condition);
                $this->model = $this->model->where($c[0], $c[1], $c[2]);
            }
        }
           
    
    }

?>