<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Request;

class Payment extends BaseController
{
    public function index()
    {
        return view('payment');
    }

    public function initiatePayment(){
        $email = $_POST['email'];
        $amount = $_POST['amount'];
        $store_id = "aamarpaytest";  // You have to use your Store ID / MerchantID here
        $signature_key="dbb74894e82415a2f7ff0ec3a97e4183"; // Your have to use your signature key here ,it will be provided by aamarPay
        $url = 'https://sandbox.aamarpay.com/jsonpost.php'; //sandbox
       // $url = 'https://secure.aamarpay.com/jsonpost.php'; //live url
       $success_url=base_url('payment/success');
       $fail_url=base_url('payment/fail');
       $cancel_url = base_url('payment/cancel');
       $tran_id = "test".rand(1111111,9999999); // Transection id need to be unique for each successful transection.

       $curl = curl_init();
       
       curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS =>'{
           "store_id": "'.$store_id.'",
           "tran_id": "'.$tran_id.'",
           "success_url": "'.$success_url.'",
           "fail_url": "'.$fail_url.'",
           "cancel_url": "'.$cancel_url.'",
           "amount": "'.$amount.'",
           "currency": "BDT",
           "signature_key": "'.$signature_key.'",
           "desc": "Merchant Registration Payment",
           "cus_name": "Customer Name",
           "cus_email": "'.$email.'",
           "cus_add1": "House B-158 Road 22",
           "cus_add2": "Mohakhali DOHS",
           "cus_city": "Dhaka",
           "cus_state": "Dhaka",
           "cus_postcode": "1206",
           "cus_country": "Bangladesh",
           "cus_phone": "0178273****",
           "type": "json"
       }',
         CURLOPT_HTTPHEADER => array(
           'Content-Type: application/json'
         ),
       ));
       
       $response = curl_exec($curl);
       
       curl_close($curl);
       
       $responseObj = json_decode($response);
       
       if(isset($responseObj->payment_url) && !empty($responseObj->payment_url)) {
            $paymentUrl = $responseObj->payment_url;
            header("Location: " . $paymentUrl);
            exit(); 
       }else{
           echo $response;
       }
    }

    
    public function successPayment(){
        $merTxnId = $_POST['mer_txnid'];
        $store_id = "aamarpaytest";  // You have to use your Store ID / MerchantID here
        $signature_key="dbb74894e82415a2f7ff0ec3a97e4183"; // Your have to use your signature key here ,it will be provided by aamarPay
        $url = "https://sandbox.aamarpay.com/api/v1/trxcheck/request.php?request_id=$merTxnId&store_id=$store_id&signature_key=$signature_key&type=json"; //sandbox
      //$url = "https://secure.aamarpay.com/api/v1/trxcheck/request.php?request_id=$merTxnId&store_id=$store_id&signature_key=$signature_key&type=json"; //live url
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);

        curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $a = (array)json_decode($buffer);
        echo "<pre>";
        print_r($a);
        echo "</pre>";

    }
    public function failedPayment(){
        $merTxnId = $_POST['mer_txnid'];
        $store_id = "aamarpaytest";  // You have to use your Store ID / MerchantID here
        $signature_key="dbb74894e82415a2f7ff0ec3a97e4183"; // Your have to use your signature key here ,it will be provided by aamarPay
        $url = "https://sandbox.aamarpay.com/api/v1/trxcheck/request.php?request_id=$merTxnId&store_id=$store_id&signature_key=$signature_key&type=json"; //sandbox
      //$url = "https://secure.aamarpay.com/api/v1/trxcheck/request.php?request_id=$merTxnId&store_id=$store_id&signature_key=$signature_key&type=json"; //live url
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL,$url);

        curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $a = (array)json_decode($buffer);
        echo "<pre>";
        print_r($a);
        echo "</pre>";
    }
    public function cancelPayment(){
        return view('payment');
    }
}