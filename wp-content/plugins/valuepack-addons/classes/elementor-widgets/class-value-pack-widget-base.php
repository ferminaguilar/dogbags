<?php

defined('ABSPATH') || exit;

use Elementor\Widget_Base;

/**
 * Base widget class for Value Pack Elementor widgets.
 *
 * Provides consistent asset dependency handling for all widgets.
 */
abstract class Value_Pack_Widget_Base extends Widget_Base
{
	/**
	 * Additional script handles to enqueue for the widget.
	 *
	 * @var string[]
	 */
	protected $value_pack_script_depends = array('value-pack-frontend-scripts');

	/**
	 * Additional style handles to enqueue for the widget.
	 *
	 * @var string[]
	 */
	protected $value_pack_style_depends = array();

	/**
	 * Get list of style handles required by the widget.
	 *
	 * @return string[]
	 */
	public function get_style_depends()
	{
		$handles = array_merge(array('value-pack-styles'), $this->value_pack_style_depends);

		return array_values(array_unique(array_filter($handles)));
	}

	/**
	 * Get list of script handles required by the widget.
	 *
	 * @return string[]
	 */
	public function get_script_depends()
	{
		return array_values(array_unique(array_filter($this->value_pack_script_depends)));
	}
}

