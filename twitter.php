<?php
require_once('TwitterAPIExchange.php');
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);

$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
if (isset($_GET['target_blank'])) {$target = ' target="_blank"';} else { $target = null;}
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
echo '
<style>

    
    .tweet{
        border-top: 0.2em solid #eee;
        padding: 0.5em;
        font-weight: 300;
        font-size: 1.15em;
    }
    
    .tweet_content{
        color: #555;
    }
    
    .tweet_content > a{
        color: #888;
    }
    
    .tweet_content > a:hover{
        color: #444;
    }
    
    .tweet_time{
        text-align: center;
        font-weight: bold;
        color: #bbb;
        padding-top: 0.4em;
    }
    
    .twitter-widget-header{
        font-weight: bold;
        background-color: #f5f5f5;
        border-bottom: 1px solid #d8d8d8;
        padding: 5px;
        margin: 0;
        margin-bottom: 6px;
    }
    
    .twitter-widget-logo{
        vertical-align: middle;
        display: inline-block;
        height: 1.5em;
        margin-right: 0.250em;
        right: 0;
    }
    
    .twitter-widget-header-text{
        font-weight: bold;
        display: inline-block;
        vertical-align: middle;
        line-height: 1.5em;
        color: rgp(57, 66, 78);
        margin-left: -.5em;
    }
    
    .twitter-widget-container{
        box-stying: border-box;
        font: 13px/1.4 Helvetica, arial, nimbussansl, liberationsans, freesans, clean, sans-serif, "Segoe UI Emoji", "Segoe UI Symbol"
        background-color #fff;
        border: 1px solid #d8d8d8;
        border-radius: 3px;
        color: #333;
        width: 350px; /* temporary for view */
        1px solid #d8d8d8 !important
    }
    
    .twitter-widget-header-link{
        outline: 0;
        font-weight: bold;
        vertical-align: middle;
        line-height: 1.5em;
        color: rgb(58, 66, 78);
        text-decoration: none;
    }
    
    .twitter-widget-profile-image
    {
        border-radius: 5px;
        width: 25%;
        display: inline-block;
        height: auto;
        max-width: 100%;
        vertical-align: middle;
        box-styling: border-box;
        word-wrap: break-word;
    }
    
    .twitter-widget-content{
        padding: .323em;
        font: 13px/1.4 Helvetica, arial, nimbussansl, liberationsans, freesans, clean, sans-serif, "Segoe UI Emoji", "Segoe UI Symbol";
    }
    
    .twitter_names{
        display: inline-block;
        vertical-align: top;
        margin-left: 3%;
    }
    
    .twitter_names_link{
        color: #54397e;
        text-decoration: none;
    }
    .twitter_name{
        color: #444;
        font-size: 1.15em;
        margin: 0 0 0.1em;
        font-weight: bold;
    }
    
    .twitter_username{
        font-size: 1.15em;
        font-weight: 300;
        margin: 0.1em 0;
        color: #666;
    }
    
    .twitter_description{
        padding: 0.5em 0 0 0;
    }
    
    .twitter_description > a{
        color: #888;
    }
    
    .twitter_description > a:hover{
        color: #444;
    }
    .twitter-widget-links{
        display: inline-block;
        width: 32%;
        padding: 0;
        margin: 0;
        text-align: center;
        border-bottom: 0.6em solid #fff;
    }
    
    .twitter-widget-links:hover{
        border-bottom: 0.6em solid #d8d8d8;
    }
    
    .twitter-widget-links-head{
        font-size: 1.15em;
        font-weight: 300;
        color: #666;
    }
    
    .twitter-widget-links-numbers{
        font-size: 1.4em;
        font-weight: bold;
        color: #444;
    }
    
    .twitter-widget-profile-link{
        text-decoration: none;
    }
    
    .twitter-widget-tweets-header{
        font-weight: 300;
        font-size: 1.15em;
        color: #777;
        line-height: 1.7em;
        text-align: center;
    }
</style>
';
echo '
<div class="twitter-widget-container">
    <div class="twitter-widget-header">
        <img class="twitter-widget-logo" title="Twitter" src="http://simpleicon.com/wp-content/uploads/twitter-2.png" />
        <div class="twitter-widget-header-text">
            <a ' . $target . ' href="https://twitter.com/' . $string[0]['user']['screen_name'] . '" class="twitter-widget-header-link">
                @' . $string[0]['user']['screen_name'] . ' (' . $string[0]['user']['name'] . ')
            </a>
        </div>
    </div>
    <div class="twitter-widget-content">
        <a ' . $target . ' href="https://twitter.com/' . $string[0]['user']['screen_name'] . '" class="twitter_names_link">
            <img src="' . $string[0]['user']['profile_image_url_https'] . '" class="twitter-widget-profile-image"/>
            <span class="twitter_names">
                <p class="twitter_name">' . 
                    $string[0]['user']['name'] . '
                </p>
                <span class="twitter_username">
                    @' . $string[0]['user']['screen_name'] . '
                </span>
            </span>
        </a>

 

        <br style="clear: both;" />
        <div class="twitter_description">' .
            getLinksFromTwitterText($string[0]['user']['description']) . '
        </div>
        
        <br style="clear: both;" />
        
        <a href="https://twitter.com/' . $string[0]['user']['screen_name'] .  '" ' . $target . ' class="twitter-widget-profile-link">
            <div class="twitter-widget-links">
                <div class="twitter-widget-links-head">
                    Tweets
                </div>
                <div class="twitter-widget-links-numbers">' . 
                    $string[0]['user']['statuses_count'] . '
                </div>
            </div>
        </a>
        <a href="https://twitter.com/' . $string[0]['user']['screen_name'] .  '/following" ' . $target . ' class="twitter-widget-profile-link">
            <div class="twitter-widget-links">
                <div class="twitter-widget-links-head">
                    Following
                </div>
                <div class="twitter-widget-links-numbers">' . 
                    $string[0]['user']['friends_count'] . '
                </div>
            </div>
        </a>
        <a href="https://twitter.com/' . $string[0]['user']['screen_name'] .  '/followers" ' . $target . ' class="twitter-widget-profile-link">
            <div class="twitter-widget-links">
                <div class="twitter-widget-links-head">
                    Followers
                </div>
                <div class="twitter-widget-links-numbers">' . 
                    $string[0]['user']['followers_count'] . '
                </div>
            </div>
        </a>
        <br style="clear: both;" />
    </div>';
    
if ($count != 0)
{
    echo '
    <div class="twitter-widget-tweets-header">
        Recent tweets
    </div>';
    
    foreach($string as $items)
        {

            $created_at = explode(" ", $items['created_at']);
            $created_at_time = explode(":", $created_at[3]);
            $created_at_string = $created_at_time[0] . ':' . $created_at_time[1] . ' ';
            $created_at_string .= ' - ';
            $created_at_string .= $created_at[2] . ' ' . $created_at[1] . ' ' . $created_at[5];

            echo '
            <div class="tweet">
                <div class="tweet_content">' . 
                    //$items['text'] . '
                    getLinksFromTwitterText($items['text']) . '
                </div>
                <div class="tweet_time">' . 
                    $created_at_string . '
                </div>
            </div>';


        }
}
echo '</div>';


function getLinksFromTwitterText($string)
{
    $string = preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" ' . $target . ' " title="$0">$0</a>', $string);
    // Converts hashtags to a link
    $string = preg_replace("/#([A-Za-z0-9\/\.]*)/", "<a href=\"http://twitter.com/search?q=$1\" " . $target . " >#$1</a>", $string);
    // Converts @user to a link
    $string = preg_replace("/@([A-Za-z0-9\/\.]*)/", "<a href=\"http://www.twitter.com/$1\" $target >@$1</a>", $string);
    return $string;
}
?>
