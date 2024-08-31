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

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Insert main language (default)
        $this->db->table('app__languages')->insertBatch([
            [
                'language' => 'Default (English)',
                'description' => 'Default language',
                'code' => 'en',
                'locale' => 'en-US,en_US,en_US.UTF8,en-us,en,english',
                'status' => 1
            ],
            [
                'language' => 'Bahasa Indonesia',
                'description' => 'Terjemahan bahasa Indonesia',
                'code' => 'id',
                'locale' => 'id-ID,id_ID,id_ID.UTF8,id-id,id,indonesian',
                'status' => 1
            ],
            [
                'language' => 'Русский',
                'description' => 'Русский перевод',
                'code' => 'ru',
                'locale' => 'ru-RU,ru_RU,ru_RU.UTF8,ru-ru,ru,russian',
                'status' => 1
            ]
        ]);

        // Insert the main configuration to app__settings
        $this->db->table('app__settings')->insert([
            'app_name' => htmlspecialchars(trim(session()->get('site_title'))),
            'app_description' => htmlspecialchars(trim(session()->get('site_description'))),
            'app_logo' => 'logo.png',
            'app_icon' => 'logo.png',
            'frontend_theme' => 'default',
            'backend_theme' => 'backend',
            'app_language' => htmlspecialchars(trim(session()->get('language') == 'id' ? 2 : 1)),
            'office_name' => 'Aksara Laboratory',
            'office_phone' => '+6281381614558',
            'office_email' => 'info@example.com',
            'office_fax' => '',
            'office_address' => '2nd Floor Example Tower Building, Some Road Name, Any Region',
            'office_map' => '[]',
            'one_device_login' => 0,
            'username_change' => 1,
            'frontend_registration' => 1,
            'default_membership_group' => 3,
            'auto_active_registration' => 1,
            'google_analytics_key' => '',
            'openlayers_search_provider' => 'openlayers',
            'openlayers_search_key' => '',
            'default_map_tile' => '',
            'facebook_app_id' => '',
            'facebook_app_secret' => '',
            'google_client_id' => '',
            'google_client_secret' => '',
            'twitter_username' => '',
            'instagram_username' => '',
            'whatsapp_number' => '',
            'action_sound' => 1
        ]);

        // Add core group privileges
        $this->db->table('app__groups_privileges')->insertBatch([
            [
                'path' => 'addons',
                'privileges' => json_encode(['index', 'detail', 'install']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'addons/ftp',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'addons/modules',
                'privileges' => json_encode(['index', 'detail', 'import', 'update', 'delete']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'addons/themes',
                'privileges' => json_encode(['index', 'detail', 'import', 'update', 'delete', 'activate', 'customize']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/account',
                'privileges' => json_encode(['index', 'edit', 'logs']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/logs',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/logs/activities',
                'privileges' => json_encode(['index', 'read', 'truncate', 'delete', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/logs/errors',
                'privileges' => json_encode(['index', 'remove', 'clear']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/cleaner',
                'privileges' => json_encode(['index', 'clean']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/connections',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf', 'connect']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/countries',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/groups',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/groups/adjust_privileges',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/groups/privileges',
                'privileges' => json_encode(['index', 'create', 'update', 'read', 'delete']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/menus',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/settings',
                'privileges' => json_encode(['index', 'update']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/translations',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/translations/synchronize',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/translations/translate',
                'privileges' => json_encode(['index', 'delete_phrase']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/updater',
                'privileges' => json_encode(['index', 'update']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/users',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/users/privileges',
                'privileges' => json_encode(['index', 'update']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'administrative/years',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'apis',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'apis/debug_tool',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'apis/documentation',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'apis/services',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/blogs',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/blogs/categories',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/comments',
                'privileges' => json_encode(['index', 'read', 'export', 'print', 'pdf', 'hide']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/comments/feedback',
                'privileges' => json_encode(['index', 'read', 'export', 'print', 'pdf', 'hide']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/galleries',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/pages',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/announcements',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/carousels',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/faqs',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/inquiries',
                'privileges' => json_encode(['index', 'read', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/media',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/partials/testimonials',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/peoples',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'cms/videos',
                'privileges' => json_encode(['index', 'create', 'read', 'update', 'delete', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'dashboard',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'notifications',
                'privileges' => json_encode(['index', 'read', 'export', 'print', 'pdf']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
            [
                'path' => 'notifications/setting',
                'privileges' => json_encode(['index']),
                'last_generated' => date('Y-m-d H:i:s')
            ],
        ]);

        // Add main user's group (superuser)
        $this->db->table('app__groups')->insert([
            'group_name' => 'Global Administrator',
            'group_description' => 'Super User',
            'group_privileges' => '{"addons":["index","detail","install"],"addons/ftp":["index"],"addons/modules":["index","detail","import","update","delete"],"addons/themes":["index","detail","import","update","delete","activate","customize"],"administrative":["index"],"administrative/account":["index","edit","logs"],"administrative/logs":["index"],"administrative/logs/activities":["index","read","truncate","delete","print","pdf"],"administrative/logs/errors":["index","remove","clear"],"administrative/cleaner":["index","clean"],"administrative/connections":["index","create","read","update","delete","export","print","pdf","connect"],"administrative/countries":["index","create","read","update","delete","export","print","pdf"],"administrative/groups":["index","create","read","update","delete","export","print","pdf"],"administrative/groups/adjust_privileges":["index","create","read","update","delete","export","print","pdf"],"administrative/groups/privileges":["index","create","update","read","delete"],"administrative/menus":["index","create","read","update","delete","export","print","pdf"],"administrative/settings":["index","update"],"administrative/translations":["index","create","read","update","delete","export","print","pdf"],"administrative/translations/synchronize":["index"],"administrative/translations/translate":["index","delete_phrase"],"administrative/updater":["index","update"],"administrative/users":["index","create","read","update","delete","export","print","pdf"],"administrative/users/privileges":["index","update"],"administrative/years":["index","create","read","update","delete","export","print","pdf"],"apis":["index"],"apis/debug_tool":["index"],"apis/documentation":["index"],"apis/services":["index","create","read","update","delete","export","print","pdf"],"cms":["index"],"cms/blogs":["index","create","read","update","delete","export","print","pdf"],"cms/blogs/categories":["index","create","read","update","delete","export","print","pdf"],"cms/comments":["index","read","export","print","pdf","hide"],"cms/comments/feedback":["index","read","export","print","pdf","hide"],"cms/galleries":["index","create","read","update","delete","export","print","pdf"],"cms/pages":["index","create","read","update","delete","export","print","pdf"],"cms/partials":["index"],"cms/partials/announcements":["index","create","read","update","delete","export","print","pdf"],"cms/partials/carousels":["index","create","read","update","delete","export","print","pdf"],"cms/partials/faqs":["index","create","read","update","delete","export","print","pdf"],"cms/partials/inquiries":["index","read","delete","export","print","pdf"],"cms/partials/media":["index"],"cms/partials/testimonials":["index","create","read","update","delete","export","print","pdf"],"cms/peoples":["index","create","read","update","delete","export","print","pdf"],"cms/videos":["index","create","read","update","delete","export","print","pdf"],"dashboard":["index"],"notifications":["index","read","export","print","pdf"],"notifications/setting":["index"]}',
            'status' => 1
        ]);

        // Add technical user's group
        $this->db->table('app__groups')->insert([
            'group_name' => 'Technical',
            'group_description' => 'Group user for technical support',
            'group_privileges' => '{"administrative":["index"],"administrative/account":["index","edit","logs"],"apis":["index"],"apis/debug_tool":["index"],"apis/documentation":["index"],"apis/services":["index","create","read","update","delete","export","print","pdf"],"cms":["index"],"cms/blogs":["index","create","read","update","delete","export","print","pdf"],"cms/blogs/categories":["index","create","read","update","delete","export","print","pdf"],"cms/comments":["index","read","export","print","pdf","hide"],"cms/comments/feedback":["index","read","export","print","pdf","hide"],"cms/galleries":["index","create","read","update","delete","export","print","pdf"],"cms/pages":["index","create","read","update","delete","export","print","pdf"],"cms/partials":["index"],"cms/partials/announcements":["index","create","read","update","delete","export","print","pdf"],"cms/partials/carousels":["index","create","read","update","delete","export","print","pdf"],"cms/partials/faqs":["index","create","read","update","delete","export","print","pdf"],"cms/partials/inquiries":["index","read","delete","export","print","pdf"],"cms/partials/media":["index"],"cms/partials/testimonials":["index","create","read","update","delete","export","print","pdf"],"cms/peoples":["index","create","read","update","delete","export","print","pdf"],"cms/videos":["index","create","read","update","delete","export","print","pdf"],"dashboard":["index"],"notifications":["index","read","export","print","pdf"]}',
            'status' => 1
        ]);

        // Add subscriber user's group
        $this->db->table('app__groups')->insert([
            'group_name' => 'Subscriber',
            'group_description' => 'Group user for subscriber',
            'group_privileges' => '{"administrative":["index"],"administrative/account":["index","edit","logs"],"dashboard":["index"]}',
            'status' => 1
        ]);

        // Insert superuser
        $this->db->table('app__users')->insert([
            'email' => session()->get('email'),
            'password' => password_hash(session()->get('password') . session()->get('encryption'), PASSWORD_DEFAULT),
            'username' => session()->get('username'),
            'first_name' => session()->get('first_name'),
            'last_name' => session()->get('last_name'),
            'gender' => 0,
            'bio' => '',
            'photo' => '',
            'address' => '',
            'phone' => '',
            'postal_code' => '',
            'language_id' => (session()->get('language') == 'id' ? 2 : 1),
            'country_id' => 0,
            'group_id' => 1,
            'registered_date' => date('Y-m-d'),
            'last_login' => date('Y-m-d H:i:s'),
            'status' => 1
        ]);
    }
}
