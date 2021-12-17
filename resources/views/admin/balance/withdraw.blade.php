@extends('admin.newlayout.layout',['breadcom'=>['Accounting','Withdrawal List']])
@section('title')
    {{ trans('admin.withdrawal_list') }}
@endsection
@section('page')

    <div class="h-10"></div>
    <section class="card">
        <div class="card-header">
            <h5>{!! trans('admin.vendors') !!}</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped mb-none" id="datatable-details">
                <thead>
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Available Balance</th>
                    <th class="text-center">Withdrawal amount</th>
                    <th class="text-center">Withdrawal Method</th>
                    <th class="text-center">Withdrawal Account</th>
                    <th class="text-center">Payout Details</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">{{ trans('admin.th_controls') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($withdrawals as $withdraw)
                    <?php $meta = get_all_user_meta($withdraw->user_id); ?>
                    <tr>
                        <th class="text-center">{{ get_username($withdraw->user_id) }}</th>
                        <th class="text-center number-green">{{ currency(getUserbalance($withdraw->user_id)+$withdraw->price) }}</th>
                        <th class="text-center number-green">{{ currency($withdraw->price) }}</th>
                        <th class="text-center">{{ strtoupper($withdraw->exporter_id) }}</th>
                        <th class="text-center">{{ $withdraw->exporter_details}}</th>
                        <th class="text-center">{{ $withdraw->token}}</th>
                        <th class="text-center">
                            @if($withdraw->status == 'pending')<span class="badge badge-warning ">{{ $withdraw->status}}</span>@endif
                            @if($withdraw->status == 'success')<span class="badge badge-success">{{ $withdraw->status}}</span>@endif
                            @if($withdraw->status == 'cancelled')<span class="badge badge-danger">{{ $withdraw->status}}</span>@endif
                        </th>
                        <th class="text-left">
                            @if($withdraw->status == 'pending')
                            <a href="#confirm-withdraw{{$withdraw->id}}" data-toggle="modal" class="btn btn-danger">Confirm/Cancel Payout</a>
                            <div class="modal fade" id="confirm-withdraw{{$withdraw->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="z-index: 1050">
                                    <div class="modal-content">
                                        <form method="post" action="/admin/balance/confirmWithdraw">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$withdraw->id}}">
                                            <div class="modal-header">
                                                {{  trans('admin.system_alert') }}
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="control-label"> Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="">Please select</option>
                                                        <option value="success">Payout Confirmed</option>
                                                        <option value="cancelled">Cancelled</option>

                                                    </select>
                                                </div>
                                                <p>Please provide the Transaction ID/Payout Details/Cancelling reason</p>
                                                <div class="form-group">
                                                    <label class="control-label">Details</label>
                                                    <textarea class="form-control" name="details" placeholder="Your description..." required></textarea>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger btn-ok" type="submit">Save</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">{{  trans('admin.cancel') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                                @endif
                        </th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

@endsection
