<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('acf_field_json')) :
	class acf_field_json extends acf_field
	{
		public $show_in_rest = true;
		public $show_in_graphql = true;
		private $env;

		function initialize()
		{
			$this->name = 'json';
			$this->label = __('JSON', 'acf-json');
			$this->category = 'basic';
			$this->defaults = array();
			$this->default_values = array();
			$this->l10n = array(
				'error'	=> __('Error! Please enter a higher value', 'acf-json'),
			);
			$this->env = array(
				'url'     => plugin_dir_url(__FILE__),
				'version' => '1.0',
			);
			$this->show_in_rest = true;
		}

		function render_field($field)
		{
			$field_name 				= esc_attr($field['name']);
			$field_value				= !empty($field['value']) ? htmlspecialchars($field['value']) : '{}';

?>

			<div class="acf-hidden">
				<input type="hidden" name="<?php echo $field_name ?>" value="<?php echo $field_value ?>">
			</div>
			<div class="acf-field-json-row">
				<div class="acf-field-json-content">
					<div class="field" data-field_type="json" data-acf-value="<?php echo $field_value ?>"></div>
				</div>
			</div>
<?php
		}

		public function format_value_for_rest($value, $post_id, array $field)
		{
			return json_decode($value);
		}

		function input_admin_enqueue_scripts()
		{

			$dir = str_replace('fields/', '', plugin_dir_url(__FILE__));

			// register & include JS
			wp_register_script('jsoneditor', "{$dir}assets/js/jsoneditor/jsoneditor.min.js");
			wp_enqueue_script('jsoneditor');

			wp_register_script('acf-input-json', "{$dir}assets/js/input.js");
			wp_enqueue_script('acf-input-json');

			// register & include CSS
			wp_register_style('jsoneditor', "{$dir}assets/js/jsoneditor/jsoneditor.min.css");
			wp_enqueue_style('jsoneditor');

			wp_register_style('acf-input-json', "{$dir}assets/css/input.css");
			wp_enqueue_style('acf-input-json');
		}

		public static function get_graphql_fields()
		{
			return [
				'json' => [
					'type' => 'String',
					'description' => __('JSON', 'acf'),
					'resolve' => static function ($root) {
						return $root['json'] ?? null;
					},
				],
			];
		}
	}

	new acf_field_json();

	acf_register_field_type('acf_field_json');

	add_action('wpgraphql/acf/registry_init', function () {
		register_graphql_acf_field_type('json');
	});

endif;

?>