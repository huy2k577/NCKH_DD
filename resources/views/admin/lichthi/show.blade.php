<h2>CHI TIẾT LỊCH THI</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th rowspan="2">Mã môn học</th>
            <th rowspan="2">Môn học</th>
            <th rowspan="2">Phòng</th>
            <th rowspan="2">Ca</th>
            <th rowspan="2">Ngày thi</th>
            <th rowspan="2">Kỳ thi</th>
            <th rowspan="2">Password</th>
            <th colspan="4" >Phân công</th>
        </tr>
        <tr>
            <th>CBCT01</th>
            <th>CBCT02</th>
            <th>CBCT03</th>
            <th>CBCT04</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td align="center">{{ $ltptctnt->ma_monhoc ?? 'N/A' }}</td>
            <td align="center">{{ $ltptctnt->ten_monhoc ?? 'N/A' }}</td>
            <td align="center">{{ $ltptctnt->ten_phong ?? 'N/A' }}</td>
            <td align="center">{{ $ltptctnt->gio_bat_dau ?? 'N/A' }}</td>
            <td align="center">{{ $ltptctnt->ngay_thi }}</td>
            <td align="center">{{ $ltptctnt->ten_ky }}</td>
            <td align="center">{{ $ltptctnt->password }}</td>
            <td align="center"> {{$ltptctnt->so_gv[0] ?? ' '}}</td>
            <td align="center"> {{$ltptctnt->so_gv[1] ?? ' '}}</td>
            <td align="center"> {{$ltptctnt->so_gv[2] ?? ' '}}</td>
            <td align="center"> {{$ltptctnt->so_gv[3] ?? ' '}}</td>
            
        </tr>
    </tbody>
</table>

<br>

<h3>DANH SÁCH SINH VIÊN</h3>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th align="center">STT</th>
            <th align="center">MSSV</th>
            <th align="center">Họ tên</th>
            <th align="center">Lớp</th>
            <th align="center">Email</th>
            <th align="center">SĐT</th>
            <th align="center">Ngày sinh</th>
            <th align="center">Giới tính</th>
            <th align="center">Tình trạng</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($ltptctnt->sinhViens as $key => $sv)
        <tr>
            <td align="center">{{ $key + 1 }}</td>
            <td align="center">{{ $sv->ma_sinhvien }}</td>
            <td align="center">{{ $sv->ho_ten_sinhvien }}</td>
            <td align="center">{{ $sv->ten_lop }}</td>
            <td align="center">{{ $sv->email }}</td>
            <td align="center">{{ $sv->so_dien_thoai }}</td>
            <td align="center">{{ $sv->ngay_sinh }}</td>
            <td align="center">{{ $sv->gioi_tinh }}</td>
            <td align="center">{{ $sv->tinh_trang }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

