WP Twitter profile
=============
Add a twitter widget to your wordpress website to show a snapshot of your profile and your latest tweets.

This widget utilises code from the [J7mbo/twitter-api-php](https://github.com/J7mbo/twitter-api-php) repository and the [khromov/wp-better-starter-widget](https://github.com/khromov/wp-better-starter-widget).

This is my first Wordpress widget, so bare with me if there are any errors or better ways of writing Wordpress code, I'm always happy to learn how to better my code.

How to use
----------
1. Clone this repository, 'git clone https://github.com/f13dev/WP-Twitter-profile'
2. Upload the 'WP-Twitter-profile' direcotry to your wordpress website under 'wp-content/plugins/'
3. In the Wordpress admin panel, under plugins, activate 'Twitter profile widget'.
4. Under 'Apearance > Widgets', drag 'Twitter profile widget' to the desired widget area to activate it.
5. Head over to https://apps.twitter.com/ and create a new application, write access is not required.
6. Copy and paste your 'Access token', ' Access token secret', 'API key' and 'API key secret' to the appropriate fields for the widget settings.
7. Enter your twitter username into the 'Twitter ID' field

Additional settings
-------------------
You may choose the number of tweets to display under your profile by entering a number in the 'Number of tweets to show' field; if you enter '0', then only your profile will be displayed, if the field is left blank then a default of 3 tweets will be shown.

You may choose to have links open in a new tab by entering 'blank' in the 'Open links in a new window' field; if any other value is entered into this field, links will open in the current window.

To do
-----
1. Lots of code tidying
2. Security testing
3. Package and add it to the Wordpress plugins site

Screen shots
------------
![alt tag](http://f13dev.com/git_images/WP-Twitter-profile_1.png)

**This is not currently intended for implementation**