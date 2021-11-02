<?php namespace Aksara\Modules\Auth\Controllers;
/**
 * Auth
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
class Auth extends \Aksara\Laboratory\Core
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		/* check if use is already signed in */
		if(get_userdata('is_logged'))
		{
			// check if request is made through API or not
			if($this->_api_request)
			{
				// requested through API, provide the access token
				return make_json
				(
					array
					(
						'status'					=> 200,
						'message'					=> phrase('you_were_logged_in'),
						'access_token'				=> session_id()
					)
				);
			}
			else
			{
				// requested through browser
				return throw_exception(301, phrase('you_were_logged_in'), base_url('dashboard'), true);
			}
		}
		else if(service('request')->getGet('code') && service('request')->getGet('scope') && service('request')->getGet('prompt'))
		{
			/* google login authentication */
			return $this->google_auth();
		}
		else if(service('request')->getGet('code') && service('request')->getGet('state') && get_userdata('FBRLH_state'))
		{
			/* facebook login authentication */
			return $this->facebook_auth();
		}
		
		$this->set_title(phrase('dashboard_access'))
		->set_icon('mdi mdi-lock-open-outline')
		->set_description(phrase('please_enter_your_account_information_to_sign_in'))
		
		->set_output
		(
			array
			(
				'years'								=> $this->_get_active_years()
			)
		)
		
		->form_callback('_validate_form')
		
		->render();
	}
	
	/**
	 * validate form
	 */
	public function _validate_form()
	{
		if(!$this->valid_token(service('request')->getPost('_token')))
		{
			return throw_exception(403, phrase('the_token_you_submitted_has_been_expired_or_you_are_trying_to_bypass_it_from_the_restricted_source'), current_page());
		}
		
		/* check if system apply one device login */
		if(get_setting('one_device_login'))
		{
			// under research
		}
		
		$this->form_validation->setRule('username', phrase('username'), 'required');
		$this->form_validation->setRule('password', phrase('password'), 'required');
		
		if(service('request')->getPost('year'))
		{
			$this->form_validation->setRule('year', phrase('year'), 'valid_year');
		}
		
		/* run form validation */
		if($this->form_validation->run(service('request')->getPost()) === false)
		{
			return throw_exception(400, $this->form_validation->getErrors());
		}
		else
		{
			$username								= service('request')->getPost('username');
			$password								= service('request')->getPost('password');
			
			$execute								= $this->model->select
			('
				user_id,
				username,
				password,
				group_id,
				language_id,
				status
			')
			->where('username', $username)
			->or_where('email', $username)
			->get
			(
				'app__users',
				1
			)
			->row();
			
			/* check if user is inactive */
			if($execute && $execute->status != 1)
			{
				return throw_exception(404, phrase('your_account_is_temporary_disabled_or_not_yet_activated'));
			}
			else if($execute && password_verify($password . ENCRYPTION_KEY, $execute->password))
			{
				/* update the last login timestamp */
				$this->model->update
				(
					'app__users',
					array
					(
						'last_login'				=> date('Y-m-d H:i:s')
					),
					array
					(
						'user_id'					=> $execute->user_id
					),
					1
				);
				
				/* check session store */
				if(1 == service('request')->getPost('remember_session'))
				{
					/* store session to the current device */
				}
				
				/* set the user credential into session */
				set_userdata
				(
					array
					(
						'is_logged'					=> true,
						'user_id'					=> $execute->user_id,
						'username'					=> $execute->username,
						'group_id'					=> $execute->group_id,
						'language_id'				=> $execute->language_id,
						'year'						=> ($this->_get_active_years() ? (service('request')->getPost('year') ? service('request')->getPost('year') : date('Y')) : null),
						'session_generated'			=> time()
					)
				);
				
				// check if request is made through API or not
				if($this->_api_request)
				{
					// requested through API, provide the access token
					return make_json
					(
						array
						(
							'status'				=> 200,
							'message'				=> phrase('you_were_logged_in'),
							'access_token'			=> session_id()
						)
					);
				}
				else
				{
					// requested through browser
					return throw_exception(301, phrase('welcome_back') . ', <b>' . get_userdata('first_name') . '</b>! ' . phrase('you_have_been_signed_in'), base_url('dashboard'), true);
				}
			}
			
			return throw_exception(400, array('password' => phrase('username_or_email_and_password_combination_did_not_match')));
		}
	}
	
	/**
	 * sign out
	 */
	public function sign_out()
	{
		/**
		 * prepare to revoke google sign token
		 */
		if(get_setting('google_client_id') && get_setting('google_client_secret') && get_userdata('oauth_uid'))
		{
			$this->google							= new \Aksara\Libraries\Google();
			
			$this->google->revokeToken();
		}
		
		// save session id to variable before destroy the source
		$session_id									= session_id();
		
		// destroy session
		service('session')->destroy();
		
		// remove session from database
		$this->model->where('id', $session_id)->delete('app__sessions');
		
		return throw_exception(301, phrase('you_were_logged_out'), base_url(), true);
	}
	/**
	 * redirect to google auth url
	 */
	public function google()
	{
		$this->google								= new \Aksara\Libraries\Google();
		
		redirect_to($this->google->get_login_url());
	}
	
	/**
	 * validate google auth
	 */
	public function google_auth()
	{
		$this->google								= new \Aksara\Libraries\Google();
		
		$session									= $this->google->validate();
		
		return $this->_validate($session);
	}
	
	/**
	 * redirect to facebook auth url
	 */
	public function facebook()
	{
		$this->facebook								= new \Aksara\Libraries\Facebook();
		
		redirect_to($this->facebook->get_login_url());
	}
	
	/**
	 * validate facebook auth
	 */
	public function facebook_auth()
	{
		$this->facebook								= new \Aksara\Libraries\Facebook();
		
		$session									= $this->facebook->validate();
		
		return $this->_validate($session);
	}
	
	/**
	 * get active years
	 */
	private function _get_active_years()
	{
		$output										= array();
		
		$query										= $this->model->get_where
		(
			'app__years',
			array
			(
				'status'							=> 1
			)
		)
		->result();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output[]							= array
				(
					'value'							=> $val->year,
					'label'							=> $val->year,
					'selected'						=> $val->default
				);
			}
		}
		
		return $output;
	}
	
	/**
	 * do validation
	 */
	private function _validate($session = null)
	{
		if(DEMO_MODE)
		{
			return throw_exception(403, phrase('this_feature_is_disabled_in_demo_mode'), current_page('../'));
		}
		
		if($session)
		{
			$query									= $this->model->select
			('
				app__users.user_id,
				app__users.username,
				app__users.group_id,
				app__users.language_id
			')
			->join
			(
				'app__users',
				'app__users.user_id = oauth__login.user_id'
			)
			->get_where
			(
				'oauth__login',
				array
				(
					'oauth__login.service_provider'	=> $session->oauth_provider,
					'oauth__login.access_token'		=> $session->oauth_uid
				)
			)
			->row();
			
			if($query)
			{
				/* set the user credential into session */
				set_userdata
				(
					array
					(
						'is_logged'					=> true,
						'oauth_uid'					=> $session->oauth_uid,
						'user_id'					=> $query->user_id,
						'username'					=> $query->username,
						'group_id'					=> $query->group_id,
						'language_id'				=> $query->language_id,
						'year'						=> ($this->_get_active_years() ? date('Y') : null),
						'session_generated'			=> time()
					)
				);
				
				return throw_exception(301, phrase('welcome_back') . ', <b>' . get_userdata('first_name') . '</b>! ' . phrase('you_were_logged_in'), base_url('dashboard'), true);
			}
			else
			{
				$query								= $this->model->select
				('
					user_id
				')
				->get_where
				(
					'app__users',
					array
					(
						'email'						=> $session->email
					)
				)
				->row();
				
				if($query)
				{
					$this->model->insert
					(
						'oauth__login',
						array
						(
							'user_id'				=> $query->user_id,
							'service_provider'		=> $session->oauth_provider,
							'access_token'			=> $session->oauth_uid,
							'status'				=> 1
						)
					);
					
					return $this->_validate($session);
				}
			}
			
			$photo									= $session->picture;
			$extension								= getimagesize($photo);
			$extension								= image_type_to_extension($extension[2]);
			$upload_name							= sha1(time()) . $extension;
			
			if(copy($photo, UPLOAD_PATH . '/users/' . $upload_name))
			{
				$photo								= $upload_name;
				
				$thumbnail_dimension				= (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 256);
				$icon_dimension						= (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 64);
				
				$this->_resize_image('users', $upload_name, 'thumbs', $thumbnail_dimension, $thumbnail_dimension);
				$this->_resize_image('users', $upload_name, 'icons', $icon_dimension, $icon_dimension);
			}
			else
			{
				$photo								= 'placeholder.png';
			}
			
			$language_id							= (get_userdata('language_id') ? get_userdata('language_id') : (get_setting('app_language') > 0 ? get_setting('app_language') : 1));
			$default_membership						= (get_setting('default_membership_group') ? get_setting('default_membership_group') : 3);
			
			$this->model->insert
			(
				'app__users',
				array
				(
					'email'							=> $session->email,
					'password'						=> '',
					'username'						=> '',
					'first_name'					=> $session->first_name,
					'last_name'						=> $session->last_name,
					'photo'							=> $photo,
					'phone'							=> '',
					'postal_code'					=> '',
					'language_id'					=> $language_id,
					'group_id'						=> $default_membership,
					'registered_date'				=> date('Y-m-d'),
					'last_login'					=> date('Y-m-d H:i:s'),
					'status'						=> 1
				)
			);
			
			if($this->model->affected_rows() > 0)
			{
				$insert_id							= $this->model->insert_id();
				
				$this->model->insert
				(
					'oauth__login',
					array
					(
						'user_id'					=> $insert_id,
						'service_provider'			=> $session->oauth_provider,
						'access_token'				=> $session->oauth_uid,
						'status'					=> 1
					)
				);
				
				$this->_send_welcome_email($session);
				
				return $this->_validate($session);
			}
		}
	}
	
	private function _send_welcome_email($session)
	{
		error_reporting(0); // prevent the PHP throw errors before exception
		
		/**
		 * to working with Google SMTP, make sure to activate less secure apps setting
		 */
		$host										= get_setting('smtp_host');
		$username									= get_setting('smtp_username');
		$password									= (get_setting('smtp_password') ? service('encrypter')->decrypt(base64_decode(get_setting('smtp_password'))) : '');
		$sender_email								= (get_setting('smtp_email_masking') ? get_setting('smtp_email_masking') : service('request')->getServer('SERVER_ADMIN'));
		$sender_name								= (get_setting('smtp_sender_masking') ? get_setting('smtp_sender_masking') : get_setting('app_name'));
		
		$this->email								= \Config\Services::email();
		
		if($host && $username && $password)
		{
			$config['userAgent']       				= 'Aksara';
			$config['protocol']						= 'smtp';
			$config['SMTPCrypto']					= 'ssl';
			$config['SMTPTimeout']					= 5;
			$config['SMTPHost']						= (strpos($host, '://') !== false ? trim(substr($host, strpos($host, '://') + 3)) : $host);
			$config['SMTPPort']						= get_setting('smtp_port');
			$config['SMTPUser']						= $username;
			$config['SMTPPass']						= $password;
		}
		else
		{
			$config['protocol']						= 'mail';
		}
		
		$config['charset']							= 'utf-8';
		$config['newline']							= "\r\n";
		$config['mailType']							= 'html'; // text or html
		$config['wordWrap']							= true;
		$config['validation']						= true; // bool whether to validate email or not
		
		$this->email->initialize($config);	
		
		$this->email->setFrom($sender_email, $sender_name);
		$this->email->setTo($session->email);
		
		$this->email->setSubject(phrase('welcome_to') . ' ' . get_setting('app_name'));
		$this->email->setMessage
		('
			<!DOCTYPE html>
			<html>
				<head>
					<meta name="viewport" content="width=device-width" />
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					<title>
						' . phrase('welcome_to') . ' ' . get_setting('app_name') . '
					</title>
				</head>
				<body>
					<p>
						' . phrase('hi') . ', <b>' . $session->first_name . ' ' . $session->last_name . '</b>
					</p>
					<p>
						' . phrase('you_are_successfully_registered_to_our_website') . ' ' . phrase('now_you_can_sign_in_to_our_website_using_your_' . $session->oauth_provider . '_account') . ' ' . phrase('make_sure_to_set_your_password_and_username_to_secure_your_account') . '
					</p>
					<p>
						' . phrase('please_contact_us_directly_if_you_still_unable_to_signing_in') . '
					</p>
					<br />
					<br />
					<p>
						<b>
							' . get_setting('office_name') . '
						</b>
						<br />
						' . get_setting('office_address') . '
						<br />
						' . get_setting('office_phone') . '
					</p>
				</body>
			</html>
		');
		
		if(!$this->email->send())
		{
			// return throw_exception(400, array('message' => $this->email->printDebugger()));
		}
	}
	
	/**
	 * _resize_image
	 * Generate the thumbnail of uploaded image
	 *
	 * @access		private
	 */
	private function _resize_image($path = null, $filename = null, $type = null, $width = 0, $height = 0)
	{
		$source										= UPLOAD_PATH . '/' . $path . '/' . $filename;
		$target										= UPLOAD_PATH . '/' . $path . ($type ? '/' . $type : null) . '/' . $filename;
		
		$imageinfo									= getimagesize($source);
		$master_dimension							= ($imageinfo[0] > $imageinfo[1] ? 'width' : 'height');
		
		// load image manipulation library
		$this->image								= \Config\Services::image('gd');
		
		// resize image
		if($this->image->withFile($source)->resize($width, $height, true, $master_dimension)->save($target))
		{
			// crop image after resized
			$this->image->withFile($target)->fit($width, $height, 'center')->save($target);
		}
	}
}
