<?php
require_once('twitter-api-php/TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
if (isset($_GET['user']))  {$user = $_GET['user'];}  else {$user  = "f13dev";}
if (isset($_GET['count'])) {$count = $_GET['count'];} else {$count = 20;}
$getfield = "?screen_name=$user&count=$count";
$twitter = new TwitterAPIExchange($settings);
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);

if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}

/**
  * Build profile section
  */
echo '<div style="float: left">';
    echo '<img src="' . $string[0]['user']['profile_image_url_https'] . '" style="border-radius: 5px;"/><br />';
echo '</div>';
echo '<div style="float: left">';
    echo '<a href="https://twitter.com/' . $string[0]['user']['screen_name'] . '" target="_blank">';
        echo $string[0]['user']['name'];
    echo '</a>';
    echo '<br />';
    echo $string[0]['user']['screen_name'];
echo '</div>';
echo '<br style="clear: both;" />';
echo '<div>';
    echo $string[0]['user']['description'];
echo '</div>';
echo '<div>';
    echo 'Tweets: ' . $string[0]['user']['statuses_count'] . '<br />';
    echo 'Followers: ' . $string[0]['user']['followers_count'] . '<br />';
    echo 'Friends: ' . $string[0]['user']['friends_count'] . '<br />';
    echo 'Followers: ' . $string[0]['user']['follower_count'] . '<br />';
    echo 'Listed: ' . $string[0]['user']['listed_count'] . '<br />';
echo '</div>';

if ($count != 0)
{
    foreach($string as $items)
        {

            $created_at = explode(" ", $items['created_at']);
            $created_at_time = explode(":", $created_at[3]);
            $created_at_string = $created_at_time[0] . ':' . $created_at_time[1] . ' ';
            $created_at_string .= ' - ';
            $created_at_string .= $created_at[2] . ' ' . $created_at[1] . ' ' . $created_at[5];


            echo "<hr />";
            echo $items['text']."<br />";
            echo $created_at_string . "<br />";


        }
}
?>