<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_body = file_get_contents('php://input');

$method = $_SERVER['REQUEST_METHOD'];

 require_once('../vendor/autoload.php');

use app\config\Dto;
use app\order\OrderController;
use app\product\ProductController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = explode('/', $uri);

// var_dump( $request);
if($request[1] == 'api'){

    if($request[2] == 'product'){
        $productController = new ProductController();

        if($method === 'POST'){

            $data = json_decode($request_body, true);
                if(is_array($data)){
                    if(count($data) == 2){
                        // basic data validation
                        $name = $data['name'];
                        $price = (int)$data['price'];
                        $response = $productController->add($name, $price);
                        echo Dto::response($response);
                    }
                }else{
                    echo Dto::response("Unable to access request data");
                    header("HTTP/1.1 404 unaccepted format request data");
                }

        }else{
            if(count($request) == 4){
                $slug = $request[3];

                if($method == 'PUT' || $method === 'PATCH'){
                    // basic data validation
                    $data = json_decode($request_body, true);
                    if(count($data) >= 1){
                        // basic data validation
                        $name = isset($data['name']) ? $data['name'] : null;
                        $price = isset($data['price']) ? $data['price'] : null;
                        $response = $productController->updateProductBySlug($slug, $name, $price);
                        echo Dto::response([$response]);
                    }else{
                        header('HTTP/1.1 404 incomplete update request data');
                        echo Dto::response('update request body is requried');

                    }

                }else if($method === 'DELETE'){
                    $response = $productController->removeProductBySlug($slug);
                    echo Dto::response($response);

                }else{
                    $response = $productController->getProductBySlug($slug);
                    echo Dto::response($response);
                }
                
            }else{
                $response = $productController->viewAll();
                echo Dto::response($response);
            }
            
        }
    }else if($request[2] == 'order'){
        $orderController = new OrderController();
        if($method == 'POST'){ //create

            $data = json_decode($request_body, true);
            if(is_array($data) && count($data) == 3){
                $product_id = (int) $data['product_id'];
                $quantity = (int) $data['quantity'];
                $order_by = (int) $data['ordered_by'];
                if($product_id != 0){

                    $response = $orderController->add($product_id,$quantity,$order_by);
                    echo Dto::response($response);

                }else{
                    echo Dto::response("Unable to identify product");
                    header("HTTP/1.1 404 product not found");
                }


            }else{
                echo Dto::response('unknown request data');
                header("HTTP/1.1 300 unknown request format");
            }

        }else if ($method == 'PUT' || $method == 'PATCH'){ //update

        }else{ // view
            $data_view = $orderController->viewAll();
            echo Dto::response($data_view);
        }

    }else{
        echo Dto::response([788]);
    }

}else{
    header('HTTP/1.1 404 Not Found');
    exit();
}




            // if(count($data) == 2){
            //     // basic data validation
            //     $name = $data[0];
            //     $price = (int)$data[1];
            //     $response = $productController->add($name, $price);
            //     echo DTO::response($response);
            // }


?>