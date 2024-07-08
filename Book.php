<?php
include_once 'Product.php';


class Book extends Product {
    protected $weight;

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }
}

?>
