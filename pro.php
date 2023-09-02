<?php
	ob_start();
    include 'functions/class_init.php';
    include 'classes/character_class.php';
    include 'classes/user_new.php';

    session_start();
	include_once 'header.php';
    include 'classes/paypal_class_init.php';

    check_logged_in();

    $username = $_SESSION['Username'];

    if (isset($_GET['cancel_subscription'])){

        $reason = '{
            "reason": "Not satisfied with the service"
        }';

        $subscription_id = get_subscription_id($username,$conn);

        $pal = new pay_pal('1','2');
        $data = $pal->cancel_subscription($subscription_id,$reason);

        update_subscription_info($subscription_id,$conn);

        check_pro($username,$conn);

        header("Location: pro.php");
    }
?>

<script src="https://www.paypal.com/sdk/js?client-id=AfbFR55ET4JTokhIyNThzZhVASaxPFDOu4yrWHqAplZQWUfSWOgJjPGWOUIW3wgaizwhMaVMh4QzLZJt&vault=true&intent=subscription">
</script> 

<script>
    let width = screen.width;
    console.log(width);
    if (width < 1150){
        var content_width = "100%";
    }
    else {
        var content_width = "900px";
    }

    function show_modal(){


        let button = document.querySelector(".pro_button")

        button.onclick = function(){}

        let bar1  = document.querySelector(".gold_bar");
        bar1.style.transform = "translateX(0px)";

        let bar2  = document.querySelector(".right");
        bar2.style.transform = "translateX(0px)";

        let vertical = document.querySelector(".vertical_inner")
        vertical.style.transitionDelay = "";
        vertical.style.transform = "translateY(0px)";

        let vertical_bottom = document.querySelector(".vertical_inner_bottom")
        vertical_bottom.style.transitionDelay = "";
        vertical_bottom.style.transform = "translateY(0px)";

        let content = document.querySelector(".pro_content")
        content.style.transition = "";
        content.style.transitionDelay = "";
        content.style.border = "1px solid gold"
        content.style.height = "calc(100vh - 328px)";
        setTimeout(function(){content.style.width = content_width}, 1200);

        setTimeout(function(){
            button.querySelector(".x_container").style.transform = "translateY(0px)";
            button.querySelector(".x_container").style.height = "30px";
            button.querySelector(".x_container").style.opacity = "";
            button.style.color = "transparent";
        }, 3100);
    
        setTimeout(function(){
            button.querySelector(".span1").style.transform = "rotate(45deg)";
            button.querySelector(".span2").style.transform = "rotate(-45deg)";
        }, 3600);

        button.classList.add("active");

        setTimeout(function(){
            let titles = document.getElementsByClassName('plan_title');
            let images = document.getElementsByClassName('plan_image');
            let list = document.getElementsByClassName('bullet_list');

            for (let i = 0; i < titles.length; i++) {
                titles[i].style.opacity = "1";
                images[i].style.opacity = "1";
                list[i].style.opacity = "1";
            }
            vertical.style.transform = "";
            vertical.style.transition = "0s";
            document.querySelector(".pro_select_plan_title").style.display = "block";
        }, 3500);

        setTimeout(function(){
            button.onclick = function(){hide_modal()}
            // window.alert("here");
        }, 5000);
    }

    function hide_modal(){


        let plans = document.getElementsByClassName("plan")
        for (let i = 0; i < plans.length; i++) {
            plans[i].style.width = "";
            plans[i].classList.remove("active");
            plans[i].querySelector(".close_plan").style.opacity= "";
            setTimeout(function(){
                plans[i].onclick = function(){show_plan(this.id)};
            }, 100);
        }

        setTimeout(function(){
            let titles = document.getElementsByClassName('plan_title');
            let images = document.getElementsByClassName('plan_image');
            let list = document.getElementsByClassName('bullet_list');
            let payment_options = document.getElementsByClassName('payment_options');
            let image_containers = document.getElementsByClassName('image_container');

            for (let i = 0; i < titles.length; i++) {
                titles[i].style.opacity = "";
                images[i].style.opacity = "";
                list[i].style.opacity = "";
                image_containers[i].classList.remove("active");
                titles[i].classList.remove("active");
                list[i].classList.remove("active");
                payment_options[i].classList.remove("active");
            }
            vertical.style.transform = "translateY(0px)";
            vertical.style.transition = "0.5s";
            document.querySelector(".pro_select_plan_title").style.display = "";
        }, 200);

        let button = document.querySelector(".pro_button")

        button.onclick = function(){}

        button.querySelector(".span1").style.transform = "";
        button.querySelector(".span2").style.transform = "";

        setTimeout(function(){
            button.querySelector(".x_container").style.transform = "";
            button.querySelector(".x_container").style.height = "";
            button.querySelector(".x_container").style.opacity = "0";
            button.style.color = "";
        }, 300);

        let vertical_bottom = document.querySelector(".vertical_inner_bottom")
        vertical_bottom.style.transitionDelay = "1.2s";
        vertical_bottom.style.transform = "";

        let content = document.querySelector(".pro_content")
        content.style.transition = "0.8";
        content.style.transitionDelay = "0.7s";
        content.style.width = ""

        setTimeout(function(){
            content.style.height = ""
        }, 1000);

        setTimeout(function(){
            content.style.border = ""
        }, 2755);

        let vertical = document.querySelector(".vertical_inner")
        vertical.style.transitionDelay = "2.75s";
        vertical.style.transform = "";

        setTimeout(function(){
            let vertical = document.querySelector(".vertical_inner")
            // vertical.style.transitionDelay = "2.75s";
            vertical.style.transform = "";
        }, 2730);

        setTimeout(function(){
            let bar1  = document.querySelector(".gold_bar");
            bar1.style.transform = "";
            let bar2  = document.querySelector(".right");
            bar2.style.transform = "";
        }, 3200);


        setTimeout(function(){
            button.classList.remove("active");
            button.onclick = function(){show_modal()}
        }, 5000);

    }

    function show_plan(id){
        let plans = document.getElementsByClassName("plan")
        document.querySelector(".pro_content").style.overflow = "hidden";
        for (let i = 0; i < plans.length; i++) {
            if (width < 623){
                plans[i].style.width = "2%";
            }
            else {
                plans[i].style.width = "3%";
            }
            plans[i].style.height = "90%";
            plans[i].classList.remove("active");
            plans[i].querySelector(".close_plan").style.opacity= "";
            plans[i].querySelector(".plan_image").style.opacity = "";
            plans[i].querySelector(".plan_title").style.opacity = "";
            plans[i].querySelector(".bullet_list").style.opacity = "";
            plans[i].querySelector(".image_container").classList.remove("active");
            plans[i].querySelector(".bullet_list").classList.remove("active");
            plans[i].querySelector(".payment_options").classList.remove("active");
            plans[i].querySelector(".plan_title").classList.remove("active");
        }
        let plan = document.getElementById(id);
        plan.style.width = "81%";
        plan.classList.add("active");
        plan.querySelector(".image_container").classList.add("active");
        plan.querySelector(".bullet_list").classList.add("active");
        plan.querySelector(".payment_options").classList.add("active");
        plan.querySelector(".plan_title").classList.add("active");
        plan.querySelector(".close_plan").style.opacity = "0.8";
        plan.querySelector(".plan_image").style.opacity = "1";
        plan.querySelector(".plan_title").style.opacity = "1";
        plan.querySelector(".bullet_list").style.opacity = "1";
        plan.onclick = null;
    }

    function close_plan(id){
        let plans = document.getElementsByClassName("plan")
        document.querySelector(".pro_content").style.overflow = "";
        for (let i = 0; i < plans.length; i++) {
            plans[i].style.width = "";
            plans[i].style.height = "";
            plans[i].classList.remove("active");
            plans[i].querySelector(".close_plan").style.opacity= "";
            plans[i].querySelector(".plan_image").style.opacity = "1";
            plans[i].querySelector(".plan_title").style.opacity = "1";
            plans[i].querySelector(".bullet_list").style.opacity = "1";
            plans[i].querySelector(".image_container").classList.remove("active");
            plans[i].querySelector(".bullet_list").classList.remove("active");
            plans[i].querySelector(".payment_options").classList.remove("active");
            plans[i].querySelector(".plan_title").classList.remove("active");
            setTimeout(function(){
                plans[i].onclick = function(){show_plan(this.id)};
            }, 100);

        }
    }
</script>

<?php
if (is_pro($_SESSION['Username'],$conn) != "true"){

?>
<div class="pro_button" onclick = "show_modal()">
    Go Pro
    <div class="x_container">
        <div class="span1"></div>
        <div class="span2"></div>
    </div>
</div>

<div class="gold_bar_container">
    <div class="gold_bar"></div>
    <div class="gold_bar right"></div>
</div>

<div class="pro_select_plan_title">
    Select Your Desired Plan
</div>

<div class="vertical_bar">
    <div class="vertical_inner"></div>
</div>


<div class="pro_content">


    <div class="plan" id = "plan1" onclick = "show_plan(this.id)">
        <div class="close_plan" onclick = "close_plan(this.parentNode.id)"></div>
        <div class="plan_title month">£1.79</div>
        <div class="image_container">
            <div class="plan_image user" >
                <img src = "images/profile.png">
            </div>
        </div>
        <div class="bullet_list">
            <ul>
                <li>Access to all premium features</li>
                <li>Unlimited Refreshes</li>
                <li>Premium Discord Role</li>
                <li>Cancel Any Time</li>
            </ul>   
        </div>
        <div class="payment_options">

            <div id="paypal-button-container-P-8N861430XM104842NMIGPCPQ" class = 'payment_buttons'></div>
            <script>
            paypal.Buttons({
                createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': 'P-8N861430XM104842NMIGPCPQ'
                });
                },
                onApprove: function(data, actions) {
                let id = data.subscriptionID;
                let guild = "false";
                let username = '<?php echo $_SESSION['Username']; ?>'
                $.ajax({
                    url: "includes/new_subscription.inc.php",
                    method: "POST",   
                    data: {id: id, plan: "P-8N861430XM104842NMIGPCPQ", username: username, guild: guild},
                    success: function(data){console.log(data);},
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
	            });
                window.location.href = "team_builder.php?to_notify";
                
                }
            }).render('#paypal-button-container-P-8N861430XM104842NMIGPCPQ'); // Renders the PayPal button
            </script>

        </div>
    </div>

    <div class="plan" id = "plan3" onclick = "show_plan(this.id)">
        <div class="close_plan" onclick = "close_plan(this.parentNode.id)"></div>
        <div class="plan_title year">£14.99</div>
        <div class="image_container">
            <div class="plan_image user" >
                <img src = "images/profile.png">
            </div>
        </div>
        <div class="bullet_list">
            <ul>
                <li>Access to all premium features</li>
                <li>Unlimited Refreshes</li>
                <li>Premium Discord Role</li>
                <li>Cancel Any Time</li>
            </ul>   
        </div>
        <div class="payment_options">

            <div id="paypal-button-container-P-55C202774A060881NMIGPGXI" class = 'payment_buttons'></div>
            <script>
            paypal.Buttons({
                createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': 'P-55C202774A060881NMIGPGXI'
                });
                },
                onApprove: function(data, actions) {
                let id = data.subscriptionID;
                let guild = "false";
                let username = '<?php echo $_SESSION['Username']; ?>'
                $.ajax({
                    url: "includes/new_subscription.inc.php",
                    method: "POST",   
                    data: {id: id, plan: "P-55C202774A060881NMIGPGXI", username: username, guild: guild},
                    success: function(data){console.log(data);},
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
	            });
                window.location.href = "team_builder.php?to_notify";
                }
            }).render('#paypal-button-container-P-55C202774A060881NMIGPGXI'); // Renders the PayPal button
            </script>

        </div>
    </div>

    <div class="plan" id = "plan2" onclick = "show_plan(this.id)">
        <div class="close_plan" onclick = "close_plan(this.parentNode.id)"></div>

        <div class="plan_title month">£19.99</div>

        <div class="image_container">
            <div class="plan_image" >
                <img src = "images/people.png">
            </div>
        </div>
        <div class="bullet_list">
            <ul>
                <li>Access to all premium features</li>
                <li>Unlimited Refreshes</li>
                <li>Grant Access of all premium features to everyone in your guild</li>
                <li>Premium Discord Role</li>
                <li>Cancel Any Time</li>
            </ul>   
        </div>
        <div class="payment_options">

            <div id="paypal-button-container-P-419185478V387683FMIGPIII" class = 'payment_buttons'></div>
            <script>
            paypal.Buttons({
                createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': 'P-419185478V387683FMIGPIII'
                });
                },
                onApprove: function(data, actions) {
                let id = data.subscriptionID;
                let guild = "true";
                let username = '<?php echo $_SESSION['Username']; ?>'
                $.ajax({
                    url: "includes/new_subscription.inc.php",
                    method: "POST",   
                    data: {id: id, plan: "P-419185478V387683FMIGPIII", username: username, guild: guild},
                    success: function(data){console.log(data);},
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
	            });
                window.location.href = "team_builder.php?to_notify";
                }
            }).render('#paypal-button-container-P-419185478V387683FMIGPIII'); // Renders the PayPal button
            </script>

        </div>
    </div>



    <div class="plan" id = "plan4" onclick = "show_plan(this.id)">
        <div class="close_plan" onclick = "close_plan(this.parentNode.id)"></div>
        <div class="plan_title year">£79.99</div>
        <div class="image_container">
            <div class="plan_image" >
                <img src = "images/people.png">
            </div>
        </div>
        <div class="bullet_list">
            <ul>
                <li>Access to all premium features</li>
                <li>Unlimited Refreshes</li>
                <li>Grant Access of all premium features to everyone in your guild</li>
                <li>Premium Discord Role</li>
                <li>Cancel Any Time</li>

            </ul>   
        </div>
        <div class="payment_options">

            <div id="paypal-button-container-P-41U02599F21786028MIGPI5A" class = 'payment_buttons'></div>
            <script>
            paypal.Buttons({
                createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': 'P-41U02599F21786028MIGPI5A'
                });
                },
                onApprove: function(data, actions) {
                let id = data.subscriptionID;
                let guild = "true";
                let username = '<?php echo $_SESSION['Username']; ?>'
                $.ajax({
                    url: "includes/new_subscription.inc.php",
                    method: "POST",   
                    data: {id: id, plan: "P-41U02599F21786028MIGPI5A", username: username, guild: guild},
                    success: function(data){console.log(data);},
                    error: function(errMsg) {
                        alert(JSON.stringify(errMsg));
                    }
	            });
                window.location.href = "team_builder.php?to_notify";
                }
            }).render('#paypal-button-container-P-41U02599F21786028MIGPI5A'); // Renders the PayPal button
            </script>

        </div>
    </div>


</div>

<div class="vertical_bar_bottom">
    <div class="vertical_inner vertical_inner_bottom"></div>
</div>

<?php
}
else{
    $subscription_id = get_subscription_id($username,$conn);
    $state = get_subscription_state($subscription_id,$conn);
    
    if (is_payed_by($_SESSION['Username'],$conn) == NULL){

        if ($state == 'ACTIVE'){
        ?>
        <div style = 'text-align:center' class="pro_button" onclick = "cancel_subscription()">
            Cancel Subscription
            <div class="x_container">
                <div class="span1"></div>
                <div class="span2"></div>
            </div>
        </div>

        <script>
            function cancel_subscription(){
                let modal = document.querySelector(".modal")
                modal.style.display = "";
            }
            function close_cancel(){
                let modal = document.querySelector(".modal")
                modal.style.display = "none";
            }
            function confirmed_cancel(){
                window.location.href = "pro.php?cancel_subscription";
            }
        </script>

        <div class="modal" style = 'display:none'> 
			<div style = "border: 1px solid rgba(255,255,255,0.3);border-bottom: 1px solid gold" class="modal-content  animate" >
			<div class="modal-content-inner " style="width:70%;text-align: center;font-family: 'Raleway', sans-serif; ">
			Are you shure you would like to cancel your subscription?
            <div style = "width:100%;display:flex;justify-content:center;align-items:center"><button onclick = "confirmed_cancel()" style="width:auto;font-family: 'Raleway', sans-serif;" class="modal_save ">Cancel Subscription</button></div>

			</div>
			<a onclick = 'close_cancel()' class="close"></a>
			</div>
		</div>
    <?php
        }
        else{
            ?>
            <div style = 'text-align:center' class="pro_button" >
            Subscription Cancelled
            </div>
            <?php
        }
    }
    
}
?>