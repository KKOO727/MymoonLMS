@extends(getTemplate().'.view.layout.layout')
@section('title')
    {{ !empty($setting['site']['site_title']) ? $setting['site']['site_title'] : '' }}
@endsection
@section('page')
    <style>
        #maincontent {
            background: #fff;
        }
        .contactForm {
            background: url({{url('/assets/default/images/pages/formbg.png')}}) no-repeat top center;
            background-size: cover;
            padding: 60px 60px 230px 60px;
        }
        .contactInfo{
            font-size:16px;
        }
        label{
            font-weight:bold;
        }
    </style>
    <div class="h-30"></div>
    <div class="h-30"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="contact">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="contactForm">
                                <form action="{{url('/contact')}}" method="POST">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control"  placeholder="Name" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"  placeholder="Email Address" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control"  placeholder="Phone" required="">
                                    </div>
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea name="message" class="form-control" style="height:160px" required></textarea>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="submit">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contactInfo" style="padding-top:70px">
                            <h1>Contact Us</h1>
                            <br>
                            <p>Fill out the fields on the left and send us a message.<br>Look forward to hearing from you!</p>
                            <p style="color:#A04190;font-weight:bold">Email: hello@mymoononline.com<br>
                                WhatsApp: +44 7727 218845</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-20"></div>
    </div>
    <div class="h-30"></div>
@endsection
@section('script')

@endsection
