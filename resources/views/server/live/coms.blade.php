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
<div class="container" id="app">
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Send a Fax <span class="pull-right">@{{ faxstatus }}</span></div>
                <div class="panel-body">
                    <form @submit.prevent="sendfax">
                        <div class="form-group">
                            <label for="faxTitle">Fax Title</label>
                            <input type="text" class="form-control" id="faxTitle" placeholder="Enter a Fax Title" v-model="faxtitle">
                        </div>

                        <div class="form-group">
                            <label for="faxBody">Fax Message</label>
                            <textarea id="faxBody" class="form-control" rows="4" cols="50" name="data" v-model="faxbody" placeholder="Use HTML for Formating. New Lines are converted automatically"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="faxAnnounce">Annonce Fax</label>
                            <div class="checkbox" id="faxAnnounce">
                                <label>
                                    <input type="checkbox" v-model="faxannounce"> Announce Fax
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="faxLocation">Target Locations</label>
                            <select class="form-control" multiple v-model="faxtargets" id="faxLocation">
                                <option v-for="fax in faxmachines">@{{ fax }}</option>
                            </select>
                        </div>

                        <div class="alert alert-danger" role="alert" v-show="!faxtitle || !faxbody || !faxtargets.length">
                            <ul>
                                <li v-show="!faxtitle">You must enter a Title</li>
                                <li v-show="!faxbody">You must enter a Message</li>
                                <li v-show="!faxtargets.length">You must specify a Target</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-default" v-if="faxtitle && faxbody && faxtargets.length">Send Fax</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">Fax Status</div>
                <div class="panel-body">
                    <p><strong>Fax Status:</strong> @{{faxstatus}}</p>
                    <p><strong>Delivery Status:</strong></p>
                    <ul>
                        <li v-for="(location, status) in faxresponse">@{{ location }} - @{{ status }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Send a CommandReport <span class="pull-right">@{{ reportstatus }}</span></div>

                <div class="panel-body">
                    <form @submit.prevent="sendreport">
                        <div class="form-group">
                            <label for="reportTitle">Report Title</label>
                            <input id="reportTitle" class="form-control" type="text" name="reporttitle" v-model="reporttitle" placeholder="Enter a Report Title">
                        </div>

                        <div class="form-group">
                            <label for="reportBody">Report Body</label>
                            <textarea id="reportBody" class="form-control" rows="4" cols="50" name="reportbody" v-model="reportbody" placeholder="Use HTML for Formating. New Lines are converted automatically"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="reportAnnounce">Annonce Report</label>
                            <div class="checkbox" id="reportAnnounce">
                                <label>
                                    <input type="checkbox" v-model="reportannounce"> Announce Report
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reportType">Report Type</label>
                            <select class="form-control" v-model="reporttype" id="reportType">
                                <option value="ccia" selected>CCIA</option>
                                <option value="freeform">Freeform</option>
                            </select>
                        </div>

                        <div class="form-group" v-show='reporttype == "ccia"'>
                            <label for="reportSender">Report Sender</label>
                            <input id="reportSender" class="form-control" type="text" name="reportsender" v-model="reportsender" placeholder="Enter a CCIA Agent Name">
                        </div>

                        <div class="alert alert-danger" role="alert" v-show="!reporttitle || !reportbody">
                            <ul>
                                <li v-show="!reporttitle">You must enter a Title</li>
                                <li v-show="!reportbody">You must enter a Message</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-default" v-if="reporttitle && reportbody">Send Announcement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--<pre>--}}
{{--@{{ $data | json }}--}}
    {{--</pre>--}}
</div>
@endsection

@section('javascripts')
    <script src="{{asset('assets/js/vue_1_0_26.js')}}"></script>
    <script src="{{asset('assets/js/vue-resource_0_9_3.js')}}"></script>

    <script src="{{asset('assets/pages/server-live-coms.js')}}"></script>
@endsection
