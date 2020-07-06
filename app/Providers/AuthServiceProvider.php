<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\MstUserpermission;
use App\MstPermission;
use App\MstUser;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('Allow-Permission',function($user, $permissionCode){
            $Permissions = MstUserpermission::where('userid', '=', Auth::user()->iuserid)->get();
            $perms = [];
            for($i=0; $i < count($Permissions); $i++){
                $perms[] = $Permissions[$i]->permission_id;
            }
            if(in_array($permissionCode, $perms)){
                return true;
            }
        });
    }
}
