<!-- resources/views/show_trangchu.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch thi</title>
    @vite(['resources/js/KetQuaDiemDanh.js','resources/css/trangchu/show_trangchu.css'])
</head>
<body>

<div class="container">

    <h1 class="title">CHI TIẾT LỊCH THI</h1>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2">Tên môn</th>
                    <th rowspan="2">Ngày</th>
                    <th rowspan="2">Phòng</th>
                    <th rowspan="2">Giờ</th>
                    <th rowspan="2">Kỳ</th>
                    <th colspan="4">Phân công coi thi</th>
                </tr>
                <tr>
                    <th>CB1</th>
                    <th>CB2</th>
                    <th>CB3</th>
                    <th>CB4</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $ltptctnt->ten_monhoc }}</td>
                    <td>{{ $ltptctnt->ngay_thi }}</td>
                    <td>{{ $ltptctnt->ten_phong }}</td>
                    <td>{{ $ltptctnt->gio_bat_dau }}</td>
                    <td>{{ $ltptctnt->ten_ky }}</td>
                    <td>{{ $ltptctnt->so_gv[0] ?? '-' }}</td>
                    <td>{{ $ltptctnt->so_gv[1] ?? '-' }}</td>
                    <td>{{ $ltptctnt->so_gv[2] ?? '-' }}</td>
                    <td>{{ $ltptctnt->so_gv[3] ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    @if(!$verified)
    <div class="card">
        <form method="POST" action="{{ route('verify.trangchu', $ltptctnt->ltptctnt_id) }}">
            @csrf
            <input class="input" type="password" name="password" placeholder="Nhập mật khẩu">
            <button class="btn">Xác nhận</button>
            @error('error')
                <p  class="error">{{ $message }}</p>
            @enderror
        </form>
    </div>
    @endif

    @if($verified && $ltptctnt->sinhViens)

    <div class="card">
        <h2>Camera</h2>
        <video id="camera" autoplay playsinline></video>
    </div>

    <div class="card">
        <h2>Điểm danh</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>MSSV</th>
                    <th>Họ tên</th>
                    <th>Lớp</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Tình trạng</th>
                    <th>Có mặt</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ltptctnt->sinhViens as $key => $sv)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $sv->ma_sinhvien }}</td>
                    <td>{{ $sv->ho_ten_sinhvien }}</td>
                    <td>{{ $sv->ten_lop }}</td>
                    <td>{{ $sv->email }}</td>
                    <td>{{ $sv->so_dien_thoai }}</td>
                    <td>{{ $sv->ngay_sinh }}</td>
                    <td>{{ $sv->gioi_tinh }}</td>
                    <td>{{ $sv->tinh_trang }}</td>
                    <td>{{ $sv->co_mat }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <br>

    <div class="center">
        <button class="btn_xemkq" onclick="KhungKetQua({{ $ltptctnt->ltptctnt_id }})">Xem kết quả</button>
    </div>

    <div id="manHinhMo" onclick="dongKhung()"></div>

    <div id="khungKetQua">
        <h3 class='text_kqdd'>Kết quả điểm danh</h3>
        <div id="dangTai">Đang tải...</div>
        <div id="noiDungChinh">
            <p class='text_tongSV'>
                <strong>Tổng SV:</strong> 
                <span id="hien_Tong"></span>
            </p>
            <div class="row">
                <p class="green">
                    Có mặt: <span id="hien_CoMat"></span>
                </p>
                <a class="btn btn-view" href="{{route('dssv.comat.trangchu',$ltptctnt->ltptctnt_id)}}">
                    Xem
                </a>
            </div>
            <div class="row">
                <p class="red">
                    Vắng: <span id="hien_Vang"></span>
                </p>
                <a class="btn btn-view-no" href="{{route('dssv.khongcomat.trangchu',$ltptctnt->ltptctnt_id)}}">
                    Xem
                </a>
            </div>
        </div>
        <button class="btn_dongkq" onclick="dongKhung()">Đóng</button>
    </div>

    @endif

</div>

<script>
const video = document.getElementById('camera');
if(video){
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => video.srcObject = stream)
    .catch(err => console.log(err));
}
</script>

</body>
</html>



