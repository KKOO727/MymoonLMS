

<div class="modal fade" id="newCourseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                </button>
                <h4 class="modal-title">New Course</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="/user/content/new/store" class="form-horizontal">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-md-2 tab-con"
                               for="inputDefault">{{ trans('main.course_type') }}</label>
                        <div class="col-md-10 tab-con">
                            <select name="type" class="form-control font-s">
                                <option value="2">Group Course</option>
                                <option value="3">Video Course</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12 tab-con">
                            <input type="submit" class="btn btn-primary pull-right" value="Next">
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<div id="ImageModal" class="modal fade modal-dialog-s" role="dialog">
    <div class="modal-dialog modal-dialog-s">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close mart-s-10" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    <img src="#" class="img-responsive" style="max-width: 100%"/>
                </p>
            </div>
        </div>

    </div>
</div>

<div id="VideoModal" class="modal fade modal-dialog-s" role="dialog">
    <div class="modal-dialog modal-dialog-s">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close mart-s-10" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    <video width="570" controls>
                        <source src="#" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                </p>
            </div>
        </div>

    </div>
</div>

<div class="modal fade modal-dialog-s" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-s">
        <div class="modal-content">
            <div class="modal-header">
                {{ trans('main.alert') }}
            </div>
            <div class="modal-body">
                {{ trans('main.are_you_sure') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ trans('main.yes_sure') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="topUpModal">
    <div class="modal-dialog">
        <div class="modal-content modal-md">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">{{ trans('Top Up your MyMoon Account') }}</h4>
            </div>
            <form method="post" action="/user/createCheckoutSession">
                {{ csrf_field() }}
                <input type="hidden" name="cur" value="{{$currency}}">
                <div class="modal-body">

                    <table class="table table-borderless profileTable">
                        <tbody><tr>
                            <th> Amount  ({{$currency}}) {{currencySign($currency)}}</th>
                            <td><input type="text" placeholder="" name="price" class="form-control text-center" required></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td class="text-left">
                                <!--<input type="radio" name="processor" value="card" class="form-radio"> VISA / AMEX / MASTERCARD<br>-->
                                <input type="radio" name="processor" value="paypal" class="form-radio"> Paypal
                            </td>
                        </tr>
                        </tbody></table>

                    <div class="h-20"></div>
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <input type="submit" value="Proceed" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="h-20"></div>
                </div>
            </form>
        </div>
    </div>
</div>
