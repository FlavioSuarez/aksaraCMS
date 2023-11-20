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

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AppLogVisitors extends Migration
{
    public function up()
    {
        // Add columns to table
        $this->forge->addField([
            'ip_address' => [
                'type' => 'varchar',
                'constraint' => 45,
                'null' => false
            ],
            'timestamp' => [
                'type' => 'timestamp',
                'null' => false
            ],
            'browser' => [
                'type' => 'varchar',
                'constraint' => 32,
                'null' => false
            ],
            'platform' => [
                'type' => 'varchar',
                'constraint' => 64,
                'null' => false
            ]
        ]);

        // Add primary and unique index
        $this->forge->addKey('ip_address', false, false);

        // Create table
        $this->forge->createTable('app__log_visitors');
    }

    public function down()
    {
        // Safe abstraction
    }
}
