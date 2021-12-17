<h3 class="paz no-margin violet"><i class="fas fa-user"></i> {{ trans('Personal Details') }}</h3>
<hr>
<form method="post" class="form-horizontal" action="/user/profile/meta/store">
    {{ csrf_field() }}
    <table class="table table-borderless profileTable">
        <tr>
            <th>{{ trans('main.dob') }}</th>
            <td><input type="text" name="meta_dob" value="{{ !empty($meta['meta_dob']) ? $meta['meta_dob'] : '' }}"
                       class="form-control datepicker"></td>
        </tr>
        <tr>
            <th>{{ trans('Phone') }}</th>
            <td><input type="text" name="meta_phone" value="{{ !empty($meta['meta_phone']) ? $meta['meta_phone'] : '' }}"
                       class="form-control"></td>
        </tr>
        <tr>
            <th>
            {{ trans('Gender') }}</td>
            <td>
                <?php
                $genders = array('Male', 'Female');
                if (!isset($meta['meta_gender']))
                    $meta['meta_gender'] = 'Male';
                ?>
                <select name="meta_gender" class="form-control font-s select2">
                    @foreach($genders as $spk)
                        <option
                            value="{{$spk}}" @if($spk == $meta['meta_gender']){{'selected'}}@endif>{{$spk}}</option>
                    @endforeach
                </select>
            </td>
        </tr>

        <tr>
            <th>{{ trans('From Country') }}</th>
            <td><?php
                $countrys = getCountryList(true);
                if (!isset($meta['meta_country'])) {
                    $meta['meta_country'] = '';
                }
                ?>
                <select name="meta_country[]" class="form-control font-s select2">
                    @foreach($countrys as $country)
                        <option value="{{$country->country_code}}" @if( $country->country_code == $meta['meta_country']){{'selected'}}@endif>{{$country->country_name}}</option>
                    @endforeach
                </select></td>
        </tr>
        <tr>
            <th>{{ trans('Living in') }}</th>
            <td><?php
                $countrys = getCountryList(true);
                if (!isset($meta['meta_living_in'])) {
                    $meta['meta_living_in'] = '';
                }
                ?>
                <select name="meta_living_in" class="form-control font-s select2">
                    @foreach($countrys as $country)
                        <option value="{{$country->country_code}}" @if( $country->country_code == $meta['meta_living_in']){{'selected'}}@endif>{{$country->country_name}}</option>
                    @endforeach
                </select></td>
        </tr>

        <tr>
            <th>{{ trans('main.address') }}</th>
            <td><textarea name="meta_address" rows="4"
                          class="form-control">{{ !empty($meta['meta_address']) ? $meta['meta_address'] : '' }}</textarea>
            </td>
        </tr>
        @if($user->type == 'Teacher')
        <tr>
            <th>{{ trans('Passport') }}</th>
            <td>
                <div class="input-group" style="display: flex">
                    <button id="lfm_avatar_pass" data-input="passport" data-preview="holder" class="btn btn-dark">Choose
                    </button>
                    <input id="passport" class="form-control" dir="ltr" type="text" name="meta_passport_file"
                           value="{{ !empty($meta['meta_passport_file']) ? $meta['meta_passport_file'] : '' }}">
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#passport"
                         data-whatever="meta_passport_file">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>{{ trans('Resume') }}</th>
            <td>
                <div class="input-group" style="display: flex">
                    <button id="lfm_avatar_res" data-input="resume" data-preview="holder" class="btn btn-dark">Choose
                    </button>
                    <input id="resume" class="form-control" dir="ltr" type="text" name="meta_resume_file"
                           value="{{ !empty($meta['meta_resume_file']) ? $meta['meta_resume_file'] : '' }}">
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#resume"
                         data-whatever="meta_resume_file">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th>{{ trans('Certificate') }}</th>
            <td>
                <div class="input-group" style="display: flex">
                    <button id="lfm_avatar_cert" data-input="certificate" data-preview="holder" class="btn btn-dark">Choose
                    </button>
                    <input id="certificate" class="form-control" dir="ltr" type="text" name="meta_certificates_file"
                           value="{{ !empty($meta['meta_certificates_file']) ? $meta['meta_certificates_file'] : '' }}">
                    <div class="input-group-prepend view-selected cu-p" data-toggle="modal" data-target="#certificate"
                         data-whatever="meta_certificates_file">
                    </div>
                </div>
            </td>
        </tr>
        @endif
        <tr>
            <th></th>
            <td class="text-center"><input type="submit" value="Save" class="btn btn-lg btn-primary"></td>
        </tr>
    </table>
</form>
