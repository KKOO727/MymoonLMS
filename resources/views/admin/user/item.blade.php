@extends('admin.newlayout.layout',['breadcom'=>['Users','Edit',$user->name]])
@section('title')
    {{ trans('admin.edit_user') }}
@endsection
@section('page')
    <div class="cards">
        <div class="card-body">

                    <div id="main" class="tab-pane active">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="/admin/user/edit/{{$user->id}}" class="form-horizontal form-bordered" method="post">
                                    {{ csrf_field() }}

                                    <div class="row form-group">
                                        <label class="col-md-3 control-label" for="inputDefault">{{ trans('admin.real_name') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" name="name" value="{{ $user->name }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.username') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $user->username }}" id="inputReadOnly" class="form-control" readonly="readonly">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-3 control-label" for="inputReadOnly">{{ trans('admin.email') }}</label>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $user->email }}" id="inputReadOnly" class="form-control text-left" dir="ltr" readonly="readonly">
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-3 control-label">Teacher Level</label>
                                        <div class="col-md-5">
                                            <select name="level" class="form-control">
                                                <option value="1" {{ $user->level=='1' ? 'selected="selected"' : '' }}>Basic</option>
                                                <option value="2" {{ $user->level=='2' ? 'selected="selected"' : '' }}>Advanced</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label class="col-md-3 control-label">{{ trans('admin.th_status') }}</label>
                                        <div class="col-md-5">
                                            <select name="mode" class="form-control populate">
                                                <option value="active" {{ $user->mode=='active' ? 'selected="selected"' : '' }}>{{ trans('admin.active') }}</option>
                                                <option value="block" {{ $user->mode=='block' ? 'selected="selected"' : '' }}>{{ trans('admin.banned') }}</option>
                                                <option value="pending_manual_verification" {{ $user->mode=='pending_manual_verification' ? 'selected="selected"' : '' }}>{{ trans('Pending Manual Verification') }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 birthday-group @if($user->mode == 'active') hidden @endif">
                                            <div class="input-group">
                                                <input type="date" name="blockDate" class="form-control" id="blockDate" value="@if(isset($meta['blockDate'])) {{date('d-m-Y',$meta['blockDate'])}} @endif">
                                                <span class="input-group-append bdatebtn" id="bdatebtn">
                                            <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                        </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button class="btn btn-lg btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped">
                                        <tr>
                                            <th colspan="2" class="text-center bold">Short Overview</th>
                                        </tr>
                                    @foreach($userMetas as $key=>$value)
                                        <?php
                                        if($key=='meta_avatar'){
                                            $value='<img src="'.asset($value).'" height="100">';
                                        }
                                        if($key=='meta_resume_file' ||  $key=='meta_certificates_file'){
                                            $value='<a href="'.asset($value).'" target="_blank" class="btn btn-primary">View File</a>';
                                        }

                                        $key=strtoupper(str_replace('meta_','',$key));

                                        ?>
                                        <tr>
                                            <th>{{ $key }}</th>
                                            <td><?php echo $value;?></td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                    </div>




                </div>
            </div>
    </div>
@endsection
