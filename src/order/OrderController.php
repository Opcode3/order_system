<?php
     namespace app\order;

    use app\config\DatabaseConnector;

    class OrderController{

        private $order = null;

        function __construct(){
            $this->order = new Order( DatabaseConnector::getConnection());
        }
        
        public function add(int $product_id, $quantity, int $order_by){
            // vaidate parameter
            return $this->order->create($product_id,$quantity,$order_by);

        }

        public function viewAllByOrder(int $order_by){
            return $this->order->readAllByOrder($order_by);
        }

        public function viewAll(){
            return $this->order->readAll();
        }
    }
?>