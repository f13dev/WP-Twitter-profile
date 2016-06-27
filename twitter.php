<?php
// Load the twitter API PHP by J7mbo/twitter-api-php
require_once('TwitterAPIExchange.php');
// Set the access tokens
$settings = array(
    'oauth_access_token' => $access_token,
    'oauth_access_token_secret' => $access_token_secret,
    'consumer_key' => $consumer_key,
    'consumer_secret' => $consumer_key_secret
);
// Set the API url and request method
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";

// if $twitter_target is blank set $target
if ($twitter_target == 'blank')
{
    $target = ' target="_blank" ';
}
else
{
    $target = '';
}

// Check if $twitter_count is a number, if not set it to 3
if (!is_numeric($twitter_count))
{
    $twitter_count = 3;
}

// Set the get field to call the twitter username and the number of tweets to retrieve
$getfield = "?screen_name=$twitter_id&count=$twitter_count";
// Create a new instance of TwitterAPIExchange
$twitter = new TwitterAPIExchange($settings);
// Store the decoded reply
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);

if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}

//print_r($string);
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
        color: #' . $string[1]['user']['profile_link_color'] . ';
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
        border-radius: 5px;
        color: #333;
        width: 100%; /* temporary for view */
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
        width: 23%;
        display: inline-block;
        height: auto;
        max-width: 100%;
        vertical-align: middle;
        box-styling: border-box;
        word-wrap: break-word;
        margin-top: -35px;
        margin-left: 10px;
        border: 3px solid white;
    }

    .twitter-widget-content{
        padding: .323em;
        font: 13px/1.4 Helvetica, arial, nimbussansl, liberationsans, freesans, clean, sans-serif, "Segoe UI Emoji", "Segoe UI Symbol";
    }

    .twitter_names{
        display: inline-block;
        vertical-align: top;
        margin-left: 3%;
        margin-top: 5px;
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
        margin-top: -0.5em;
    }

    .twitter_description > a{
        color: #' . $string[1]['user']['profile_link_color'] . ';
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
        border-bottom: 0.6em solid #' . $string[1]['user']['profile_link_color'] . ';
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

    .tweet_link{
        text-decoration: none;
    }

    .tweet_media{
        max-width: 100%;
        max-height: 300px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .twitter-follow-button{
        display: block;
        background-color: #fff;
        width: 50%;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
        padding: 0.5em;
        border-radius: 7px;
        color: #' . $string[1]['user']['profile_link_color'] . ';
        text-decoration: none;
        margin-top: 1em;
        border: 3px solid #' . $string[1]['user']['profile_link_color'] . ';
    }

    .twitter-follow-button:hover{
        background-color: #' . $string[1]['user']['profile_link_color'] . ';
        color: #fff;
    }

    .twitter-banner{
        width: 100%;
        height: auto;
        max-height: 200px;
        border-radius: 5px 5px 0 0;
    }
</style>
';
echo '
<div class="twitter-widget-container">

    <div class="twitter-widget-head-bar">
            <img src="' . $string[0][user][profile_banner_url] . '" class="twitter-banner" />
            <a ' . $target . ' href="https://twitter.com/' . $string[0]['user']['screen_name'] . '" class="twitter_names_link">
                <img src="' . str_replace('normal', '400x400', $string[0]['user']['profile_image_url_https']) . '" class="twitter-widget-profile-image"/>
                <span class="twitter_names">
                    <p class="twitter_name">' .
                        $string[0]['user']['name'] . '
                    </p>
                    <span class="twitter_username">
                        @' . $string[0]['user']['screen_name'] . '
                    </span>
                </span>
            </a>
        </div>
    <div class="twitter-widget-content">



        <br style="clear: both;" />
        <div class="twitter_description">' .
            getLinksFromTwitterText($string[0]['user']['description']) . '
        </div>

        <a class="twitter-follow-button" href="https://twitter.com/intent/follow?screen_name=' . $string[0]['user']['screen_name'] . '" data-size="large" data-width="960" data-height="600"> Follow @' . $string[0]['user']['screen_name'] . '</a>

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

if ($twitter_count != 0)
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
                    getLinksFromTwitterText($items['text']) .
                    '<a href="' . $items['entities']['media'][0]['url'] . '" ' . $target . ' class="tweet_link" />';
                    if ($items['entities']['media'][0]['media_url'] != '')
                    {
                        echo '
                            <img src="' . $items['entities']['media'][0]['media_url'] . '" class="tweet_media" />';
                    }
                    echo '
                    </div>
                    <div class="tweet_time">' .
                        $created_at_string . '
                    </div>
                </a>
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
