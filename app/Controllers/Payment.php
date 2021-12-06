<?php

namespace App\Controllers;

class Payment extends BaseController
{
    public function index()
    {
        return view('payment');
    }

    public function initiatePayment(){

        $email = $_POST['email'];
        $amount = $_POST['amount'];
        $value='';
        $key='';
        $fields_string='';

        $url = 'https://sandbox.aamarpay.com/request.php'; //sandbox
       // $url = 'https://secure.aamarpay.com/request.php'; //live url

        $fields = array(
            'store_id' => '', //enter your store id
            'amount' => $amount, 
            'payment_type' => 'VISA',
            'currency' => 'BDT', 
            'tran_id' => rand(111111111111,999999999999), //make unique transaction ID for each transaction 
            'cus_name' => 'abcd', 
            'cus_email' => $email,
            'cus_add1' => 'Dhaka',
            'cus_add2' => 'Mohakhali DOHS',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1206',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '234234234',
            'cus_fax' => 'Not¬Applicable',
            'ship_name' => '234234',
            'ship_add1' => 'House B-121, Road 21', 'ship_add2' => 'Mohakhali',
            'ship_city' => 'Dhaka', 'ship_state' => 'Dhaka',
            'ship_postcode' => '1212', 'ship_country' => 'Bangladesh',
            'desc' => '234234aef', 'success_url' => base_url('payment/success'),
            'fail_url' => base_url('payment/fail'),
            'cancel_url' => base_url('payment/cancel'),
            'opt_a' => '', 'opt_b' => '',
            'opt_c' => '', 'opt_d' => '',
            'signature_key' => '');
            
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        $fields_string = rtrim($fields_string, '&'); 
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POST, count($fields)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));	
        curl_close($ch); 
     

        $this->redirect_to_merchant($url_forward);
    }

    public function redirect_to_merchant($url) {

        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
          <head><script type="text/javascript">
            function closethisasap() { document.forms["redirectpost"].submit(); } 
          </script></head>
          <body onLoad="closethisasap();">
          
            <form name="redirectpost" method="post" action="<?php echo 'https://sandbox.aamarpay.com/'.$url; ?>"></form>
            <!-- for live url https://secure.aamarpay.com -->
          </body>
        </html>
        <?php	
        exit;
    } 

    public function successPayment(){
        var_dump($_POST);
    }
    public function failedPayment(){
        var_dump($_POST);
    }
    public function cancelPayment(){
        return view('payment');
    }
}