<!DOCTYPE html>
<head>
<style>
 p {
    font-size: 30px;
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
}
.active{
    color:#ff5722 !important;
}

</style>
</head>
<body style="background: #0D0A19;">

<p>@if($order_status == 'Success') 
    {{-- <a href="{{ $success_url }}" class="active" style="color:#fff;">Redirecting to Biddr.</a> --}}
    Thanks for your purchase Payment successfully Done. <br> Please go back to Homepage <b><a href="https://netbookflix.com/home">Back</a></b>
     @else 
        {{-- <a href="{{ $failure_url }}" class="active" style="color:#fff;">Redirecting to Biddr.</a> --}}
        Your purchase Not successfull. <br> Please go back to Homepage <b><a href="https://netbookflix.com/home">Back</a></b>
        @endif    


</body>
</html>