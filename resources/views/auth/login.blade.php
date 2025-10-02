@extends('layouts.app')

@section('content')
<div class="login-container">
    <h2>Login</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
    </form>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        // Disable button to prevent double submission
        const btn = document.getElementById('loginBtn');
        btn.disabled = true;
        btn.textContent = 'Logging in...';
        
        // Re-enable after 5 seconds in case of error
        setTimeout(function() {
            btn.disabled = false;
            btn.textContent = 'Login';
        }, 5000);
    });
    </script>

    @if(config('app.debug'))
    <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; font-size: 12px;">
        <strong>Debug Info:</strong><br>
        Form Action: {{ route('login') }}<br>
        CSRF Token: {{ csrf_token() }}<br>
        Current URL: {{ request()->url() }}<br>
        Session ID: {{ session()->getId() }}
    </div>
    @endif
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
