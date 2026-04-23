<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Login') }}</title>

    @vite('resources/css/app.css')
    
</head>
<body>
    <main class="login-form">
        <div>
            <div class="logo">
                Logo @auth Hlo @endif
            </div>
            <form action="{{ route('login') }}" method="post">
                @csrf
                <input type="text" name="email" placeholder="Email" @error ('email') class="invalid-input" @enderror value="{{ old('email') }}">
                @error ('email')
                    <p class="error">{{ $message }}</p>
                @enderror
                <input type="password" name="password" placeholder="Password" @error ('password') class="invalid-input" @enderror>
                @error ('password')
                    <p class="error">{{ $message }}</p>
                @enderror
                @if (session('wrongCredentialsError'))
                    <p class="error">{{ session('wrongCredentialsError') }}</p>
                @endif
                <div class="w-87.5 px-4 mb-4 text-start">
                    <label for="remember"><input type="checkbox" name="remember" id="remember" value="true" class="me-2">Remember Me</label>
                </div>
                <button type="submit" class="btn-default">Login</button>
            </form>
            <p>Don't have an account? <a href="{{ route('register') }}" class="link">Register</a></p>
        </div>
        
    </main>
    <script>
        document.addEventListener('click', e => {
            if (e.target.classList.contains('invalid-input')) {
                e.target.classList.remove('invalid-input')
            }
        })
    </script>
</body>
</html>