<?php
/*
Plugin Name: Twitter profile widget
Plugin URI:
Description: Add your twitter profile onto your wordpress website
Version: 1.1.0
Author: Jim Valentine - f13dev
Author URI: http://f13dev.com
License: MIT
*/

/**
 * Register the widget
 */
add_action('widgets_init', create_function('', 'return register_widget("WP_Twitter_profile_widget");'));
// Register the CSS
add_action( 'wp_enqueue_scripts', 'f13_twitter_stylesheet');

function f13_twitter_stylesheet()
{
    wp_register_style( 'f13twitter-style', plugins_url('twitter.css', __FILE__));
    wp_enqueue_style( 'f13twitter-style' );
}

/**
 * Class WP_twitter_profile_widget
 */
class WP_Twitter_profile_widget extends WP_Widget
{
	/** Basic Widget Settings */
	const WIDGET_NAME = "Twitter profile widget";
	const WIDGET_DESCRIPTION = "Using this profile you may add a snapshot of your twitter profile to a widget area on your wordpress site.";

	var $textdomain;
	var $fields;

	/**
	 * Construct the widget
	 */
	function __construct()
	{
		$this->textdomain = strtolower(get_class($this));

		//Add fields
		$this->add_field('title', 'Enter title', '', 'text');
		$this->add_field('twitter_id', 'Twitter ID', '', 'text');
		$this->add_field('twitter_count', 'Number of tweets to show (enter \'0\' to only show your profile)', '', 'number');
		$this->add_field('access_token', 'Access token', '', 'text');
		$this->add_field('access_token_secret', 'Access token secret', '', 'password');
		$this->add_field('consumer_key', 'API key', '', 'text');
		$this->add_field('consumer_key_secret', 'API key secret', '', 'password');
		$this->add_field('twitter_target', 'Open links in a new tab)', '', 'checkbox');
		$this->add_field('twitter_timeout', 'Cache timout in minutes', '30', 'number' );

		//Init the widget
		parent::__construct($this->textdomain, __(self::WIDGET_NAME, $this->textdomain), array( 'description' => __(self::WIDGET_DESCRIPTION, $this->textdomain), 'classname' => $this->textdomain));
	}

	/**
	 * Widget frontend
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance)
	{
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];

		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];


		$this->widget_output($args, $instance);

		echo $args['after_widget'];
	}

	/**
	 * Function to load the widget
	 */
	private function widget_output($args, $instance)
	{
		extract($instance);

		/**
		 * Require the twitter.php file to load the widget content
		 */
		require_once('twitter.php');
	}

	/**
	 * Widget backend
	 *
	 * @param array $instance
	 * @return string|void
	 */
	public function form( $instance )
	{
		/**
		 * Create a header with basic instructions.
		 */
		?>
			<br/>
			Use this widget to add a mini version of your twitter profile as a widget<br/>
			<br/>
			Get your API and Access token details by creating an app at https://apps.twitter.com/, the widget only requires read access. Copy and paste the details to the associated fields below to authorise the widget.<br/>
			<br/>
		<?php
		/* Generate admin form fields */
		foreach($this->fields as $field_name => $field_data)
		{
			if($field_data['type'] === 'text')
			{
				?>
				<p>
					<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_data['description'], $this->textdomain ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="text" value="<?php echo esc_attr(isset($instance[$field_name]) ? $instance[$field_name] : $field_data['default_value']); ?>" />
				</p>
			<?php
			}
			elseif($field_data['type'] === 'number')
			{
				?>
				<p>
					<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_data['description'], $this->textdomain ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="number" value="<?php echo esc_attr(isset($instance[$field_name]) ? $instance[$field_name] : $field_data['default_value']); ?>" />
				</p>
			<?php
			}
			elseif($field_data['type'] === 'password')
			{
				?>
				<p>
					<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_data['description'], $this->textdomain ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="password" value="<?php echo esc_attr(isset($instance[$field_name]) ? $instance[$field_name] : $field_data['default_value']); ?>" />
				</p>
			<?php
			}
			elseif($field_data['type'] === 'checkbox')
			{
			?>
				<p>
					<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_data['description'], $this->textdomain ); ?></label><br />
					<input id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="checkbox"
					<?php
					if (esc_attr($instance[$field_name]) == true)
					{
						echo ' checked';
					}
					?>
					/>
				</p>
			<?php
			}
			/* Otherwise show an error */
			else
			{
				echo __('Error - Field type not supported', $this->textdomain) . ': ' . $field_data['type'];
			}
		}
	}

	/**
	 * Adds a text field to the widget
	 *
	 * @param $field_name
	 * @param string $field_description
	 * @param string $field_default_value
	 * @param string $field_type
	 */
	private function add_field($field_name, $field_description = '', $field_default_value = '', $field_type = 'text')
	{
		if(!is_array($this->fields))
			$this->fields = array();

		$this->fields[$field_name] = array('name' => $field_name, 'description' => $field_description, 'default_value' => $field_default_value, 'type' => $field_type);
	}

	/**
	 * Updating widget by replacing the old instance with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update($new_instance, $old_instance)
	{
		return $new_instance;
	}
}
