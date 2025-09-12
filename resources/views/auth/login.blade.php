@extends('layouts.app')

@section('content')
<div class="login-container">
    <h2>Login</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>
<style>
.login-container {
    max-width: 400px;
    margin: 60px auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
}
.login-container h2 {
    text-align: center;
    margin-bottom: 24px;
}
.form-group {
    margin-bottom: 18px;
}
.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #e0e0e0;
}
.btn-primary {
    width: 100%;
    background: #4CAF50;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}
.btn-primary:hover {
    background: #45a049;
}
.alert-danger {
    color: #fff;
    background: #e74c3c;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 16px;
    text-align: center;
}
</style>
@endsection
