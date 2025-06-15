<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

/**
 * Languages table migration.
 *
 * Handles both core (system) and custom languages with rich metadata
 * required by the multilingual CMS.
 */
class CreateLanguagesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // ISO-639-1 language code – e.g. "tr", "en"
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'unique'     => true,
            ],

            // Full locale – e.g. "tr_TR", "en_US"
            'locale' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
            ],

            // Translation key that will be resolved from language files
            'name_key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],

            // Native language name, displayed to users
            'native_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],

            // Writing direction
            'direction' => [
                'type'       => 'ENUM',
                'constraint' => ['ltr', 'rtl'],
                'default'    => 'ltr',
            ],

            // Runtime visibility in system / site
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'passive', 'pending'],
                'default'    => 'pending',
            ],

            // Marks the default language (only one row should have value 1)
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
            ],

            // Core languages cannot be deleted
            'is_system_language' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
            ],

            // Source of the language files (core|custom)
            'origin' => [
                'type'       => 'ENUM',
                'constraint' => ['core', 'custom'],
                'default'    => 'custom',
            ],

            // e.g. "tr.png" – may live in assets/flags
            'flag_icon' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],

            'date_format' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],

            'time_format' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],

            // Fallback language code when a translation is missing
            'fallback_lang' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true,
                'default'    => 'en',
            ],

            // Additional extensible settings (JSON)
            'settings' => [
                'type' => 'JSON',
                'null' => true,
            ],

            // Soft-deletion timestamp
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],

            'updated_at' => [
                'type'     => 'DATETIME',
                'null'     => true,
                'on_update' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        // The `code` column is already UNIQUE, so an extra index would duplicate the key name.
        $this->forge->addKey('locale');
        $this->forge->createTable('languages', true);
    }

    public function down()
    {
        $this->forge->dropTable('languages', true);
    }
}
