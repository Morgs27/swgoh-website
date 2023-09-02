<?php

class pay_pal
{
    private $user;
    private $get_token = "https://api-m.sandbox.paypal.com/v1/oauth2/token";
    private $product = "https://api-m.sandbox.paypal.com/v1/catalogs/products";
    private $plan = "https://api-m.sandbox.paypal.com/v1/billing/plans";
    private $subscription = "https://api-m.sandbox.paypal.com/v1/billing/subscriptions";

    public function __consturct($settings)
    {
        $this->client_id = $settings[0];
        $this->secret = $settings[1];
    }

    public function request($fetchUrl,$type,$headers,$fields)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $fetchUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $type,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function get_token()
    {
        $headers = array(
            'Authorization: Basic QWZiRlI1NUVUNEpUb2toSXlOVGh6WmhWQVNheFBGRE91NHlyV0hxQXBsWlFXVWZTV09nSmpQR1dPVUlXM3dnYWl6d2hNYVZNaDRRekxaSnQ6RUxUM0l1QWcyRE1vdlpPOXZ3ampHdTV6YnBnMHhEbzU5WGNWU0NqRU1wSTZYeVZjWTB4X0VjeVBiM3l2eWJJakpJRVFMUU9ETFZiSW5hRmc=',
            'Content-Type: application/x-www-form-urlencoded'
        );

        $fields = 'grant_type=client_credentials';

        $response = json_decode($this->request($this->get_token,'POST',$headers,$fields));

        return $response->access_token;
    }

    public function create_product($data)
    {
        $request_id = "plan5";

        $headers = array(
            'Authorization: Bearer ' . $this->get_token(),
            'PayPal-Request-Id:' . $request_id,
            'Content-Type: application/json'
        );

        $response = json_decode($this->request($this->product,'POST',$headers,$data));
        
        echo "Product:</br>";
        print_r($response);
        echo "</br></br>";

        return $response;

    }

    public function create_plan($data)
    {
        $request_id = "plan5";

        $headers = array(
            'Authorization: Bearer ' . $this->get_token(),
            'PayPal-Request-Id:' . $request_id,
            'Content-Type: application/json',
            'Accept: application/json'
        );

        $response = json_decode($this->request($this->plan, 'POST', $headers,$data));
        
        echo "</br>Plan:</br>";
        print_r($response);
        echo "</br></br>";

        return $response;
    }

    public function get_subscription_info($subscription_id)
    {

        $headers = array(
            'Authorization: Bearer ' . $this->get_token(),
            'Content-Type: application/json',
        );

        $curl = curl_init();

        $url = $this->subscription . "/" . $subscription_id;

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        // echo "</br>Subscription Info:</br>";
        // print_r($response);
        // echo "</br></br>";

        return $response;
    }
    public function cancel_subscription($subscription_id,$reason)
    {
 
        $headers = array(
            'Authorization: Bearer ' . $this->get_token(),
            'Content-Type: application/json',
        );

        $url = $this->subscription . "/" . $subscription_id . "/cancel";

        $response = json_decode($this->request($url, 'POST', $headers,$reason));
        
        // echo "</br>Plan:</br>";
        // print_r($response);
        // echo "</br></br>";

        return $response;
    }
}