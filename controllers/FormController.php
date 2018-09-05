<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11-Jul-18
 * Time: 14:31
 */

namespace Api\Controllers;

use Phalcon\Mvc\Controller;
use Exception;
use Api\Models\Cartype;
use Api\Models\Voucher;
use Api\Models\Price;
use Api\Models\User;
use Phalcon\Http\Response;

class FormController extends Controller
{
    function post()
    {
        $decoded = $this->requestBody;

        $id_company = $decoded['id_company'];
        $id_user = $decoded['id_user'];
//        $email = $decoded['email'];
//        $phone_number = $decoded['phone_number'];
        $from_address = $decoded['from_address'];
        $to_address = $decoded['to_address'];
        $from_lat = $decoded['from_lat'];
        $from_lng = $decoded['from_lng'];
        $to_lat = $decoded['to_lat'];
        $to_lng = $decoded['to_lng'];
        $cartype_str = $decoded['cartype'];
        if(isset($decoded['voucher']))
            $voucher = $decoded['voucher'];

//        /* email */
//        $domain = ltrim(stristr($email, '@'), '@');
//        $user   = stristr($email, '@', TRUE);
//        $dns = ['yahoo.com', 'gmail.com'];
//        if(!preg_match('/[a-zA-Z0-9]*/', $user) || empty($user) || empty($domain) || !in_array($domain, $dns))
//        {
//            $response = new Response();
//            $response->setJsonContent(
//                [
//                    'status' => 'email',
//                ]
//            );
//            return $response;
//        }
//
//        /* phone */
//        if(!preg_match("/07[0-9]{8}/", $phone_number) || strlen($phone_number) > 10) {
//            $response = new Response();
//            $response->setJsonContent(
//                [
//                    'status' => 'phone',
//                ]
//            );
//            return $response;
//        }

        /* find distance and duration */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graphhopper.com/api/1/route?point=' . $from_lat . ',' . $from_lng . '&point=' . $to_lat . ',' . $to_lng . '&locale=de&vehicle=car&key=1457d742-1f3c-4f20-bd8f-f9977a7775db');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geocode = curl_exec($ch);
        $geocode = json_decode($geocode);
        $time = $geocode->paths[0]->time;
        $distance = $geocode->paths[0]->distance;

        /* find id_cartype by type */
        $cartype = Cartype::findFirst([
            'conditions' => "id_company = :id_company: and type = :cartype:",
            'bind'       => ['id_company' => $id_company, 'cartype' => $cartype_str]
        ]);
        if($cartype == null)
        {
            throw new Exception('cartype not found.', 409);
        }
        $cartype_id = $cartype->id;
        $cartype_name = $cartype->type;

        /* find price */
        $prices = Price::find([
            'conditions' => "id_company = :company: and id_cartype = :cartype: and id_user = :user:",
            'bind'       => ['company' => $id_company, 'cartype' => $cartype_id, 'user' => $id_user]
        ]);
        if($prices == null){
            throw new Exception('price not found.', 409);
        }
        $type = ''; $charge = 0; $price_value = 0;
        foreach ($prices as $price){
            $type = $price->type;
            $charge = $price->charge;
            if($distance > $price->distance_threshold)
                break;
        }
        if($type == 'unit')
            $price_value = $charge * $distance;
        else
            $price_value = $charge;
        /* voucher */
        if(isset($decoded['voucher'])) {
            $discount = Voucher::findFirst([
                'conditions' => "code = :code:",
                'bind'       => ['code' => $voucher]
            ]);
            if($discount == null){
                throw new Exception('voucher not found.', 409);
            }
            $current_date = date('Y-m-d H:i:s');
            if($discount->enabled == "0" or $current_date < $discount->from_date or $current_date > $discount->to_date){
                throw new Exception('voucher not available.', 409);
            }
            if($discount->type == "total")
                $price_value = $price_value - $discount->value;
            elseif($discount->type == "percentage")
                $price_value = $price_value - ($discount->value * $price_value) / 100;
        }

        /* if everything is ok, return price, duration, distance */
        $data[] = [
            'id_company'   => $id_company,
            'id_user' => $id_user,
            'id_cartype' => $cartype_id,
            'cartype' => $cartype_name,
            'from_address'   => $from_address,
            'to_address'   => $to_address,
            'from_lat'   => $from_lat,
            'to_lat'   => $to_lat,
            'from_lng'   => $from_lng,
            'to_lng'   => $to_lng,
            'distance' => $distance,
            'duration' => $time,
            'price'   => $price_value,
            'price_type' => $type,
            'price_base' => $charge
        ];
        if(isset($decoded['voucher'])){
            $data[0]['voucher'] = $voucher;
            $data[0]['voucher_type'] = $discount->type;
            $data[0]['voucher_value'] = $discount->value;
        }
        return json_encode($data);
    }
}