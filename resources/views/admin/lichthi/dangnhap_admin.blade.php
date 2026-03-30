<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    {{-- Link CSS --}}
    @vite(['resources/css/admin/login_admin.css'])
</head>

<body>
<div class="login-container">
    <form method="POST" action="{{ route('admin.verify') }}" class="login-box">
        @csrf
        <h2>ADMIN</h2>
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" placeholder="Nhập tên..." required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>

        <button type="submit" class="btn-login">LOGIN</button>

        {{-- lỗi chung --}}
        @error('error')
            <p class="error">{{ $message }}</p>
        @enderror

        @error('error_dangnhapadmin')
            <p class="error">{{ $message }}</p>
        @enderror

    </form>
</div>
</body>

</html>