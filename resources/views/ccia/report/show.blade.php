{{--Copyright (c) 2016 "Werner Maisl"--}}

{{--This file is part of the Aurora Webinterface--}}

{{--The Aurora Webinterface is free software: you can redistribute it and/or modify--}}
{{--it under the terms of the GNU Affero General Public License as--}}
{{--published by the Free Software Foundation, either version 3 of the--}}
{{--License, or (at your option) any later version.--}}

{{--This program is distributed in the hope that it will be useful,--}}
{{--but WITHOUT ANY WARRANTY; without even the implied warranty of--}}
{{--MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the--}}
{{--GNU Affero General Public License for more details.--}}

{{--You should have received a copy of the GNU Affero General Public License--}}
{{--along with this program. If not, see <http://www.gnu.org/licenses/>.<!DOCTYPE html>--}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Report Details</div>

                <table class="table">
                    <tbody>
                    <tr>
                        <td><b>Title:</b></td>
                        <td>{{$report->title}}</td>
                    </tr>
                    <tr>
                        <td><b>Report Date:</b></td>
                        <td>{{$report->report_date}}</td>
                    </tr>
                    <tr>
                        <td><b>Status:</b></td>
                        <td>{{$report->status}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Report Transcripts</div>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Transcript ID</th>
                        <th>Character Name</th>
                        <th>Interviewer</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($transcripts as $transcript)
                    <tr>
                        <td>{{$transcript->id}}</td>
                        <td>{{$transcript->character->name}}</td>
                        <td>{{$transcript->interviewer}}</td>
                        <td><a href="{{route('ccia.report.transcript.get',['transcript_id'=>$transcript->id])}}" class="btn btn-success" role="button">View</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection