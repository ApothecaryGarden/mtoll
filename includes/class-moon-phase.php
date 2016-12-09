<?php
/**
 * Mtoll Moon_phase
 * @version 1.0.0
 * @package Mtoll
 */

class M_Moon_phase extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_slug = 'mtoll-moon-phase';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected static $shortcode = 'mtoll-moon-phase';


	/**
	 * Construct widget class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Mtoll Moon_phase', 'mtoll' );
		$this->default_widget_title = esc_html__( 'Mtoll Moon_phase', 'mtoll' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'A widget boilerplate description.', 'mtoll' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @since  1.0.0
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
			'text'          => $instance['text'],
		) );
	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @since  1.0.0
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		$widget = '';

		// Set up default values for attributes.
		$atts = shortcode_atts(
			array(
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
				'text'          => '',
			),
			(array) $atts,
			self::$shortcode
		);

		$math = self::math();

		// Before widget hook.
		$widget .= $atts['before_widget'];

		// Title.
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		// Display
		$widget .= '<br />';
		$widget .= '<center><img src="' . $math['image'] . '" alt="' . $math['phase'] . '" title="' . $math['phase'] . '" width="128" height="128" /></center>';
		$widget .= '<center><b>' . $math['phase'] . '</b></center>';
		$widget .= '<br />';


		// Zodiac
		$widget .= '<center>';
		$widget .= sprintf(__('The moon is currently in %s', 'moon-phases'), $math['zodiac']);
		$widget .= '</center>';

		// Age
		$widget .= '<center>';
		$widget .= sprintf(_n('The moon is %d day old', 'The moon is %d days old', $math['age'], 'moon-phases'), $math['age']);
		$widget .= '</center>';


		// Details
	//	$widget .= '<br />';
	//	$widget .= sprintf(__('Distance: %d earth radii', 'moon-phases'), $math['distance']);
	//	$widget .= '<br />';
	//	$widget .= sprintf(__('Ecliptic latitude: %d degrees', 'moon-phases'), $math['latitude']);
	//	$widget .= '<br />';
	//	$widget .= sprintf(__('Ecliptic longitude: %d degrees', 'moon-phases'), $math['longitude']);
		$widget .= '<br />';

		$widget .= wpautop( wp_kses_post( $atts['text'] ) );

		// After widget hook.
		$widget .= $atts['after_widget'];

		return $widget;
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @since  1.0.0
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance = $old_instance;

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Sanitize text before saving to database.
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = force_balance_tags( $new_instance['text'] );
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		}

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  1.0.0
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
				'text'  => '',
			)
		);

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'mtoll' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'mtoll' ); ?></label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $instance['text'] ); ?></textarea></p>
		<p class="description"><?php esc_html_e( 'Basic HTML tags are allowed.', 'mtoll' ); ?></p>
		<?php
	}

	/**
	 * Moon phase math from Joe's (outdated) plugin. Returns all the info in an array.
	 * @link http://www.joeswebtools.com/wordpress-plugins/moon-phases/
	 * @return array [description]
	 */
	public static function math() {
		// Get date
		$y = date('Y');
		$m = date('n');
		$d = date('j');

		// Calculate julian day
		$yy = $y - floor((12 - $m) / 10);
		$mm = $m + 9;
		if($mm >= 12) {
			$mm = $mm - 12;
		}

		$k1 = floor(365.25 * ($yy + 4712));
		$k2 = floor(30.6 * $mm + 0.5);
		$k3 = floor(floor(($yy / 100) + 49) * 0.75) - 38;

		$jd = $k1 + $k2 + $d + 59;
		if($jd > 2299160) {
			$jd = $jd - $k3;
		}

		// Calculate the moon phase
		$ip = self::moon_phases_normalize(($jd - 2451550.1) / 29.530588853);
		$ag = $ip * 29.53;

		if($ag < 1.84566) {
			$phase = __('New Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'new_moon.png';
		}
    	else if($ag < 5.53699) {
	    	$phase = __('Waxing Crescent Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'waxing_crescent_moon.png';
	    }
    	else if($ag < 9.22831) {
			$phase = __('First Quarter Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'first_quarter_moon.png';
		}
		else if($ag < 12.91963) {
			$phase = __('Waxing Gibbous Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'waxing_gibbous_moon.png';
		}
		else if($ag < 16.61096) {
			$phase = __('Full Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'full_moon.png';
		}
		else if($ag < 20.30228) {
			$phase = __('Waning Gibbous Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'waning_gibbous_moon.png';
		}
		else if($ag < 23.99361) {
			$phase = __('Third Quarter Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'third_quarter_moon.png';
		}
		else if($ag < 27.68493) {
			$phase = __('Waning Crescent Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'waning_crescent_moon.png';
		}
		else {
			$phase = __('New Moon', 'moon-phases');
			$image = Mtoll::url('assets/images/') . 'new_moon.png';
		}

		// Convert phase to radians
		$ip = $ip * 2 * pi();

		// Calculate moon's distance
		$dp = 2 * pi() * self::moon_phases_normalize(($jd - 2451562.2) / 27.55454988);
		$di = 60.4 - 3.3 * cos($dp) - 0.6 * cos(2 * $ip - $dp) - 0.5 * cos(2 * $ip);

		// Calculate moon's ecliptic latitude
		$np = 2 * pi() * self::moon_phases_normalize(($jd - 2451565.2) / 27.212220817);
		$la = 5.1 * sin($np);

		// Calculate moon's ecliptic longitude
		$rp = self::moon_phases_normalize(($jd - 2451555.8) / 27.321582241);
		$lo = 360 * $rp + 6.3 * sin($dp) + 1.3 * sin(2 * $ip - $dp) + 0.7 * sin(2 * $ip);

		// Calculate zodiac sign
		if($lo < 30) {
			$zodiac = __('Aries', 'moon-phases');
		}
		else if($lo < 60) {
			$zodiac = __('Taurus', 'moon-phases');
		}
		else if($lo < 90) {
			$zodiac = __('Gemini', 'moon-phases');
		}
		else if($lo < 120) {
			$zodiac = __('Cancer', 'moon-phases');
		}
		else if($lo < 150) {
			$zodiac = __('Leo', 'moon-phases');
		}
		else if($lo < 180) {
			$zodiac = __('Virgo', 'moon-phases');
		}
		else if($lo < 210) {
    		$zodiac = __('Libra', 'moon-phases');
		}
    	else if($lo < 240) {
    		$zodiac = __('Scorpio', 'moon-phases');
	    }
    	else if($lo < 270) {
    		$zodiac = __('Sagittarius', 'moon-phases');
	    }
    	else if($lo < 300) {
    		$zodiac = __('Capricorn', 'moon-phases');
    	}
	    else if($lo < 330) {
			$zodiac = __('Aquarius', 'moon-phases');
	    }
		else {
			$zodiac = __('Pisces', 'moon-phases');
		}

		// Age
		$age = floor($ag);

		// Distance
		$distance = round(100 * $di) / 100;

		// Ecliptic latitude
		$latitude = round(100 * $la) / 100;

		// Ecliptic longitude
		$longitude = round(100 * $lo) / 100;
		if($longitude > 360) {
			$longitude -= 360;
		}

		$math = array();
		$math['image'] = $image;
		$math['phase'] = $phase;
		$math['zodiac'] = $zodiac;
		$math['age'] = $age;
		$math['distance'] = $distance;
		$math['latitude'] = $latitude;
		$math['longitude'] = $longitude;

		return $math;
	}

	public static function moon_phases_normalize($v) {
		$v -= floor($v);
		if($v < 0) {
			$v += 1;
		}
		return $v;
	}
}


/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  1.0.0
 * @return void
 */
function register_mtoll_moon_phase() {
	register_widget( 'M_Moon_phase' );
}
add_action( 'widgets_init', 'register_mtoll_moon_phase' );
