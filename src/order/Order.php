<?php
    namespace app\order;
    
    // id, slug, product_id, quantity, ordered_by
    
    
    class Order{
        
        private $table_name = 'orders';
        private $databaseConnector = null;

        function __construct($databaseConnector)
        {
            $this->databaseConnector = $databaseConnector;   
        }

        public function create(int $product_id, $quantity, int $ordered_by){
            $slug = $this->generateSlug();
            $sql = "INSERT INTO ".$this->table_name."(slug, product_id, quantity, order_by) VALUES(?,?,?,?)";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$slug, $product_id, $quantity, $ordered_by]);
            $result = $stmt->fetch();
            return $result;
        }

        public function update($slug, int $quantity){
            $sql = "UPDATE ".$this->table_name." SET quantity=? WHERE slug=?";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$quantity, $slug]);
            $result = $stmt->fetch();
            return $result;
        }

        public function delete($slug):bool {
            $sql = "DELETE * FROM ".$this->table_name." WHERE slug=?";
            $stmt = $this->databaseConnector->prepare($sql);
            return $stmt->execute([$slug]);
            
        }

        public function read($slug){
            $sql = "SELECT * FROM ".$this->table_name." WHERE slug=?";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$slug]);
            $result = $stmt->fetch();
            return $result;
        }

        public function readAll(){
            $sql = "SELECT products.name, products.price, ".$this->table_name.".* FROM ".$this->table_name." LEFT JOIN products ON ".$this->table_name.".product_id = products.id";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }
        
        public function readAllByOrder(int $ordered_by){
            $sql = "SELECT * FROM ".$this->table_name." WHERE ordered_by=?";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$ordered_by]);
            $result = $stmt->fetchAll();
            return $result;
        }

        private function generateSlug(){
            return uniqid(time());
        }
  
    }

?>