<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CMS > Peoples
 * Manage peoples that will be shown in the frontpage
 *
 * @version			2.1.1
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Peoples extends Aksara
{
	private $_table									= 'peoples';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(array(1, 2));
		$this->set_theme('backend');
		
		$this->crud();
	}
	
	public function index()
	{
		$this->set_title(phrase('peoples'))
		->set_icon('mdi mdi-account-group-outline')
		->unset_column('people_id, people_slug, biography, instagram, facebook, twitter')
		->unset_field('people_id')
		->unset_view('people_id')
		->set_field
		(
			array
			(
				'biography'							=> 'textarea',
				'email'								=> 'email',
				'photo'								=> 'image',
				'status'							=> 'boolean'
			)
		)
		->set_field('people_slug', 'to_slug', 'full_name')
		->set_field('full_name', 'hyperlink', 'peoples', array('people_slug' => 'people_slug'), true)
		
		->add_action('option', '../../peoples/user', phrase('view_page'), 'btn-success', 'mdi mdi-eye', array('people_slug' => 'people_slug'), true)
		
		->column_order('photo, full_name')
		->field_order('photo')
		->set_validation
		(
			array
			(
				'first_name'						=> 'required|xss_clean',
				'status'							=> 'is_boolean'
			)
		)
		->merge_field('first_name, last_name')
		->merge_field('mobile, instagram')
		->merge_field('facebook, twitter')
		->merge_content('{first_name} {last_name}', phrase('full_name'))
		->field_size
		(
			array
			(
				'mobile'							=> 'col-md-6',
				'instagram'							=> 'col-md-6',
				'facebook'							=> 'col-md-6',
				'twitter'							=> 'col-md-6'
			)
		)
		->set_alias
		(
			array
			(
				'photo'								=> phrase('photo'),
				'first_name'						=> phrase('first_name'),
				'last_name'							=> phrase('last_name'),
				'people_slug'						=> phrase('slug'),
				'position'							=> phrase('position'),
				'mobile'							=> phrase('mobile'),
				'email'								=> phrase('email'),
				'biography'							=> phrase('biography'),
				'status'							=> phrase('status')
			)
		)
		->render($this->_table);
	}
}
