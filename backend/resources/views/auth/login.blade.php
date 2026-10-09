<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — iStore</title>
    <style>
        * { box-sizing: border-box; }
        body { min-height: 100vh; margin: 0; display: grid; place-items: center; background: #f7f7fb; color: #29283d; font: 15px Arial, sans-serif; }
        .card { width: min(420px, calc(100% - 32px)); padding: 32px; border: 1px solid #ecebf2; border-radius: 16px; background: #fff; box-shadow: 0 12px 45px #2421430d; }
        .brand { margin: 0 0 8px; font-size: 28px; font-weight: 700; letter-spacing: -1px; }
        .brand span { color: #7564e5; }
        .intro { margin: 0 0 26px; color: #858499; font-size: 13px; line-height: 1.5; }
        label { display: block; margin: 16px 0 7px; color: #55546a; font-size: 12px; font-weight: 700; }
        input[type=email], input[type=password] { width: 100%; height: 42px; padding: 0 12px; border: 1px solid #e6e5ee; border-radius: 7px; color: #29283d; font: inherit; }
        .remember { display: flex; align-items: center; gap: 8px; color: #77768a; font-size: 12px; }
        .submit { width: 100%; height: 42px; margin-top: 20px; border: 0; border-radius: 7px; background: #7564e5; color: white; font-size: 13px; font-weight: 700; cursor: pointer; }
        .error { margin: 5px 0 0; color: #c5495b; font-size: 11px; }
        .demo { margin-top: 20px; padding: 12px; border-radius: 7px; background: #f8f7ff; color: #77768a; font-size: 11px; line-height: 1.6; }
        .demo b { color: #4f4d67; }
    </style>
</head>
<body>
    <main class="card">
        <h1 class="brand">iStore<span>.</span></h1>
        <p class="intro">Masuk ke sistem operasional toko dengan akun owner atau karyawan.</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email') <p class="error">{{ $message }}</p> @enderror
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <p class="remember"><input id="remember" name="remember" type="checkbox" value="1"> <label for="remember" style="margin:0">Ingat saya</label></p>
            <button class="submit" type="submit">Masuk ke iStore</button>
        </form>
        <div class="demo"><b>Akun demo lokal</b><br>Owner: owner@istore.demo / password<br>Karyawan: staff@istore.demo / password<br>Ganti kata sandi demo sebelum dipakai di luar presentasi.</div>
    </main>
</body>
</html>
