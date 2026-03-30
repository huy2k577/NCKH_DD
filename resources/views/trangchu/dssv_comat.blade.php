<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DANH SÁCH SINH VIÊN CÓ MẶT</title>

    @vite(['resources/css/trangchu/dssv_trangchu.css']) 
</head>
<body>

<div class="container">
    <h2 class="center">DANH SÁCH SINH VIÊN CÓ MẶT</h2>

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
            @foreach ($dssv_comat as $key => $sv)
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
                <td>
                    @if($sv->co_mat)
                        <span class="green">✔ Có</span>
                    @else
                        <span class="red">✘ Không</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <div class="row action-bar">
        <a href="{{ route('show.trangchu',$id_ltptctnt)}}" class="btn btn-back">
            ← Quay lại
        </a>
        <a href="{{ route('export.dssv.comat.trangchu',$id_ltptctnt) }}" class="btn btn-export">
            Export
        </a>
    </div>
</div>
</body>
</html>