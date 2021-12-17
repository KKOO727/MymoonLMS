<h3 class="paz no-margin violet"><i class="fas fa-user-graduate"></i> {{ trans('Profile Information') }}</h3>
<hr>
<form method="post" class="form-horizontal" action="/user/profile/store">
    {{ csrf_field() }}
    <table class="table table-borderless profileTable">
        <tr>
            <th>
            {{ trans('Full Name') }}</td>
            <td><input type="text" name="name" value="{{ @$user->name }}" class="form-control"></td>
        </tr>
        <tr>
            <th>
            {{ trans('Passport Name') }}</td>
            <td><input type="text" name="meta_passport_name" value="{{ !empty($meta['meta_passport_name']) ? $meta['meta_passport_name'] : '' }}" class="form-control"></td>
        </tr>
        <tr>
            <th>{{ trans('Username') }}</th>
            <td><input type="text" value="{{ @$user->username }}" class="form-control disabled" disabled></td>
        </tr>
        <tr>
            <th>{{ trans('Email') }}</th>
            <td><input type="text" value="{{ @$user->email }}" class="form-control disabled" disabled></td>
        </tr>
        @if($user->type =='Teacher')
        <tr>
            <th>{{ trans('Short Title') }}</th>
            <td>
                <input type="text" name="meta_short_title" class="form-control" value="{{ !empty($meta['meta_short_title']) ? $meta['meta_short_title'] : '' }}">
            </td>
        </tr>


        <tr>
            <th>{{ trans('main.short_biography') }}</th>
            <td><textarea name="meta_short_biography" rows="5" class="form-control res-vertical">{{ !empty($meta['meta_short_biography']) ? $meta['meta_short_biography'] : '' }}</textarea>
            </td>
        </tr>
        <tr>
            <th>{{ trans('Can Speak') }}</th>
            <td><?php
                $speaking = getSpeakingList('all');
                if (isset($meta['meta_speaking'])) {
                    $meta['meta_speaking'] = explode(',', $meta['meta_speaking']);
                } else {
                    $meta['meta_speaking'] = array();
                }
                ?>
                <select name="meta_speaking[]" class="form-control font-s select2" multiple>
                    @foreach($speaking as $spk)
                        <option value="{{$spk}}" @if(in_array($spk,$meta['meta_speaking'])){{'selected'}}@endif>{{$spk}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <th>{{ trans('Student type') }}</th>
            <td><?php
                $student_type = array('Adults', 'Kids');
                if (isset($meta['meta_student_type'])) {
                    $meta['meta_student_type'] = explode(',', $meta['meta_student_type']);
                } else {
                    $meta['meta_student_type'] = array();
                }
                ?>
                <select name="meta_student_type[]" class="form-control font-s select2" multiple>
                    @foreach($student_type as $spk)
                        <option
                            value="{{$spk}}" @if(in_array($spk,$meta['meta_student_type'])){{'selected'}}@endif>{{$spk}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
            <tr>
                <th>{{ trans('Paypal') }}</th>
                <td><input type="text" name="meta_paypal" value="{{ !empty($meta['meta_paypal']) ? $meta['meta_paypal'] : '' }}"
                           class="form-control"></td>
            </tr>
            <tr>
                <th>{{ trans('Intro video link') }}</th>
                <td><input type="text" value="{{ @$meta['meta_intro_video'] }}" name="meta_intro_video" class="form-control"></td>
            </tr>
        @endif
        <tr>
            <th>{{ trans('main.avatar') }}</th>
            <td>
                <div class="input-group" style="display: flex">
                    <button id="lfm_avatar" data-input="avatar" data-preview="holder" class="btn btn-dark">Choose
                    </button>
                    <input id="avatar" class="form-control" dir="ltr" type="text" name="meta_avatar"
                           value="{{ !empty($meta['meta_avatar']) ? $meta['meta_avatar'] : '' }}">
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#ImageModal"
                         data-whatever="meta_avatar">
                        <span class="input-group-text"> <i class="fa fa-eye" aria-hidden="true"></i> </span>
                    </div>
                </div>
            </td>
        </tr>

        <tr>
            <th></th>
            <td class="text-center"><input type="submit" value="Save" class="btn btn-lg btn-primary"></td>
        </tr>
    </table>
</form>
