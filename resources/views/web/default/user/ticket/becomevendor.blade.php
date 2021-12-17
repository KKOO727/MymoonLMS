@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="container">
    <div class="h-20"></div>
    <div class="row">
        <div class="col-md-6 col-xs-12 tab-con">
            <div class="ucp-section-box">
			@if($user->type == 'Teacher' && )
				<div class="header back-red"><h2 style='color:#0f9898'>{{ trans('main.pending_Teacher') }}</h2></div>
			@else
                <div class="header back-red">{{ trans('main.teacherNewInfo') }}</div>
                <div class="body">
                    <form method="post" action="{!! baseURL() !!}/user/ticket/store">
                        {{ csrf_field() }}
                        <div class="form-group">
							<label class="control-label" for="inputDefault">{{ trans('main.dob') }}</label>
                            <input  type="text" name="dob" class="form-control" id="inputDefault"  data-inputmask="'alias': 'yyyy-mm-dd'" data-mask />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="inputDefault">{{ trans('main.nationality') }}</label>
                            <select name="nationality" class="form-control font-s" required>
                                @foreach($country as $cat)
                                    <option value="{{ $cat->ID }}">{{ $cat->COUNTRY }}</option>
                                @endforeach
                            </select>
                        </div>

						<div class="form-group">
							<label class="control-label" for="inputDefault">{{ trans('main.mobile') }}</label>
                            <input  type="text" name="mobile" class="form-control" id="mobile"   />
                        </div>

						<div class="form-group">
                            <label class="control-label" for="inputDefault">{{ trans('main.gender') }}</label>
                            <select name="gender" class="form-control font-s" required>
                                    <option value="Male">Male</option>
									<option value="Female">Female</option>

                            </select>
                        </div>

						<div class="form-group">
                            <label class="control-label" for="inputDefault">{{ trans('main.residence') }}</label>
                            <select name="residence" class="form-control font-s" required>
                                @foreach($country as $cat)
                                    <option value="{{ $cat->ID }}">{{ $cat->COUNTRY }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label control-label-p">{{ trans('main.pasportPhone') }}</label>
                            <div class="input-group" style="display: flex">
                                <button id="lfm_attach" data-input="pasportPhone" data-preview="holder" class="btn btn-primary">
                                    Choose
                                </button>
                                <input id="pasportPhone" class="form-control" dir="ltr" type="text" name="pasportPhone" value="{{ !empty($meta['pasportPhone']) ? $meta['pasportPhone'] : '' }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="pasportPhone">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                </div>
                            </div>
                        </div>


						<div class="form-group">
                            <label class="control-label control-label-p">{{ trans('main.certificates') }}</label>
                            <div class="input-group" style="display: flex">
                                <button id="lfm_attach1" data-input="certificates" data-preview="holder" class="btn btn-primary">
                                    Choose
                                </button>
                                <input id="certificates" class="form-control" dir="ltr" type="text" name="certificates" value="{{ !empty($meta['certificates']) ? $meta['certificates'] : '' }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="certificates">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                </div>
                            </div>
                        </div>


						<div class="form-group">
                            <label class="control-label control-label-p">{{ trans('main.resume') }}</label>
                            <div class="input-group" style="display: flex">
                                <button id="lfm_attach2" data-input="resume" data-preview="holder" class="btn btn-primary">
                                    Choose
                                </button>
                                <input id="resume" class="form-control" dir="ltr" type="text" name="resume" value="{{ !empty($meta['resume']) ? $meta['resume'] : '' }}">
                                <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal" data-whatever="resume">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
							<label class="control-label" for="inputDefault">{{ trans('main.profil') }}</label>
                            <input  type="text" name="profile" class="form-control" id="profile"   />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-custom pull-left" value="Reply">{{ trans('main.submit') }}</button>
                        </div>
                    </form>
                </div>

			@endif

            </div>
        </div>
        <div class="col-md-6 col-xs-12 tab-con">

                <div class="table-responsive">
                    <table class="table ucp-table" id="ticket-table" border='1'>
                        <thead class="back-blue">
                        <tr>
                            <th width="50%" class="cell-center">{{ trans('main.title') }}</th>
                            <th width="50%" class="text-center">{{ trans('main.Value') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.username') }}</td>
                                <td style='text-align:left' class="text-center">{{ $user['username'] }}</td>
                            </tr>

							<tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.displayName') }}</td>
                                <td style='text-align:left' class="text-center">{{ $user['name'] }}</td>
                            </tr>

							<tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.reg_date') }}</td>
                                <td style='text-align:left' class="text-center">{{ date('d F Y : H:i',strtotime($user['created_at'])) }} </td>
                            </tr>

							<tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.mode') }}</td>
                                <td style='text-align:left' class="text-center">{{ $user['mode'] }} </td>
                            </tr>

							<tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.email') }}</td>
                                <td style='text-align:left' class="text-center">{{ $user['email'] }} </td>
                            </tr>

							<tr>
                                <td style='text-align:left' class="cell-center">{{ trans('admin.address') }}</td>
                                <td style='text-align:left' class="text-center">{{ $user['address'] }} </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

        </div>
    </div>
    </div>
@endsection


@section('script')
    <script src="{!! baseURL() !!}/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script>
        $('#lfm_attach').filemanager('file', {prefix: '{!! baseURL() !!}/user/laravel-filemanager'});

		$('#lfm_attach1').filemanager('file', {prefix: '{!! baseURL() !!}/user/laravel-filemanager'});

		$('#lfm_attach2').filemanager('file', {prefix: '{!! baseURL() !!}/user/laravel-filemanager'});
    </script>





@endsection
