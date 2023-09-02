<?php
include 'functions/class_init.php';
ob_start();
session_start();

include 'header.php';
include 'functions/upload_to_database.php';
include 'classes/paypal_class_init.php';


if ($_SESSION["Username"] == "Morgs27"){

    ?>
    <div class = "admin_buttons">
        <a href = 'paypal_admin.php?create_plan'>Create Plan</a>
        <a href = 'paypal_admin.php?subscription_info'>Subscription Info</a>
        <a href = 'paypal_admin.php?cancel_subscription'>Cancel Subscription </a>
        <a href = 'paypal_admin.php?check_pro'>Check Pro</a>
        <a href = 'paypal_admin.php?clear_pro'>Clear All Pro</a>
        <a href = 'paypal_admin.php?update_subscription_info'>Update Subscription Info</a>
    </div>
    <?php

    if (isset($_GET['create_plan'])) {
        
        echo "<div class = 'admin_results'>";

        $data_product = '{
            "name": "Pro Version",
            "description": "Single",
            "type": "SERVICE",
            "category": "SOFTWARE",
            "home_url": "https://swgohteammanager.com/index.php"
        }';

        $pal = new pay_pal('1','2');

        $token = $pal->get_token();

        $product = $pal->create_product($data_product);

        $product_id = json_encode($product->id);

        $price = "79.99";

        $data_plan = '{
            "product_id": ' . $product_id . ',
            "name": "SWGOH TM Pro Version (Guild Plan)",
            "billing_cycles": [
              {
                "frequency": {
                  "interval_unit": "YEAR",
                  "interval_count": 1
                },
                "tenure_type": "REGULAR",
                "sequence": 1,
                "total_cycles": 0,
                "pricing_scheme": {
                  "fixed_price": {
                    "value": '. $price . ',
                    "currency_code": "GBP"
                  }
                }
              }
            ],
            "payment_preferences": {
              "auto_bill_outstanding": true
              },
              "setup_fee_failure_action": "CONTINUE",
              "payment_failure_threshold": 3
            },
            "taxes": {
              "percentage": "10",
              "inclusive": false
            }
          }';

        echo "</br>Data Plan:</br>";
        echo $data_plan;
        echo "</br></br>";

        $plan = $pal->create_plan($data_plan);

        $plan_id = $plan->id;
        $interval = "month";
        $name = $plan->name;
        $product_id = $plan->product_id;

        echo "Plan Id: " . $plan_id;

        echo "</div>";

        ?>
        <script src="https://www.paypal.com/sdk/js?client-id=AfbFR55ET4JTokhIyNThzZhVASaxPFDOu4yrWHqAplZQWUfSWOgJjPGWOUIW3wgaizwhMaVMh4QzLZJt&vault=true&intent=subscription">
        </script> 

        <div id="paypal-button-container" style = 'margin-top:30px;display:flex;justify-content:center;align-items:center;'></div>

        <script>

        let plan_id = '<?php echo $plan_id; ?>'
        let username = '<?php echo $_SESSION['Username']; ?>'

        paypal.Buttons({
            createSubscription: function(data, actions) {
            return actions.subscription.create({
                'plan_id': plan_id // Creates the subscription
            });
            },
            onApprove: function(data, actions) {
            let id = data.subscriptionID;
            let guild = "true";
            $.ajax({
                url: "new_subscription.php",
                method: "POST",   
                data: {id: id, plan: plan_id, username: username, guild: guild},
                success: function(data){console.log(data);},
                error: function(errMsg) {
                    alert(JSON.stringify(errMsg));
                }
	        });

            alert('You have successfully created subscription ' + data.subscriptionID); // Optional message given to subscriber
            }
        }).render('#paypal-button-container'); // Renders the PayPal button
        </script>

        
        <?php

        $sql = "SELECT * FROM paypal_plans WHERE paypal_plan_id = '$plan_id'";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result) > 0){
            echo "Plan Already Been Created";
        }
        else {
            $sql = "INSERT INTO paypal_plans (paypal_plan_id,cost,interval_length,name,product_id) VALUES ('$plan_id','$price','$interval','$name','$product_id')";
            $result = $conn->query($sql);

        }

    }

    if (isset($_GET['subscription_info'])) {

        echo "<div class = 'admin_results'>";

        $pal = new pay_pal('1','2');
        $data = $pal->get_subscription_info("I-E8FLR892SRW4");

        echo "</div>";
    }

    if (isset($_GET['cancel_subscription'])) {

        echo "<div class = 'admin_results'>";

        $reason = '{
            "reason": "Not satisfied with the service"
        }';

        $pal = new pay_pal('1','2');
        $data = $pal->cancel_subscription("I-LYW7WEFATD3X",$reason);

        echo "</div>";
    }

    if (isset($_GET['check_pro'])) {

        echo "<div class = 'admin_results'>";

        check_pro($_SESSION['Username'],$conn);

        echo "</div>";
    }

    if (isset($_GET['clear_pro'])) {

        echo "<div class = 'admin_results'>";

        clear_all_pro($conn);

        echo "</div>";
    }

    if (isset($_GET['update_subscription_info'])) {

        echo "<div class = 'admin_results'>";

        update_subscription_info("I-LYW7WEFATD3X",$conn);

        echo "</div>";
    }

}
else {
    header("location:index.php");
}