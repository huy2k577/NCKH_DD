<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>

    @vite(['resources/css/trangchu/login_trangchu.css'])
</head>

<body>
<div class="container">
    <form action="{{ route('login.post.trangchu') }}" method="post" class="form-box">
        @csrf

        <h2>PHÒNG THI</h2>

        <div class="input-group">
            <label>Phòng Thi</label>
            <input type="text" name="ten_phong" value="{{ old('ten_phong') }}" placeholder="VD: A101" required>
            @error('ten_phong')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="input-group">
            <label>Ca thi</label>
            <input type="text" name="ten_ca" value="{{ old('ten_ca') }}" placeholder="VD: Ca 1" required>
            @error('ten_ca')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">LOGIN</button>

        @error('No_lichthi')
            <p class="error center">{{ $message }}</p>
        @enderror

    </form>
</div>
</body>

</html>