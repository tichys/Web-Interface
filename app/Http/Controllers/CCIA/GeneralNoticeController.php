<?php
    
    /**
     * Copyright (c) 2016 "Sierra Brown"
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
    
    namespace App\Http\Controllers\CCIA;
    
    use Illuminate\Http\Request;
    use App\Http\Requests;
    use App\Http\Controllers\Controller;
    use Yajra\Datatables\Datatables;
    
    use App\Models\CCIAGeneralNotice;
    
    class GeneralNoticeController extends Controller
    {
        public function __construct(Request $request)
        {
            
        }
        
        public function index()
        {
            return view('ccia.generalnotice.index');
        }
        
        public function getEdit(Request $request)
        {
            
            // TODO Code that processes getEdit
            
            return redirect()->route('ccia.generalnotice.index');
        }
        
        public function postEdit(Request $request)
        {
            if ($request->user()
                        ->cannot('ccia_general_notice_edit')
            ) {
                abort('403', 'You do not have permission to edit CCIA General Notices.');
            }
            
            // TODO Code that processes postEdit
            
            return redirect()->route('ccia.generalnotice.index');
        }
        
        public function getAdd(Request $request)
        {
            if ($request->user()
                        ->cannot('ccia_general_notice_edit')
            ) {
                abort('403', 'You do not have permission to edit CCIA General Notices.');
            }
            
            // TODO Code that processes getAdd
            
            return redirect()->route('ccia.generalnotice.index');
        }
        
        public function postAdd(Request $request)
        {
            if ($request->user()
                        ->cannot('ccia_general_notice_edit')
            ) {
                abort('403', 'You do not have permission to edit CCIA General Notices.');
            }
            
            // TODO Code that processes postAdd
            
            return redirect()->route('ccia.generalnotice.index');
        }
        
        public function delete(Request $request)
        {
            if ($request->user()
                        ->cannot('ccia_general_notice_edit')
            ) {
                abort('403', 'You do not have permission to edit CCIA General Notices.');
            }
            
            // TODO Code that processes delete
            
            return redirect()->route('ccia.generalnotice.index');
        }
        
        public function getData(Request $request)
        {
            $data = CCIAGeneralNotice::select(['id', 'title']);
            
            return Datatables::of($data)
                             ->editColumn('title', '<a href="{{ route(\'ccia.generalnotice.edit.get\', [\'id\' => $id]) }}">{{$title}}</a>')
                             ->addColumn('action', '<p><a href="{{ route(\'ccia.generalnotice.edit.get\', [\'id\' => $id]) }}" class="btn btn-info" role="button">Show/Edit</a>  @can(\'ccia_general_notice_edit\')<a href="{{route(\'ccia.generalnotice.delete\', [\'id\' => $id]) }}" class="btn btn-danger" role="button">Delete</a>@endcan()</p>')
                             ->make();
            
        }
    }
