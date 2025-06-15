<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * LanguageModel
 *
 * Handles CRUD for the `languages` table while enforcing business-rules:
 *  • Prevents deletion of system languages (is_system_language = 1)
 *  • Ensures only one default language (is_default = 1) at all times
 *  • Automatically syncs changes to default language with smapp_config.php
 *
 * NOTE: All write operations should go through this model to keep the
 *       application state consistent.
 */
class LanguageModel extends Model
{
    protected $table         = 'languages';
    protected $primaryKey    = 'id';

    protected $allowedFields = [
        'code',
        'locale',
        'name_key',
        'native_name',
        'direction',
        'status',
        'is_default',
        'is_system_language',
        'origin',
        'flag_icon',
        'date_format',
        'time_format',
        'fallback_lang',
        'settings',
    ];

    protected $useSoftDeletes  = true;
    protected $useTimestamps   = true;
    protected $returnType      = 'array';
    protected $dateFormat      = 'datetime'; // respects DB driver default

    // --------------------------------------------------------------------
    // Validation Rules
    // --------------------------------------------------------------------
    protected $validationRules = [
        // Placeholder “id” used in is_unique rule; declare a basic rule so
        // Validation doesn’t complain when it sees {id} placeholder.
        'id'     => 'permit_empty|is_natural_no_zero',
        'code'   => 'required|alpha|min_length[2]|max_length[5]|is_unique[languages.code,id,{id}]',
        'locale' => 'required|min_length[2]|max_length[10]',
        'status' => 'required|in_list[active,passive,pending]',
        'direction' => 'required|in_list[ltr,rtl]',
    ];

    // --------------------------------------------------------------------
    // Hooks
    // --------------------------------------------------------------------
    protected $beforeInsert = ['enforceBusinessRules'];
    protected $beforeUpdate = ['enforceBusinessRules'];
    protected $beforeDelete = ['preventSystemLanguageDelete'];

    /**
     * Business rules executed before insert/update.
     *
     * @param array $data
     * @return array
     */
    protected function enforceBusinessRules(array $data): array
    {
        if (! isset($data['data'])) {
            return $data;
        }

        $payload = &$data['data'];

        // (1) Only one default language
        if (array_key_exists('is_default', $payload) && (int) $payload['is_default'] === 1) {
            // Reset others
            $this->builder()->set('is_default', 0)->update();
        }

        // (2) If status is passive ensure is_default = 0
        if (isset($payload['status']) && $payload['status'] !== 'active') {
            $payload['is_default'] = 0;
        }

        return $data;
    }

    /**
     * Prevent deleting system languages via softDelete().
     *
     * Runs before `delete()` or `purgeDeleted()`. Receives the hook payload
     * which contains the IDs slated for deletion. If any of those rows are
     * marked as `is_system_language = 1` the operation is aborted.
     *
     * @param array $data ['id' => int|array, 'purge' => bool]
     * @return array
     */
    protected function preventSystemLanguageDelete(array $data): array
    {
        // The hook provides either a single ID or an array of IDs.
        if (empty($data['id'])) {
            return $data;
        }

        $ids = (array) $data['id'];

        // Detect if any of the IDs map to a system language
        $count = $this->whereIn('id', $ids)
                      ->where('is_system_language', 1)
                      ->countAllResults();

        if ($count > 0) {
            throw new \RuntimeException('System languages cannot be deleted.');
        }

        return $data;
    }

    // --------------------------------------------------------------------
    // Public helpers
    // --------------------------------------------------------------------

    /**
     * Hard-delete custom language (and optionally remove frontend/lang files).
     */
    public function hardDelete(int $id, bool $purgeFiles = true): bool
    {
        // Fetch regardless of soft-delete status
        $language = $this->withDeleted()->find($id);
 
        if (! $language) {
            return false;
        }
 
        if ($language['is_system_language']) {
            throw new \RuntimeException('System languages cannot be deleted.');
        }
 
        // Permanently remove DB row (purge)
        // Using Model::delete with $purge = true bypasses SoftDeletes filter
        $this->delete($id, true);

        // Remove language directories if requested
        if ($purgeFiles) {
            $langDir = ROOTPATH . 'public_html/' . $language['code'];
            if (is_dir($langDir)) {
                helper('filesystem');
                delete_files($langDir, true);
                @rmdir($langDir);
            }
        }

        return true;
    }
}