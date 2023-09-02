<?php 
include '../functions/db_connect.php';

$subreddits = array("SWGOHRecruiting","swgoh_guilds");

$channels_guild = array("AhnaldT101" => "282298162973245440","SWGOH Events" => "505385935551070208","Hot Utils" => "638479232917307403","AP Hub" => "818563567069954048","The Gambit" => "592457682687098890");
$channels_personal = array("AhnaldT101" => "458664839905148939","SWGOH Events" => "924826760489607178","Hot Utils" => "834119568120348683","AP Hub" => "781983821452935218","The Gambit" => "754059305363439706");

date_default_timezone_set('UTC');

function update_reddit_posts($conn,$subreddits){
    

    $data = array();

    foreach ($subreddits as $subreddit){
        
        $instance = json_decode(file_get_contents("https://www.reddit.com/r/".$subreddit."/new.json"));
        $instance = $instance->data;
        $after = $instance->after;
        $children = $instance->children;
        $data = array_merge($data,$children);

        $last_child = end($children);
        $last_child_date = $last_child->data->created_utc;
        $last_child_date = date("Y-m-d H:i:s",$last_child_date);

        $last_week = date("Y-m-d H:i:s",strtotime("-7 days"));
       
        if ($last_child_date > $last_week){
            $done = false;
            while ($done == false){
                $instance = json_decode(file_get_contents("https://www.reddit.com/r/".$subreddit."/new.json?after=".$after));
                $instance = $instance->data;
                $after = $instance->after;
                $children = $instance->children;
                $data = array_merge($data,$children);
        
                $last_child = end($children);
                $last_child_date = $last_child->data->created_utc;
                $last_child_date = date("Y-m-d H:i:s",$last_child_date);
        
                $last_week = date("Y-m-d H:i:s",strtotime("-7 days"));
                
                if ($last_child_date < $last_week){
                    $done = true;
                }
            }
        }

        

    }

    $sql = "DELETE FROM reddit_posts";
    $result = $conn->query($sql);

    $sql = "INSERT INTO reddit_posts (subreddit,title,text,link,created,author,img_url,link_url) VALUES ";
    
    $x = 0;
    foreach($data as $child){
        $child = $child->data;
        $subreddit = $child->subreddit;
        $title = $child->title;
        $text = $child->selftext_html;
        $link = "https://reddit.com" . $child->permalink;
        $author = $child->author;
        $created = date("Y-m-d H:i:s",$child->created_utc);
        $hint = $child->post_hint;
        if ($hint == 'image'){
            $image_url = $child->url;
            $link_url = "None";
        }
        else if ($hint == 'link'){
            $link_url = $child->url;
        }
        else{
            $image_url = "None";
            $link_url = "None";
        }
        
        $text = str_replace("'","#z#",$text);
        $text = str_replace('"',"#z#",$text);

        $title = str_replace("'","#z#",$title);
        $title = str_replace('"',"#z#",$title);

        if ($x == 0 ){
            $sql_str = "('" . $subreddit . "','" . $title . "','" . $text . "','" . $link . "','" . $created . "','" . $author . "','" . $image_url . "','" . $link_url . "')";
        }
        else{
            $sql_str = ",('" . $subreddit . "','" . $title . "','" . $text . "','" . $link . "','" . $created . "','" . $author . "','" . $image_url . "','" . $link_url . "')";
        }

        $sql = $sql . $sql_str;
        $x += 1;
    }
    $result = $conn->query($sql);
}

function update_discord_posts($conn,$channels_guild,$channels_personal){

    $sql = "DELETE FROM discord_posts";
    $result = $conn->query($sql);

    foreach ($channels_guild as $channel){

        $server = array_search($channel,$channels_guild);

        echo $server;
       
        $api_key = 'NDAzMjU3MjEwMTE4ODY0ODk2.YiofqQ.k9t50ymj3mQZu6g14AL-0xmDhAA';
        $url = "https://discord.com/api/v9/channels/". $channel ."/messages?limit=100";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization:' . $api_key,
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($data);

        print_r($data);


        foreach ($data as $message){
            $content = $message->content;
            $author = $message->author->username;
            $embeds = $message->embeds;
            foreach ($embeds as $embed){
                $url = $embed->url;
                if (strpos($url,"swgoh.gg") !== false){
                    $gg_link = $url;
                }
                else {
                    $gg_link = "None";
                }
            }
            $attachments = $message->attachments;
            $image_url = "None";
            foreach($attachments as $attachment){
                $image_url = $attachment->url;
            }
            $timestamp = $message->timestamp;
            $timestamp = date("Y-m-d H:i:s",strtotime($timestamp));

            $sql = "INSERT INTO discord_posts (server,content,author,gg_link,img_url,timestamp,type)
             VALUES ('$server','$content','$author','$gg_link','$image_url','$timestamp','guild')";
            $result = $conn->query($sql);
        }
 
    }

    foreach ($channels_personal as $channel){

        $server = array_search($channel,$channels_personal);

        echo $server;
       
        $api_key = 'NDAzMjU3MjEwMTE4ODY0ODk2.YiofqQ.k9t50ymj3mQZu6g14AL-0xmDhAA';
        $url = "https://discord.com/api/v9/channels/". $channel ."/messages?limit=100";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization:' . $api_key,
            'Content-Type: application/x-www-form-urlencoded'
        ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($data);

        print_r($data);


        foreach ($data as $message){
            $content = $message->content;
            $author = $message->author->username;
            $embeds = $message->embeds;
            foreach ($embeds as $embed){
                $url = $embed->url;
                if (strpos($url,"swgoh.gg") !== false){
                    $gg_link = $url;
                }
                else {
                    $gg_link = "None";
                }
            }
            $attachments = $message->attachments;
            $image_url = "None";
            foreach($attachments as $attachment){
                $image_url = $attachment->url;
            }
            $timestamp = $message->timestamp;
            $timestamp = date("Y-m-d H:i:s",strtotime($timestamp));

            $sql = "INSERT INTO discord_posts (server,content,author,gg_link,img_url,timestamp,type)
             VALUES ('$server','$content','$author','$gg_link','$image_url','$timestamp','personal')";
            $result = $conn->query($sql);
        }
 
    }
}   

update_reddit_posts($conn,$subreddits);

update_discord_posts($conn,$channels_guild,$channels_personal);