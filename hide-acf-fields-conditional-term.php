<?php
/**
 * Class for hiding an acf field based on if a term is selected.
 * Developer: Brad Goddard
 * Website: https://www.lucid-digital.co.uk
 * @package LucidDigital
 */

class Hide_acf_fields_conditional_term {

	public $post_type_slug;
	public $term_id;
	public $field_class;

	public function __construct( $post_type_slug, $term_id, $field_class ) {
		$this->set_post_type_slug( $post_type_slug );
		$this->set_term_id( $term_id );
		$this->set_field_class( $field_class );
		add_action( 'acf/input/admin_head', [$this, 'my_acf_admin_head'] );
	}

	/**
	 * @return mixed
	 */
	public function get_post_type_slug() {
		return $this->post_type_slug;
	}

	/**
	 * @param mixed $post_type_slug
	 */
	public function set_post_type_slug( $post_type_slug ) {
		$this->post_type_slug = $post_type_slug;
	}

	/**
	 * @return mixed
	 */
	public function get_term_id() {
		return $this->term_id;
	}

	/**
	 * @param mixed $term_id
	 */
	public function set_term_id( $term_id ) {
		$this->term_id = $term_id;
	}

	/**
	 * @return mixed
	 */
	public function get_field_class() {
		return $this->field_class;
	}

	/**
	 * @param mixed $field_class
	 */
	public function set_field_class( $field_class ) {
		$this->field_class = $field_class;
	}

	function my_acf_admin_head() {
		global $post_type;
		if ( $this->get_post_type_slug() === $post_type ) :
			// Gets taxonomy term from $this->get_post_type_slug().
			$taxonomy = get_object_taxonomies($this->get_post_type_slug());
			?>
			<script type="text/javascript">
                (function ($) {
                    acf.add_action('ready', function ($el) {
                        // Container for the sub title field.
                        var container = $('.' + '<?php echo $this->field_class; ?>');
                        // Featured checkbox.
                        var $field = $('#' + '<?php echo $taxonomy[0]; ?>' + 'div input');
                        // The checkbox relative to $this->get_term_id().
                        var checkbox = $('#in-' + '<?php echo $taxonomy[0]; ?>' + '-' + '<?php echo $this->get_term_id(); ?>');
                        container.addClass('hidden-by-conditional-logic');
                        // If $this->get_term_id() term is already selected then show the $this->field_class field.
                        if ($(checkbox).is(':checked')) {
                            container.removeClass('hidden-by-conditional-logic');
                        }
                        // Click function to show and hide the $this->field_class field.
                        $field.on('click', function (evt) {
                            if ($(checkbox).is(':checked')) {
                                container.removeClass('hidden-by-conditional-logic');
                            } else {
                                container.addClass('hidden-by-conditional-logic');
                            }
                        });
                    });
                })(jQuery);
			</script>
		<?php endif;
	}
}

new Hide_acf_fields_conditional_term( [post-type-slug], [term-id], [acf-field-container-class] );