<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class AbstractController extends Controller
{
    protected $repository;

    protected $repositoryName;

    protected $user;

    protected $compacts;

    protected $lang = [
        'prefix' => 'repositories.',
        'replacements' => [],
    ];

    public function __construct($repository = null)
    {
        $this->middleware(function ($request, $next) use ($repository) {
            $this->user = Auth::guard($this->getGuard())->user();

            if ($repository) {
                $this->repositorySetup($repository);
            }
            
            return $next($request);
        });


        $this->lang['replacements'] = [
            'object' => $this->trans($this->repositoryName),
        ];
    }

    public function repositorySetup($repository = null)
    {
        $this->repository = $repository->setUser($this->user);
        $this->repositoryName = strtolower(class_basename($this->repository->model()));
    }

    public function trans($str = null, $data = [])
    {
        $replacements = array_merge($data, $this->lang['replacements']);
        
        return trans($this->lang['prefix'] . $str, $replacements);
    }

    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }

    public function before($action, $object = null, $abort = true)
    {
        $action = in_array($action, ['index','show']) ? 'read' : 'write';
        
        if ($object == null) {
            $object = $this->repository->model();
        }

        if (!$this->user || $this->user->cannot($action, $object)) {
            return ($abort) ? abort(403, $this->trans('forbidden_to_perform', ['action' => $action])) : false;
        }

        return true;
    }
}
