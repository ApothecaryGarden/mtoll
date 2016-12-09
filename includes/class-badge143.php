<?php
/**
 * Mtoll Badge143
 * @version 1.0.0
 * @package Mtoll
 */

class M_Badge143 extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_slug = 'mtoll-badge143';


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
	protected static $shortcode = 'mtoll-badge143';


	/**
	 * Construct widget class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Mtoll Badge143', 'mtoll' );
		$this->default_widget_title = esc_html__( 'Mtoll Badge143', 'mtoll' );

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
		$widget = $args = $query = $atts['text'] = '';

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

		// Before widget hook.
		$widget .= $atts['before_widget'];

		// Title.
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		// After widget hook.

		wp_reset_postdata();

		// WP_Query arguments
		$args = array (
			'post_type'              => array( 'badges' ),
			'post_status'            => array( 'publish' ),
			'order'                  => 'DESC',
			'orderby'                => 'menu_order',
		);
		global $user_ID;
		// The Query
		$query = new WP_Query( $args );
		// The Loop
		$widget .= '<div class="mtoll-badge143-wrap">';
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$earned_status = badgeos_get_user_achievements( array( 'user_id' => $user_ID, 'achievement_id' => absint( $query->post->ID ) ) ) ? 'user-has-earned' : 'user-has-not-earned';
					$widget .= '<div class="badgeos-achievements-list-item '. $earned_status . '"><div class="badgeos-item-image">';
					$widget .= '<a href="' . get_permalink( $query->post->ID ) . '">' . badgeos_get_achievement_post_thumbnail( $query->post->ID, 'full' ) . '</a>';
					$widget .= '</div></div><!-- .badgeos-item-image -->';
				//	$widget .= $query->post->ID . ' apples<br />';
				}
			} else {
				// no posts found
			}
		$widget .= '</div>';
		// Restore original Post Data
		wp_reset_postdata();
		$content = $atts['text'];
	//	$content = $wp_embed->autoembed( $content );
	//	$content = $wp_embed->run_shortcode( $content );
		$content = do_shortcode( $content );
		$content = wpautop( wp_kses_post( $content ) );
		$widget .= $content;

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
}


/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  1.0.0
 * @return void
 */
function register_mtoll_badge143() {
	register_widget( 'M_Badge143' );
}
add_action( 'widgets_init', 'register_mtoll_badge143' );
