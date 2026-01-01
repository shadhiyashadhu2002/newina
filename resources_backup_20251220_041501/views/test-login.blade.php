<!DOCTYPE html>
<html>
<head>
    <title>Staff Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 800px; margin: 0 auto; }
        .credentials { background: #f5f5f5; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .test-form { background: #e8f4f8; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .result { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        input[type="email"], input[type="password"] { width: 200px; padding: 8px; margin: 5px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Staff Login Test Page</h1>
        
        <div class="test-form">
            <h3>Test Staff Login</h3>
            <form method="POST" action="/test-login">
                @csrf
                <div>
                    <label>Email:</label><br>
                    <input type="email" name="email" value="sana@service.com" required>
                </div>
                <div>
                    <label>Password:</label><br>
                    <input type="password" name="password" value="1010" required>
                </div>
                <div>
                    <button type="submit">Test Login</button>
                </div>
            </form>
            
            @if(session('login_result'))
                <div class="result {{ session('login_success') ? 'success' : 'error' }}">
                    {{ session('login_result') }}
                </div>
            @endif
        </div>

        <h3>Available Staff Credentials:</h3>
        
        <div class="credentials">
            <strong>Sana Service:</strong> sana@service.com / 1010
        </div>
        
        <div class="credentials">
            <strong>Asna:</strong> asnas@sales.com / 1011
        </div>
        
        <div class="credentials">
            <strong>Safa:</strong> safa@sales.com / 1012
        </div>
        
        <div class="credentials">
            <strong>Jumana:</strong> jumana@service.com / 1013
        </div>
        
        <div class="credentials">
            <strong>Saniya:</strong> saniya@service.com / 1014
        </div>
        
        <div class="credentials">
            <strong>Safna:</strong> safna@service.com / 1015
        </div>
        
        <div class="credentials">
            <strong>Shamna:</strong> shamna@service.com / 1016
        </div>
        
        <div class="credentials">
            <strong>Priya:</strong> priya@service.com / 1017
        </div>
        
        <div class="credentials">
            <strong>Jasna:</strong> jasna@service.com / 1019
        </div>
        
        <div class="credentials">
            <strong>Jasmin:</strong> jasmin@service.com / 1020
        </div>
        
        <div class="credentials">
            <strong>Sajna:</strong> sajna@service.com / 1024
        </div>
        
        <div class="credentials">
            <strong>Midhuna:</strong> midhuna@service.com / 1025
        </div>
        
        <div class="credentials">
            <strong>Hima:</strong> hima@service.com / 1028
        </div>

        <h3>Regular Login Pages:</h3>
        <p><a href="/login">Regular User Login</a></p>
        <p><a href="/admin/login">Admin Login</a></p>
        
    </div>
</body>
</html>