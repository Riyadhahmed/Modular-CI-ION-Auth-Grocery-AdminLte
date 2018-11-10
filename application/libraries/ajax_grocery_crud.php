<?php

class ajax_grocery_CRUD extends grocery_CRUD {

	protected $unset_ajax_extension			= false;
	private $state_code 			= null;
	private $slash_replacement	= "_agsl_";
	protected $relation_dependency		= array();

	function __construct()
	{
		parent::__construct();

		$this->states[101]='ajax_extension';

	}

	public function inline_js($inline_js = '')
	{
		$this->_inline_js($inline_js);
	}


	public function set_relation_dependency($target_field, $source_field, $relation_field_on_source_table)
	{
		$this->relation_dependency[$target_field] = array($target_field, $source_field,$relation_field_on_source_table);
		return $this;
	}

	private function render_relation_dependencies()
	{

		foreach($this->relation_dependency as $dependency)
		{
			$this->render_relation_dependency($dependency[0],$dependency[1],$dependency[2]);
		}

	}

	private function render_relation_dependency($target_field, $source_field, $relation_field_on_source_table){

		$sourceElement = "'#field-$source_field'";
		$targetElement = "'#field-$target_field'";

		$js_text = "
			$(document).ready(function() {
				$($sourceElement).change(function() {
					var selectedValue = $($sourceElement).val();
					//alert('post:'+'ajax_extension/$target_field/$relation_field_on_source_table/'+encodeURI(selectedValue.replace(/\//g,'$this->slash_replacement')));
					$.post('ajax_extension/$target_field/$relation_field_on_source_table/'+encodeURI(selectedValue.replace(/\//g,'$this->slash_replacement')), {}, function(data) {
					//alert('BACK');
					//alert('data'+data);
					var \$el = $($targetElement);
						  var newOptions = data;
						  \$el.empty(); // remove old options
						  \$.each(newOptions, function(key, value) {
						    \$el.append(\$('<option></option>')
						       .attr('value', key).text(value));
						    });
						  \$el.chosen().trigger('liszt:updated');
    	  			},'json');
				});
			});
			";
log_message('debug','DEP: '.$target_field);
		$this->inline_js($js_text);

	}

	public function render()
	{

		$this->pre_render();

		$this->state_code = $this->getStateCode();

		if( $this->state_code != 0 )
		{
			$this->state_info = $this->getStateInfo();
		}
		else
		{
			throw new Exception('The state is unknown , I don\'t know what I will do with your data!', 4);
			die();
		}

		switch ($this->state_code) {
			case 2://add
					$this->render_relation_dependencies();
					$output = parent::render();
			break;
			case 3://edit
					$this->render_relation_dependencies();
					$output = parent::render();
			break;
			case 6://update
				$this->render_relation_dependencies();
				$output = parent::render();
			break;

			case 101://ajax_extension

				$state_info = $this->getStateInfo();

				$ajax_extension_result = $this->ajax_extension($state_info);

				$ajax_extension_result[""] = "";

				echo json_encode($ajax_extension_result);
			die();

			break;
			default:

				$output = parent::render();

			break;

		}

		if(empty($output)){
			$output = $this->get_layout();
		}else{
		}

		return $output;
	}



public function getStateInfo()
	{
		$state_code = $this->getStateCode();

		$segment_object = $this->get_state_info_from_url();

		$first_parameter = $segment_object->first_parameter;

		$second_parameter = $segment_object->second_parameter;

		$third_parameter = $segment_object->third_parameter;


		$state_info = (object)array();

		switch ($state_code) {
			case 101: //ajax_extension
				$state_info->target_field_name = $first_parameter;
				$state_info->relation_field_on_source_table = $second_parameter;
				$state_info->filter_value = $third_parameter;

			break;

			default:
				$state_info = parent::getStateInfo();

		}

		return $state_info;
	}



	protected function ajax_extension($state_info)
	{

		if(!isset($this->relation[$state_info->target_field_name]))
			return false;

		list($field_name, $related_table, $related_field_title, $where_clause, $order_by)  = $this->relation[$state_info->target_field_name];


		$target_field_name = $state_info->target_field_name;

		$relation_field_on_source_table = $state_info->relation_field_on_source_table;

		$filter_value = $state_info->filter_value;

		if(is_int($filter_value)){

			$final_filter_value = $filter_value;

		}else {

				$decoded_filter_value = urldecode($filter_value);

				$replaced_filter_value = str_replace($this->slash_replacement,'/',$decoded_filter_value);

				if(strpos($replaced_filter_value,'/') !== false) {
					$final_filter_value = $this->_convert_date_to_sql_date($replaced_filter_value);

				}else{
					$final_filter_value = $replaced_filter_value;
				}
		}

		$target_field_relation = $this->relation[$target_field_name];

		$result = $this->get_dependency_relation_array($target_field_relation, $relation_field_on_source_table, $final_filter_value);

		return $result;
	}

	protected function get_dependency_relation_array($relation_info, $relation_key_field, $relation_key_value, $limit = null)
	{
		list($field_name , $related_table , $related_field_title, $where_clause, $order_by)  = $relation_info;

		$where_clause = array($relation_key_field => $relation_key_value);

		$relation_array = $this->basic_model->get_relation_array($field_name , $related_table , $related_field_title, $where_clause, $order_by, $limit);

		return $relation_array;
	}



	public function unset_ajax_extension()
	{
		$this->unset_ajax_extension = true;

		return $this;
	}


    //Overriden with the purpose of adding a third parameter, currently not calling parent. It should be changed in future if changes are made to parent.
	protected function get_state_info_from_url()
	{
		$ci = &get_instance();

		$segment_position = count($ci->uri->segments) + 1;
		$operation = 'list';

		$segements = $ci->uri->segments;
		foreach($segements as $num => $value)
		{
			if($value != 'unknown' && in_array($value, $this->states))
			{
				$segment_position = (int)$num;
				$operation = $value; //I don't have a "break" here because I want to ensure that is the LAST segment with name that is in the array.
			}
		}

		$function_name = $this->get_method_name();

		if($function_name == 'index' && !in_array('index',$ci->uri->segments))
			$segment_position++;

		$first_parameter = isset($segements[$segment_position+1]) ? $segements[$segment_position+1] : null;
		$second_parameter = isset($segements[$segment_position+2]) ? $segements[$segment_position+2] : null;
		$third_parameter = isset($segements[$segment_position+3]) ? $segements[$segment_position+3] : null;

		return (object)array('segment_position' => $segment_position, 'operation' => $operation, 'first_parameter' => $first_parameter, 'second_parameter' => $second_parameter, 'third_parameter' => $third_parameter);
	}

}
