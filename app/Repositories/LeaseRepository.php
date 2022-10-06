<?php

namespace App\Repositories;

class LeaseRepository extends AbstractRepository {

    public function selectCustomerAttributes($customer_attrs) {
        $this->model = $this->model->with($customer_attrs);
    }
}


?>