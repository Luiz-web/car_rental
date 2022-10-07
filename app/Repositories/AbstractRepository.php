<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectRelationalAttributes($relational_attrs) {
        $this->model = $this->model->with($relational_attrs);
    }

    public function filter($filters) {
        $filters = explode(';', $filters);
        
        foreach($filters as $key => $condition) {
            $c = explode(':', $condition);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function selectAttributes($attrs) {
        $this->model = $this->model->selectRaw($attrs);
    }

    public function getResult() {
        return $this->model->get();
    }
}

?>