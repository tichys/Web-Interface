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

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ServerForm;
use MongoDB\Driver\Server;
use Yajra\Datatables\Datatables;

class FormController extends Controller
{
    public function __construct(Request $request)
    {
        if($request->user()->cannot('admin_forms_show'))
        {
            abort('403','You do not have the required permission');
        }
    }

    public function index()
    {
        return view('admin.forms.index');
    }

    public function getEdit($form_id)
    {
        $form = ServerForm::findOrFail($form_id);
        return view('admin.forms.edit', ['form' => $form]);
    }

    public function postEdit($form_id, Request $request)
    {
        if($request->user()->cannot('admin_forms_edit'))
        {
            abort('403','You do not have the required permission');
        }
        $this->validate($request,[
            'id' => 'required|max:9999|numeric',
            'name' => 'required|max:50',
            'department' => 'required|max:32',
            'data' => 'required',
            'info' => 'required'
        ]);

        $form = ServerForm::findOrFail($form_id);
        $form->id = $request->input('id');
        $form->name = $request->input('name');
        $form->department = $request->input('department');
        $form->data = $request->input('data');
        $form->info = $request->input('info');
        $form->save();

        return redirect()->route('admin.forms.index');
    }

    public function delete($form_id, Request $request)
    {
        if($request->user()->cannot('admin_forms_edit'))
        {
            abort('403','You do not have the required permission');
        }
        $form = ServerForm::findOrFail($form_id);
        $form->delete();

        return redirect()->route('admin.forms.index');
    }

    public function getAdd(Request $request)
    {
        if($request->user()->cannot('admin_forms_edit'))
        {
            abort('403','You do not have the required permission');
        }

        return view('admin.forms.add');
    }

    public function postAdd(Request $request)
    {
        if($request->user()->cannot('admin_forms_edit'))
        {
            abort('403','You do not have the required permission');
        }

        $this->validate($request,[
            'id' => 'required|max:9999|numeric',
            'name' => 'required|max:50',
            'department' => 'required|max:32',
            'data' => 'required',
            'info' => 'required'

        ]);

        $form = new ServerForm();
        $form->id = $request->input('id');
        $form->name = $request->input('name');
        $form->department = $request->input('department');
        $form->data = $request->input('data');
        $form->info = $request->input('info');
        $form->save();

        return redirect()->route('admin.forms.index');
    }

    public function getFormData()
    {
        $forms = ServerForm::select(['form_id','id', 'name', 'department']);

        return Datatables::of($forms)
            ->removeColumn('form_id')
            ->editColumn('name', '<a href="{{route(\'admin.forms.edit.get\',[\'form\'=>$form_id])}}">{{$name}}</a>')
            ->addColumn('action','<p><a href="{{route(\'admin.forms.edit.get\',[\'form\'=>$form_id])}}" class="btn btn-info" role="button">Show/Edit</a>  @can(\'admin_forms_edit\')<a href="{{route(\'admin.forms.delete\',[\'form\'=>$form_id])}}" class="btn btn-danger" role="button">Delete</a>@endcan()</p>')
            ->make();
    }
}