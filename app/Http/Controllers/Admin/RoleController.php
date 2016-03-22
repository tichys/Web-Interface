<?php
/**
 * Copyright (c) 2016 "Werner Maisl"
 *
 * This file is part of Aurorastation-Wi
 * Aurorastation-Wi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace App\Http\Controllers\Admin;

use App\Services\Auth\ForumUserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SitePermission;
Use App\Models\SiteRole;

class RoleController extends Controller
{
    public function __construct(Request $request)
    {
        if ($request->user()->cannot('admin_site_roles_show')) {
            abort('403', 'You do not have the required permission');
        }
    }

    public function index(Request $request)
    {
        $roles = SiteRole::get();
        return view('/admin/roles/index', ['roles' => $roles]);
    }

    public function getAdd(Request $request)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }

        return view('/admin/roles/add');
    }

    public function postAdd(Request $request)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $this->validate($request, [
            'name' => 'required|max:50', //Can be increased to 255
            'label' => 'required|max:50', //Can be increased to 255
            'description' => 'required|max:100',
        ]);

        $role = new SiteRole($request->all());
        $role->save();

        return redirect()->route('admin.roles.index');
    }

    public function getEdit(Request $request, $role_id)
    {
        $role = SiteRole::findOrfail($role_id);

        $all_permissions = SitePermission::pluck('name','id');
        $assigned_permissions = $role->permissions()->pluck('name','id');

        $avail_permissions = array_diff($all_permissions->toArray(),$assigned_permissions->toArray());

        $assigned_users = $role->get_users(true);

        return view('/admin/roles/edit', ['role' => $role,'avail_permissions'=>$avail_permissions,'assigned_users'=>$assigned_users]);
    }

    public function postEdit(Request $request, $role_id)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $this->validate($request, [
            'name' => 'required|max:50', //Can be increased to 255
            'label' => 'required|max:50', //Can be increased to 255
            'description' => 'required|max:100',
        ]);

        $role = SiteRole::findOrfail($role_id);
        $role->name = $request->input('name');
        $role->label = $request->input('label');
        $role->description = $request->input('description');
        $role->save();

        return redirect()->route('admin.roles.index');
    }

    public function delete(Request $request, $role_id)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }

        $role = SiteRole::findOrFail($role_id);
        $role->delete();

        return redirect()->route('admin.roles.index');
    }

    public function addPermission(Request $request, $role_id)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $role = SiteRole::findOrFail($role_id);
        $permission = SitePermission::findOrFail($request->input('permission'));
        $role->permissions()->attach($permission);

        return redirect()->route('admin.roles.edit.get',['role_id' => $role_id]);
    }

    public function removePermission(Request $request, $role_id)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $role = SiteRole::findOrFail($role_id);
        $permission = SitePermission::findOrFail($request->input('permission'));
        $role->permissions()->detach($permission);

        return redirect()->route('admin.roles.edit.get',['role_id' => $role_id]);
    }

    public function addUser(Request $request, $role_id)
    {
        $this->validate($request, [
            'user_id' => 'numeric'
        ]);
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $role = SiteRole::findOrFail($role_id);
        $user = ForumUserModel::findOrFail($request->input('user_id'));

        $user->roles()->attach($role);
        return redirect()->route('admin.roles.edit.get',['role_id' => $role_id]);
    }

    public function removeUser(Request $request, $role_id)
    {
        if ($request->user()->cannot('admin_site_roles_edit')) {
            abort('403', 'You do not have the required permission');
        }
        $role = SiteRole::findOrFail($role_id);
        $user = ForumUserModel::findOrFail($request->input('user'));

        $user->roles()->detach($role);
        return redirect()->route('admin.roles.edit.get',['role_id' => $role_id]);
    }
}
