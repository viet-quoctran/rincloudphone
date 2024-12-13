@extends('login.layout')

@section('title', 'Sign Up')

@section('content')
    <div class="signup">
        <form>
            <label for="chk" aria-hidden="true">Sign In</label>
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="pswd" placeholder="Password" required="">
            <button>Sign in</button>
        </form>
    </div>
@endsection