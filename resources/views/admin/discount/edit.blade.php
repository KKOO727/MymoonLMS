@extends('admin.newlayout.layout',['breadcom'=>['Discounts','Edit']])
@section('title')
    {{ trans('admin.th_edit') }} {{ trans('admin.discounts') }}
@endsection
@section('page')

    <section class="panel">
        <div class="panel-body">
            <form action="/admin/discount/store" class="form-horizontal form-bordered" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id }}">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_title') }}</label>
                    <div class="col-md-8">
                        <input type="text" name="title" value="{{ $item->title }}" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.type') }}</label>
                    <div class="col-md-8">
                        <select name="type" class="form-control populate" id="type">
                            <option value="gift" @if($item->type == "gift") selected @endif>{{ trans('admin.giftcard') }}</option>
                            <option value="off" @if($item->type == "off") selected @endif>{{ trans('admin.discount_card') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.amount') }}</label>
                    <div class="col-md-8">
                        <input type="number" name="off" value="{{ $item->off ?? '0' }}" class="form-control text-center" placeholder="Percent for Discount cards | Fixed amount for Giftcards" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.gift_code') }}</label>
                    <div class="col-md-8">
                        <input type="text" name="code" value="{{ $item->code }}" class="form-control text-center" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.expire_date') }}</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="date" id="block_date" value="@if(isset($item->expire_at)) {{date('Y/m/d',$item->expire_at)}} @endif" class="form-control text-center" id="inputDefault">
                            <input type="hidden" name="expire_at" id="expire_at" value="@if(isset($item->expire_at)) {{date('d-m-Y',$item->expire_at)}} @endif">
                            <span class="input-group-append bdatebtn" id="bdatebtn">
                                <span class="input-group-text"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault">{{ trans('admin.th_status') }}</label>
                    <div class="col-md-8">
                        <select name="mode" class="form-control populate" id="type">
                            <option value="publish">{{ trans('admin.active') }}</option>
                            <option value="draft">{{ trans('admin.disabled') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label" for="inputDefault"></label>
                    <div class="col-md-8">
                        <button class="btn btn-primary" type="submit">{{ trans('admin.save_changes') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection


