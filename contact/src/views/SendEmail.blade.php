@extends('layouts.style')

@section('content')
    <h1 style="color:#00d18e;margin:40px 0 40px 0;">Please Activate Your Account</h1>
    <div style="width:700px;margin:0px auto;border:1px solid #00d18e;text-align:center;padding:100px 0 100px 0;overflow:hidden;">
        <button  class="button button--secondary" style="background:#00d18e;overflow:hidden;border-radius:10px;">
            <a href="http://website.loc/emailActive/{{ $token }}" style="width:500px;font-size:20px;padding:10px;display:block;background:#00d18e;letter-spacing: 0.5em;color:#fff;text-decoration:none;">
                Activate
            </a>
        </button>
    </div>

<footer>
    <div style="width:1175px;margin:0 auto;">
        <p style="color:#00d18e;float:left;">
            Thank you for working with us.
        </p>
        <p style="color:#00d18e;float:right;">
          WebSite-Hotel
        </p>
    </div>
</footer>
  

@endsection
