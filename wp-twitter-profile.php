<?php
/*
Plugin Name: Twitter profile widget
Plugin URI: 
Description: Add your twitter profile onto your wordpress website
Version: 1.0
Author: Jim Valentine - f13dev
Author URI: http://f13dev.com
License: GPL2
*/

/**
 * Register the widget
 */
add_action('widgets_init', create_function('', 'return register_widget("WP_Twitter_profile_widget");'));

/**
 * Class Widget_Better_Starter_Widget
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
		//We're going to use $this->textdomain as both the translation domain and the widget class name and ID
		$this->textdomain = strtolower(get_class($this));

		//Figure out your textdomain for translations via this handy debug print
		//var_dump($this->textdomain);
		
		//Add fields
		$this->add_field('title', 'Enter title', '', 'text');
		$this->add_field('twitter_id', 'Twitter ID', '', 'text');
		$this->add_field('twitter_count', 'Number of tweets to show (enter \'0\' to only show your profile)', '', 'text');
		$this->add_field('access_token', 'Access token', '', 'text');
		$this->add_field('access_token_secret', 'Access token secret', '', 'text');
		$this->add_field('consumer_key', 'API key', '', 'text');
		$this->add_field('consumer_key_secret', 'API key secret', '', 'text');
		$this->add_field('twitter_target', 'Open links in a new window (either enter \'blank\' or leave empty)', 'blank', 'text');

		//Translations
		load_plugin_textdomain($this->textdomain, false, basename(dirname(__FILE__)) . '/languages' );

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

		/* Before and after widget arguments are usually modified by themes */
		echo $args['before_widget'];

		if (!empty($title))
			echo $args['before_title'] . $title . $args['after_title'];

		/* Widget output here */
		$this->widget_output($args, $instance);

		/* After widget */
		echo $args['after_widget'];
	}
	
	/**
	 * This function will execute the widget frontend logic.
	 * Everything you want in the widget should be output here.
	 */
	private function widget_output($args, $instance)
	{
		extract($instance);

		/**
		 * This is where you write your custom code.
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
		?>
			<br/>
			Use this widget to add a mini version of your twitter profile as a widget<br/>
			<br/>
			Get your API and Access token details by creating an app at https://apps.twitter.com/, the widget only requires read access. Copy and paste the details to the associated fields below to authorise the widget.<br/>
			<br/>
		<?php
		/* Generate admin for fields */
		foreach($this->fields as $field_name => $field_data)
		{
			if($field_data['type'] === 'text'):
				?>
				<p>
					<label for="<?php echo $this->get_field_id($field_name); ?>"><?php _e($field_data['description'], $this->textdomain ); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id($field_name); ?>" name="<?php echo $this->get_field_name($field_name); ?>" type="text" value="<?php echo esc_attr(isset($instance[$field_name]) ? $instance[$field_name] : $field_data['default_value']); ?>" />
				</p>
			<?php
			//elseif($field_data['type'] == 'textarea'):
			//You can implement more field types like this.
			else:
				echo __('Error - Field type not supported', $this->textdomain) . ': ' . $field_data['type'];
			endif;
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
