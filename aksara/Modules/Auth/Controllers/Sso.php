<?php

/**
 * This file is part of Aksara CMS, both framework and publishing
 * platform.
 *
 * @author     Aby Dahana <abydahana@gmail.com>
 * @copyright  (c) Aksara Laboratory <https://aksaracms.com>
 * @license    MIT License
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.txt file.
 *
 * When the signs is coming, those who don't believe at "that time"
 * have only two choices, commit suicide or become brutal.
 */

namespace Aksara\Modules\Auth\Controllers;

use Hybridauth\Hybridauth;

class Sso extends \Aksara\Laboratory\Core
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($provider = null)
    {
        $config = [
            // Location where to redirect users once they authenticate with a provider
            'callback' => base_url('auth/sso/' . $provider),

            // Providers specifics
            'providers' => [
                'google' => [
                    'enabled' => ('google' == $provider ? true : false),
                    'keys' => [
                        'id' => get_setting('google_client_id'),
                        'secret' => get_setting('google_client_secret')
                    ]
                ],
                'facebook' => [
                    'enabled' => ('facebook' == $provider ? true : false),
                    'keys' => [
                        'id' => get_setting('facebook_app_id'),
                        'secret' => get_setting('facebook_app_secret')
                    ]
                ]
            ]
        ];

        try {
            // Instantiate Hybridauth
            $hybridauth = new \Hybridauth\Hybridauth($config);

            // Instantiate adapter directly
            $adapter = $hybridauth->authenticate($provider);

            // Retrieve the user's profile
            $profile = $adapter->getUserProfile();

            if ($profile && $profile->email) {
                // Validate response
                return $this->_validate($provider, $profile);
            } else {
                // Throw exception
                throw new \Throwable(phrase('Unable to signing you in using th selected platform.'));
            }
        } catch(\Throwable $e) {
            return throw_exception(403, $e->getMessage(), base_url('auth'));
        }
    }

    /**
     * do validation
     * @param null|mixed $provider
     */
    private function _validate($provider = null, $params = [])
    {
        if (DEMO_MODE) {
            return throw_exception(403, phrase('This feature is disabled in demo mode.'), current_page('../'));
        } elseif (! $params) {
            return throw_exception(403, phrase('Unable to signing you in using th selected platform.'), current_page('../'));
        }

        $query = $this->model->select('
            app__users.user_id,
            app__users.username,
            app__users.group_id,
            app__users.language_id
        ')
        ->join(
            'app__users',
            'app__users.user_id = app__users_oauth.user_id'
        )
        ->get_where(
            'app__users_oauth',
            [
                'app__users_oauth.service_provider' => $provider,
                'app__users_oauth.access_token' => $params->identifier
            ]
        )
        ->row();

        if ($query) {
            // Set the user credential into session
            set_userdata([
                'is_logged' => true,
                'oauth_uid' => $provider,
                'user_id' => $query->user_id,
                'username' => $query->username,
                'group_id' => $query->group_id,
                'language_id' => $query->language_id,
                'year' => ($this->_get_active_years() ? date('Y') : null),
                'session_generated' => time()
            ]);

            return throw_exception(301, phrase('Welcome back') . ', <b>' . get_userdata('first_name') . '</b>! ' . phrase('You were signed in.'), base_url((service('request')->getGet('redirect') ? service('request')->getGet('redirect') : 'dashboard')), true);
        } else {
            $query = $this->model->select('
                user_id
            ')
            ->get_where(
                'app__users',
                [
                    'email' => $params->email
                ]
            )
            ->row();

            if ($query) {
                // User found, set the oauth platform integration
                $this->model->insert(
                    'app__users_oauth',
                    [
                        'user_id' => $query->user_id,
                        'service_provider' => $provider,
                        'access_token' => $params->identifier,
                        'status' => 1
                    ]
                );

                return $this->_validate($params);
            } else {
                // User not found, create user and set the oauth platform integration
                $photo = $params->photoURL;
                $extension = getimagesize($photo);
                $extension = image_type_to_extension($extension[2]);
                $upload_name = sha1(time()) . $extension;

                if (copy($photo, UPLOAD_PATH . '/users/' . $upload_name)) {
                    $photo = $upload_name;
                    $thumbnail_dimension = (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 256);
                    $icon_dimension = (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 64);

                    $this->_resize_image('users', $upload_name, 'thumbs', $thumbnail_dimension, $thumbnail_dimension);
                    $this->_resize_image('users', $upload_name, 'icons', $icon_dimension, $icon_dimension);
                } else {
                    $photo = 'placeholder.png';
                }

                $language_id = (get_userdata('language_id') ? get_userdata('language_id') : (get_setting('app_language') > 0 ? get_setting('app_language') : 1));
                $default_membership = (get_setting('default_membership_group') ? get_setting('default_membership_group') : 3);

                $this->model->insert(
                    'app__users',
                    [
                        'email' => $params->email ?? 'abydahana@gmail.com',
                        'password' => '',
                        'username' => $params->identifier,
                        'first_name' => $params->firstName,
                        'last_name' => $params->lastName,
                        'photo' => $photo,
                        'phone' => '',
                        'postal_code' => '',
                        'language_id' => $language_id,
                        'group_id' => $default_membership,
                        'registered_date' => date('Y-m-d'),
                        'last_login' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ]
                );

                if ($this->model->affected_rows() > 0) {
                    $insert_id = $this->model->insert_id();

                    $this->model->insert(
                        'app__users_oauth',
                        [
                            'user_id' => $insert_id,
                            'service_provider' => $provider,
                            'access_token' => $params->identifier,
                            'status' => 1
                        ]
                    );

                    $this->_send_welcome_email($params);
                }

                return $this->_validate($params);
            }
        }
    }

    private function _send_welcome_email($params = [])
    {
        // To working with Google SMTP, make sure to activate less secure apps setting
        $host = get_setting('smtp_host');
        $username = get_setting('smtp_username');
        $password = (get_setting('smtp_password') ? service('encrypter')->decrypt(base64_decode(get_setting('smtp_password'))) : '');
        $sender_email = (get_setting('smtp_email_masking') ? get_setting('smtp_email_masking') : (service('request')->getServer('SERVER_ADMIN') ? service('request')->getServer('SERVER_ADMIN') : 'webmaster@' . service('request')->getServer('SERVER_NAME')));
        $sender_name = (get_setting('smtp_sender_masking') ? get_setting('smtp_sender_masking') : get_setting('app_name'));

        $this->email = \Config\Services::email();

        if ($host && $username && $password) {
            $config['userAgent'] = 'Aksara';
            $config['protocol'] = 'smtp';
            $config['SMTPCrypto'] = 'ssl';
            $config['SMTPTimeout'] = 5;
            $config['SMTPHost'] = (strpos($host, '://') !== false ? trim(substr($host, strpos($host, '://') + 3)) : $host);
            $config['SMTPPort'] = get_setting('smtp_port');
            $config['SMTPUser'] = $username;
            $config['SMTPPass'] = $password;
        } else {
            $config['protocol'] = 'mail';
        }

        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailType'] = 'html'; // Text or html
        $config['wordWrap'] = true;
        $config['validation'] = true; // Bool whether to validate email or not

        $this->email->initialize($config);

        $this->email->setFrom($sender_email, $sender_name);
        $this->email->setTo($params->email);

        $this->email->setSubject(phrase('Welcome to') . ' ' . get_setting('app_name'));
        $this->email->setMessage('
            <!DOCTYPE html>
            <html>
                <head>
                    <meta name="viewport" content="width=device-width" />
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>
                        ' . phrase('Welcome to') . ' ' . get_setting('app_name') . '
                    </title>
                </head>
                <body>
                    <p>
                        ' . phrase('Hi') . ', <b>' . $params->first_name . ' ' . $params->last_name . '</b>
                    </p>
                    <p>
                        ' . phrase('You are successfully registered to our website.') . ' ' . phrase('Now you can sign in to our website using your ' . $provider . ' account.') . ' ' . phrase('Make sure to set your password and username to secure your account.') . '
                    </p>
                    <p>
                        ' . phrase('Please contact us directly if you still unable to signing in.') . '
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

        if (! $this->email->send()) {
            //return throw_exception(400, array('message' => $this->email->printDebugger()));
        }
    }

    /**
     * Get active year
     */
    private function _get_active_years()
    {
        $query = $this->model->get_where(
            'app__years',
            [
                'status' => 1
            ]
        )
        ->result();

        return $query;
    }

    /**
     * _resize_image
     * Generate the thumbnail of uploaded image
     *
     * @param mixed|null $path
     * @param mixed|null $filename
     * @param mixed|null $type
     */
    private function _resize_image($path = null, $filename = null, $type = null, $width = 0, $height = 0)
    {
        $source = UPLOAD_PATH . '/' . $path . '/' . $filename;
        $target = UPLOAD_PATH . '/' . $path . ($type ? '/' . $type : null) . '/' . $filename;

        $imageinfo = getimagesize($source);
        $master_dimension = ($imageinfo[0] > $imageinfo[1] ? 'width' : 'height');

        // Load image manipulation library
        $this->image = \Config\Services::image('gd');

        // Resize image
        if ($this->image->withFile($source)->resize($width, $height, true, $master_dimension)->save($target)) {
            // Crop image after resized
            $this->image->withFile($target)->fit($width, $height, 'center')->save($target);
        }
    }
}
