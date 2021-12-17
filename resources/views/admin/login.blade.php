<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login &mdash; MyMoon Online</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="/assets/admin//modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin//modules/fontawesome/css/all.min.css">
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/assets/admin//modules/bootstrap-social/bootstrap-social.css">
    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/admin//css/style.css">
    <link rel="stylesheet" href="/assets/admin//css/components.css">

</head>

<body>
<div id="app">
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

                    @if ($errors->any())
                        <div class="alert alert-warning">
                            <ul>
                                {!! implode('', $errors->all('<li style="color: red">:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    <div class="card card-primary">
                        <div class="card-header"><h4>Login</h4></div>

                        <div class="card-body">
                            <form method="POST" action="/admin/login">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="email">Username</label>
                                    <input id="email" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                                    <div class="invalid-feedback">
                                        Please fill in your email
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="d-block">
                                        <label for="password" class="control-label">Password</label>
                                    </div>
                                    <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                    <div class="invalid-feedback">
                                        please fill in your password
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>

                            @if (!empty($msg))
                                <div class="alert alert-warning">
                                    <p>{{ $msg }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- General JS Scripts -->
<script src="/assets/admin//modules/jquery.min.js"></script>
<script src="/assets/admin//modules/popper.js"></script>
<script src="/assets/admin//modules/tooltip.js"></script>
<script src="/assets/admin//modules/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/admin//modules/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/admin//modules/moment.min.js"></script>
<script src="/assets/admin//js/stisla.js"></script>

<!-- JS Libraies -->

<!-- Page Specific JS File -->

<!-- Template JS File -->
<script src="/assets/admin//js/scripts.js"></script>
<script src="/assets/admin//js/custom.js"></script>
</body>
</html>
