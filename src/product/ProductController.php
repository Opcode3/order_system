<?php
    namespace app\product;

    use app\config\DatabaseConnector;

    class ProductController{
        private $productModel = null;
        function __construct(){

            $this->productModel = new Product(DatabaseConnector::getConnection());

        }
        public function viewAll(){
            return $this->productModel->readAll();
        }

        public function add($name, $price){
            // validate parameter
            return $this->productModel->create($name, (int) $price);

        }

        public function getProductBySlug($slug){
            // validate parameter
            return $this->productModel->read($slug);
        }

        public function updateProductBySlug($slug, $name = null, $price = null){
            $data = [ "slug" => $slug ];

            if($name != null){ $data['name'] = $name; }

            if($price != null){ $data['price'] = $price; }

            // return $data;

            return $this->productModel->update($data);
        }

        public function removeProductBySlug($slug){
            return $this->productModel->delete($slug);
        }
    }

?>