<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LanguageModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Admin > Languages CRUD controller
 *
 * Handles management of languages in the back-office:
 *  • Listing / pagination
 *  • Create / Edit / Delete (soft)
 *  • Set as default
 *
 * The heavy business–rules live in the [`app/Models/LanguageModel.php`](smapp/app/Models/LanguageModel.php:1)
 * so the controller mainly orchestrates validation & redirections.
 */
class Languages extends BaseController
{
    /** @var LanguageModel */
    protected $model;

    public function __construct()
    {
        $this->model = new LanguageModel();
    }

   /**
    * GET /languages[/scope]
    *
    * Lists languages filtered by scope:
    *   • active   → status = active
    *   • pending  → status = pending
    *   • passive  → status = passive
    *   • deleted  → only soft-deleted rows
    *   • (none)   → all rows
    *
    * @param string|null $scope
    */
   public function index(?string $scope = null)
   {
       $perPage = 20;

       $builder = $this->model
           ->orderBy('is_default', 'DESC')
           ->orderBy('id', 'ASC');

       switch ($scope) {
           case 'active':
           case 'pending':
           case 'passive':
               $builder->where('status', $scope);
               break;
           case 'deleted':
               $builder->onlyDeleted();
               break;
           default:
               $scope = null; // show all
               break;
       }

       $languages = $builder->paginate($perPage);

       $data = [
           'title'       => $this->adminLang('language_settings'),
           'languages'   => $languages,
           'pager'       => $this->model->pager,
           'admin_route' => get_smapp_config('admin_route', 'smapp'),
           'language'    => $this->language,
           'filter'      => $scope,
       ];

       return view('admin/languages/index', $data);
   }

    /**
     * GET /languages/create
     * Display create form
     */
    public function create()
    {
        $data = [
            'title'         => $this->adminLang('add') . ' ' . $this->adminLang('menu.languages'),
            'languageRow'   => [],
            'validation'    => service('validation'),
            'action'        => 'store',
            'admin_route'   => get_smapp_config('admin_route', 'smapp'),
            'language'      => $this->language,
        ];

        return view('admin/languages/form', $data);
    }

    /**
     * POST /languages/store
     */
    public function store(): RedirectResponse
    {
        $payload = $this->request->getPost();

        if (! $this->model->insert($payload)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        return redirect()->to(get_admin_url_with_language('languages', $this->language));
    }

    /**
     * GET /languages/edit/{id}
     */
    public function edit(int $id)
    {
        $languageRow = $this->model->find($id);
        if (! $languageRow) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title'         => $this->adminLang('edit') . ' ' . $languageRow['code'],
            'languageRow'   => $languageRow,
            'validation'    => service('validation'),
            'action'        => 'update/' . $id,
            'admin_route'   => get_smapp_config('admin_route', 'smapp'),
            'language'      => $this->language,
        ];

        return view('admin/languages/form', $data);
    }

    /**
     * POST /languages/update/{id}
     */
    public function update(int $id): RedirectResponse
    {
        $payload = $this->request->getPost();
        // Add the current record's ID so the {id} placeholder in validation rules
        // like `is_unique[languages.code,id,{id}]` is properly replaced,
        // preventing false “code must be unique” errors when editing.
        $payload['id'] = $id;

        if (! $this->model->update($id, $payload)) {
            return redirect()->back()->withInput()->with('errors', $this->model->errors());
        }

        session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        return redirect()->to(get_admin_url_with_language('languages', $this->language));
    }

    /**
     * POST /languages/delete/{id}
     */
    public function delete(int $id): RedirectResponse
    {
        try {
            $this->model->delete($id);
            session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        } catch (\Throwable $e) {
            session()->setFlashdata('error_message', $e->getMessage());
        }

        return redirect()->to(get_admin_url_with_language('languages', $this->language));
    }

    /**
     * POST /languages/default/{id}
     * Mark given language as the new default.
     */
    public function setDefault(int $id): RedirectResponse
    {
        $row = $this->model->find($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound();
        }

        // model hook will unset previous default
        $this->model->update($id, ['is_default' => 1, 'status' => 'active']);

        session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        return redirect()->to(get_admin_url_with_language('languages', $this->language));
    }

    /**
     * POST /languages/restore/{id}
     *
     * Restores a soft-deleted language and sets its status to “passive”.
     */
    public function restore(int $id): RedirectResponse
    {
        $row = $this->model->onlyDeleted()->find($id);
        if (! $row) {
            throw PageNotFoundException::forPageNotFound();
        }

        // Restore record: bypass field protection so we can null-out deleted_at
        $this->model->protect(false);
        $this->model->update($id, [
            'deleted_at' => null,
            'status'     => 'passive',
        ]);
        $this->model->protect(true);

        session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        return redirect()->to(get_admin_url_with_language('languages/deleted', $this->language));
    }

    /**
     * POST /languages/purge/{id}
     *
     * Permanently removes a custom language from the system.
     */
    public function purge(int $id): RedirectResponse
    {
        try {
            $this->model->hardDelete($id);
            session()->setFlashdata('success_message', $this->adminLang('messages.success'));
        } catch (\Throwable $e) {
            session()->setFlashdata('error_message', $e->getMessage());
        }

        return redirect()->to(get_admin_url_with_language('languages/deleted', $this->language));
    }
}