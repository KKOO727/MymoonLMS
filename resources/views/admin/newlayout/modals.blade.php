<div id="ImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="z-index: 1050">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    <img src="#" class="img-responsive" style="max-width: 100%"/>
                </p>
            </div>
        </div>

    </div>
</div>

<div id="VideoModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="z-index: 1050">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
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

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="z-index: 1050">
        <div class="modal-content">
            <div class="modal-header">
                {{  trans('admin.system_alert') }}
            </div>
            <div class="modal-body">
                {{  trans('admin.are_you_sure') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('main.cancel') }}</button>
                <a class="btn btn-danger btn-ok">{{ trans('main.yes_sure') }}</a>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="uploader-modal" role="dialog">
    <div class="modal-dialog" style="z-index: 1050;max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-body">
                <iframe src="/laravel-filemanager" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

