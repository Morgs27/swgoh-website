<?php 
include '../functions/db_connect.php';


$subreddits = array("SWGOHRecruiting","swgoh_guilds");

// $channels_guild = array("AhnaldT101" => "282298162973245440","SWGOH Events" => "505385935551070208","Hot Utils" => "638479232917307403","AP Hub" => "818563567069954048","The Gambit" => "592457682687098890");
// $channels_personal = array("AhnaldT101" => "458664839905148939","SWGOH Events" => "924826760489607178","Hot Utils" => "834119568120348683","AP Hub" => "781983821452935218","The Gambit" => "754059305363439706");


$channels_guild = array("AhnaldT101" => "282298162973245440", "SWGOH Events" => "1190698749442392266", "Hot Utils" => "638479232917307403" );
$channels_personal = array("AhnaldT101" => "458664839905148939", "SWGOH Events" => "924826760489607178", "Hot Utils" => "834119568120348683");


date_default_timezone_set('UTC');

function update_reddit_posts($conn,$subreddits){
    
    

    $data = array();

    foreach ($subreddits as $subreddit){

        $url = "https://www.reddit.com/r/".$subreddit."/new.json";

        // Set user agent
        $userAgent = 'Swgoh'; // Replace this with your own user agent string

        // Create a stream context to include the user-agent header
        $options = [
            'http' => [
                'header' => "User-Agent: $userAgent"
            ]
        ];
        $context = stream_context_create($options);

        // Fetch the JSON content
        $json = file_get_contents($url, false, $context);

        // Decode the JSON content
        $instance = json_decode($json, true);

        $instance = $instance['data']['children'];

        $data = array_merge($data,$instance);
    }

    $sql = "DELETE FROM reddit_posts";
    $result = $conn->query($sql);

    $sql = "INSERT INTO reddit_posts (subreddit,title,text,link,created,author,img_url,link_url) VALUES ";
    
    $x = 0;
    foreach($data as $child){
        if (is_array($child)){
            $child = $child['data'];
        }
        else{
            continue;
        }
        $subreddit = $child['subreddit'];
        $title = $child['title'];
        $text = $child['selftext_html'];
        $link = "https://reddit.com" . $child['permalink'];
        $author = $child['author'];
        $created = date("Y-m-d H:i:s",$child['created_utc']);
        
        if (array_key_exists('post_hint',$child)){
            $hint = $child['post_hint'];
        }
        else{
            $hint = "None";
        }

        if ($hint == 'image'){
            $image_url = $child['url'];
            $link_url = "None";
        }
        else if ($hint == 'link'){
            $link_url = $child['url'];
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
    // echo $sql;
    $conn->set_charset('utf8mb4');
    $result = $conn->query($sql);
    print_r($conn->error);
}



function get_messages($channel_code){

    echo "</br> Getting Messages </br>";

    $api_key = 'OTM1NjkxMTc3MDc4OTYwMTU5.GoNZcJ.zUCClAVG9FpTypm3VrnpCIm48aeFJUD4Z7xdzU';

    // $url = "https://discord.com/api/v9/channels/". $channel_code ."/messages?limit=100";

    $url = "https://discord.com/api/v9/channels/". $channel_code . "/messages?limit=100";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Authorization: ' . $api_key,
        'Cookie: __cfruid=995ecdace9fff40823674f53aeea1bd619cc40ed-1715776082; __dcfduid=92855f5612b611ef8d5b72129c9eb4c5; __sdcfduid=92855f5612b611ef8d5b72129c9eb4c5eb7a0f0efa54e5e9ae9a998eb1303e3e3a5b1b480e6ead8bafbd68aa01df8ce7; _cfuvid=CokG8psygF7qwGuIYI4VlxNU34dx8jG3irH._FUOZt0-1715776082350-0.0.1.1-604800000'
      ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

    print_r($response);

    $data = json_decode($response);

    return $data;

}

function update_discord_posts($conn,$channels_guild,$channels_personal){

    $sql = "DELETE FROM discord_posts";
    $result = $conn->query($sql);

    foreach ($channels_guild as $channel){

        $server = array_search($channel,$channels_guild);
       
        $data = get_messages($channel);


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

        $data = get_messages($channel);

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