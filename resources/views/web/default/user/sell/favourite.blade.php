@extends(getTemplate() . '.user.layout.layout')
@section('pages')
    <div class="h-20"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="leftMenu">
                    <ul>
                        <li><a href="{{URL::to('/user/video/buy')}}"><i class="fas fa-book"></i> My Courses</a></li>
                        <li><a href="{{URL::to('/user/favourites')}}"><i class="fas fa-heart"></i> My Favourite List</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <div class="light-box">
                    <div class="h-20"></div>
                    @if($favs->count() == 0)
                        <div class="text-center">
                            <img src="/assets/default/images/empty/dashboardbought.png">
                            <div class="h-20"></div>
                            <span class="empty-first-line">No data found</span>
                            <div class="h-20"></div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table ucp-table" id="buy-table">
                                <thead class="thead-s">
                                <th width="130"></th>
                                <th class="cell-ta">Teacher</th>
                                <th></th>
                                </thead>
                                <tbody>
                                @foreach($favs as $fav)
                                        <tr class="text-center">
                                            <td class="text-center">
                                                <?php
                                                echo getProfileBox($fav->seller_id);
                                                ?>
                                            </td>
                                            <td class="cell-ta">
                                                <?php echo getProfileText($fav->seller_id,false); ?>
                                            </td>
                                            <td>
                                                <a href="{{url('/profile/'.$fav->seller_id)}}"  class="btn btn-primary btn-block">BOOK NOW</a>

                                            </td>

                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="h-25"></div>
            </div>
        </div>
    </div>

@endsection
