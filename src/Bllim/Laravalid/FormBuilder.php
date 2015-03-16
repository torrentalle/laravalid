<?php namespace Bllim\Laravalid;
/**
 * This class is extending \Illuminate\Html\FormBuilder to make 
 * validation easy for both client and server side. Package convert 
 * laravel validation rules to javascript validation plugins while 
 * using laravel FormBuilder.
 *
 * USAGE: Just pass $rules to Form::open($options, $rules) and use.
 * You can also pass by using Form::setValidation from controller or router
 * for coming first form::open.
 * When Form::close() is used, $rules are reset.
 *
 * NOTE: If you use min, max, size, between and type of input is different from string
 * don't forget to specify the type (by using numeric, integer).
 *
 * TODO: Route system to unique
 *
 * @package    Laravel Validation For Client-Side
 * @author     Bilal Gultekin <bilal@bilal.im>
 * @license    MIT
 * @see        Illuminate\Html\FormBuilder
 * @version    0.9
 */
use Lang, Config;

class FormBuilder extends \Illuminate\Html\FormBuilder {

	protected $converter;

	public function __construct(\Illuminate\Html\HtmlBuilder $html, \Illuminate\Routing\UrlGenerator $url, $csrfToken, BaseConverter\Converter $converter)
	{
		parent::__construct($html, $url, $csrfToken);
		$plugin = config('laravalid.plugin');
		$this->converter = $converter;
	}

	/**
	 * Set rules for validation
	 *
	 * @param array $rules 		Laravel validation rules
	 *
	 */
	public function setValidation($rules)
	{
		$this->converter()->set($rules);
	}

	/**
	 * Get binded converter class
	 *
	 * @param array $rules 		Laravel validation rules
	 *
	 */
	public function converter()
	{
		return $this->converter;
	}

	/**
	 * Reset validation rules
	 *
	 */
	public function resetValidation()
	{
		$this->converter()->reset();
	}

	/**
	 * Opens form, set rules
	 *
	 * @param array $rules 		Laravel validation rules
	 *
	 * @see Illuminate\Html\FormBuilder
	 */
	public function open(array $options = array(), $rules = null)
	{
		$this->setValidation($rules);
		
		return parent::open($options);
	}

	/**
	 * Create a new model based form builder.
	 *
	 * @param array $rules 		Laravel validation rules
	 *
	 * @see Illuminate\Html\FormBuilder
	 */
	public function model($model, array $options = array(), $rules = null)
	{
		$this->setValidation($rules);
		return parent::model($model, $options);
	}

	/**
	 * @see Illuminate\Html\FormBuilder
	 */
	public function input($type, $name, $value = null, $options = [])
	{
		$options = $this->converter->convert($name) + $options;
		return parent::input($type, $name, $value, $options);
	}

	/**
	 * @see Illuminate\Html\FormBuilder
	 */
	public function textarea($name, $value = null, $options = [])
	{
		$options = $this->converter->convert($name) + $options;
		return parent::textarea($name, $value, $options);
	}

	/**
	 * @see Illuminate\Html\FormBuilder
	 */
	public function select($name, $list = [], $selected = null, $options = [])
	{
		$options = $this->converter->convert($name) + $options;
		return parent::select($name, $list, $selected, $options);
	}

	protected function checkable($type, $name, $value, $checked, $options)
	{
		$options = $this->converter->convert($name) + $options;
		return parent::checkable($type, $name, $value, $checked, $options);
	}

	/**
	 * Closes form and reset $this->rules
	 * 
	 * @see Illuminate\Html\FormBuilder
	 */
	public function close()
	{
		$this->resetValidation();
		return parent::close();
	}


}