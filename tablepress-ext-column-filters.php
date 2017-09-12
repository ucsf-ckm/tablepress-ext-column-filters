<?php

/**
 * @link              https://github.com/ucsf-ckm/tablepress-ext-column-filters
 * @since             1.0.0
 * @package           TablePressExtColumnFilters
 *
 * @wordpress-plugin
 * Plugin Name:       TablePress Extension: Column filters
 * Plugin URI:        https://github.com/ucsf-ckm/tablepress-ext-column-filters
 * Description:       Filters a given data table by searching comma-separated lists of terms in a given column.
 * Version:           1.0.0
 * Author:            Stefan Topfstedt
 * Author URI:        https://github.com/stopfstedt
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * GitHub Plugin URI: https://github.com/ucsf-ckm/tablepress-ext-column-filters
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The plugin version.
 *
 * @since 1.0.0
 *
 * @var string TABLEPRESS_EXT_COLUMN_FILTERS_VERSION
 */
const TABLEPRESS_EXT_COLUMN_FILTERS_VERSION = '1.0.0';

add_action( 'wp_enqueue_scripts', 'tablepress_ext_column_filters_datatables_enqueue_scripts' );
add_filter( 'tablepress_table_js_options', 'tablepress_ext_column_filters_datatables_js_options', 10, 3 );
add_filter( 'tablepress_datatables_command', 'tablepress_ext_column_filters_datatables_command', 10, 5 );
add_shortcode( 'tablepress-ext-column-filters', 'tablepress_ext_column_filters_shortcode' );

/**
 * Enqueues this plugin's custom scripts.
 *
 * @since 1.0.0
 */
function tablepress_ext_column_filters_datatables_enqueue_scripts() {
	wp_enqueue_script(
		'tablepress-ext-column-filters',
		plugins_url( 'js', plugin_basename( __FILE__ ) ) . '/tablepress-ext-column-filters.js',
		array( 'tablepress-datatables' ),
		TABLEPRESS_EXT_COLUMN_FILTERS_VERSION,
		true
	);
}

/**
 * Forces DataTables filtering on.
 *
 * @since 1.0.0
 *
 * @param array  $js_options Current JS options.
 * @param string $table_id Table ID.
 * @param array $render_options Render Options.
 * @return array Modified JS options.
 */
function tablepress_ext_column_filters_datatables_js_options( $js_options, $table_id, $render_options ) {
	$js_options['datatables_filter'] = true;
	return $js_options;
}

/**
 * Adds additional JS code to the given DataTables command.
 *
 * @since 1.0.0
 *
 * @param string $command DataTables command.
 * @param string $html_id HTML ID of the table.
 * @param array $parameters DataTables parameters.
 * @param string $table_id Table ID.
 * @param array $js_options DataTables JS options.
 *
 * @return string Modified DataTables command.
 */
function tablepress_ext_column_filters_datatables_command( $command, $html_id, $parameters, $table_id, $js_options ) {
	$command .= "var dt = $('#${html_id}').DataTable();";
	$command .= "$(\".tablepress-ext-column-filters[data-table='${table_id}']\").tablePressExtColumnFilters(dt);";

	return $command;
}

/**
 * Renders the shortcode.
 *
 * @since 1.0
 *
 * @param array $attrs
 * @param string|null $content
 *
 * @return string
 */
function tablepress_ext_column_filters_shortcode( $attrs = array(), $content = null ) {
	$attrs     = shortcode_atts( array( 'table-id' => '', 'column-id' => '' ), $attrs );
	$table_id  = $attrs['table-id'];
	$column_id = $attrs['column-id'];

	return "<div class=\"tablepress-ext-column-filters\" data-table=\"${table_id}\" data-column=\"${column_id}\">${content}</div>";
}
