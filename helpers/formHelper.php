<?php

class formHelper {

	/**
	 * Will output an html <option> with the given params
	 * 
	 * @param string $optionValue 
	 * @param string $displayValue 
	 * @param string $fieldValue 
	 * @return string
	 */
	public function option($optionValue, $displayValue, $fieldValue) {
		$selected = ($optionValue == $fieldValue) ? 'selected="selected"' : '';
		return "<option value='{$optionValue}' {$selected}>{$displayValue}</option>";
	}
}