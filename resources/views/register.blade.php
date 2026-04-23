<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Register') }}</title>

    @vite('resources/css/app.css')
    
</head>
<body>
    <main class="register-form">
        <div>
            <div class="logo">
                Logo
            </div>
            <form action="{{ route('register') }}" method="post">
                @csrf
                <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" autocomplete="name">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
                <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
                <select id="currencyField" name="currency" data-old-value="{{ old('currency') }}">
                    <option value="" default hidden>Select a currency</option>
                    @foreach (config('currencies') as $currency => $country)
                        <option value="{{ $currency }}">{{ $currency . ' ' . $country }}</option>
                    @endforeach
                </select>
                @error('currency')
                    <p class="error">{{ $message }}</p>
                @enderror
                <input type="password" name="password" placeholder="Password">
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
                <input type="password" name="password_confirmation" placeholder="Confirm Password">
                @error('password_confirmation')
                    <p class="error">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn-default mt-4">Register</button>
            </form>
            <p>Already Registered? <a href="{{ route('login') }}" class="link">Login</a></p>
        </div>
        

    </main>
    
    @vite('resources/js/app.js')
    <script>
        var currencyField = document.getElementById('currencyField');
        currencyField.value = currencyField.dataset.oldValue;
    </script>
</body>
</html>