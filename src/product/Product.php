<?php

// Product field data ( id, slug, name, price)

    namespace app\product;

    class Product{
        private $table_name = 'products';
        private $databaseConnector = null;

        function __construct($databaseConnector)
        {
            $this->databaseConnector = $databaseConnector;   
        }

        public function create(string $name, int $price){
            $slug = $this->generateSlug();
            $sql = "INSERT INTO ".$this->table_name."(slug, name, price) VALUES(?,?,?)";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$slug, $name, $price]);
            $result = $this->read($slug);
            return $result;
        }

        public function update(array $data){
            $check = 0;
            $sql = "UPDATE ".$this->table_name." SET ";
            if(array_key_exists("name", $data) ){ $check = 1; $sql .= "name= :name";}
            if(array_key_exists("price", $data)){ $sql .= ($check == 1)?", price= :price":"price= :price";}
            $sql .= " WHERE slug=:slug";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute($data);
            if($stmt->rowCount() > 0){
                return [];
            }else{
            //    return $sql;
               return 'unable to make changes.';
            }
        }

        public function delete($slug){
            $sql = "DELETE FROM ".$this->table_name." WHERE slug=?";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$slug]);
            if($stmt->rowCount() > 0){
                return [];
            }else{
               header('HTTP/1.1 400 Not Found');
               return 'Request Data Not Found.';
            }

        }

        public function read($slug){
            $sql = "SELECT * FROM ".$this->table_name." WHERE slug=?";
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->execute([$slug]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
             if($result){
                 return $result;
             }else{
                header('HTTP/1.1 400 Not Found');
                return 'Request Data Not Found.';
             }
        }

        public function readAll(){
            $sql = "SELECT * FROM ".$this->table_name;
            $stmt = $this->databaseConnector->prepare($sql);
            $stmt->setFetchMode(\PDO::FETCH_ASSOC);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $result;
        }

        private function generateSlug(){
            return uniqid(time());
        }
    }
