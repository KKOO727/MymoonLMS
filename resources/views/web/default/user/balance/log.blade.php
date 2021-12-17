<h3 class="paz no-margin violet"><i class="fas fa-list"></i> {{ trans('Balance log') }}</h3>
<hr>

@if(count($lists) == 0)
    <div class="text-center">
        <img src="/assets/default/images/empty/financialdocs.png">
        <div class="h-20"></div>
        <span class="empty-first-line">{{ trans('main.no_financial_doc') }}</span>
        <div class="h-20"></div>
    </div>
@else
    <div class="col-xs-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table ucp-table" id="log-table">
                    <thead class="back-orange">
                    <th class="text-center">#</th>
                    <th class="cell-ta">{{ trans('main.title') }}</th>
                    <th class="cell-ta">{{ trans('main.description') }}</th>
                    <th class="text-center">{{ trans('main.amount') }}</th>
                    <th class="text-center">{{ trans('main.type') }}</th>
                    <th class="text-center">{{ trans('Status') }}</th>

                    </thead>
                    <tbody>
                    <?php $i = 1;?>
                    @foreach($lists as $item)

                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="cell-ta">{{ $item->title }}</td>
                            <td class="cell-ta">{{ $item->description }}<br>{{ date('d F Y | H:i',$item->created_at) }}</td>
                            <td class="text-center">
                                <b @if($item->type =='deposit' || $item->type =='sell') class="green-s" @else class="red-s" @endif>{{currency($item->price)}}</b></td>
                            <td class="text-center">
                                {{strtoupper($item->type)}}
                            </td>
                            <td class="text-center">
                                @if($item->status == 'pending')<span class="badge badge-warning ">{{ $item->status}}</span>@endif
                                @if($item->status == 'success')<span class="badge badge-success">{{ $item->status}}</span>@endif
                                @if($item->status == 'cancelled')<span class="badge badge-danger">{{ $item->status}}</span>@endif

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
