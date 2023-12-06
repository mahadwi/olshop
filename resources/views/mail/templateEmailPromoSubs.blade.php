<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email</title>
</head>
<body style="padding: 15px;background-color: #F5F8FA">
    <div style="
        background-color: #ffffff;
        max-width: 600px;
        min-width: 400px;
        margin: 30px auto 30px auto;
        padding: 15px;
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        webkit-box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        font-family:Whitney,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;
        font-weight:500;
        font-size:14px;
        color:#4f545c;
        letter-spacing:0.27px
    ">
    {{-- @dd($data['message']); --}}
        {!! $data['message'] !!}
    </div>

</body>
</html>
