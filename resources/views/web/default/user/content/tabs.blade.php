<div class="col-md-3 col-xs-12 col-sm-4 tab-con right-side">
    <ul>
        <li class="active" cstep="1"><a href="javascript:void(0);"><span
                    class="upicon mdi mdi-library-video"></span><span>{{ trans('main.general') }}</span></a>
        </li>
        <li cstep="2"><a href="javascript:void(0);"><span
                    class="upicon mdi mdi-account"></span><span>Configuration</span></a>
        </li>
        <li cstep="3"><a href="javascript:void(0);"><span
                    class="upicon mdi mdi-folder-image"></span><span>{{ trans('main.view') }}</span></a>
        </li>
        @if(isset($item) && $item->type == '3')
            <li cstep="4"><a href="javascript:void(0);"><span
                        class="upicon mdi mdi-movie-open"></span><span>Lessons</span></a>
            </li>
        @endif
    </ul>
</div>
