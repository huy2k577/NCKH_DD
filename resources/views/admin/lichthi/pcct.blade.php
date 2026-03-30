@vite(['resources/css/admin/pcct_admin.css'])
<h2 class="title">📋 DANH SÁCH PHÂN CÔNG COI THI</h2>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2">STT</th>
                <th rowspan="2">Mã MH</th>
                <th rowspan="2">Tên môn</th>
                <th rowspan="2">Phòng</th>
                <th rowspan="2">Lớp</th>
                <th rowspan="2">Ca Thi</th>
                <th rowspan="2">Ngày Thi</th>
                <th rowspan="2">Kỳ Thi</th>
                <th rowspan="2">Số lượng</th>
                <th colspan="4">Coi thi</th>
            </tr>
            <tr>
                <th>CB1</th>
                <th>CB2</th>
                <th>CB3</th>
                <th>CB4</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($lichThis as $key => $lt)
            <tr>
                <td>{{ $lichThis->firstItem() + $key }}</td>
                <td>{{ $lt->ma_monhoc ?? '-' }}</td>
                <td>{{ $lt->ten_monhoc ?? '-' }}</td>
                <td>{{ $lt->ten_phong ?? '-' }}</td>
                <td>{{ $lt->ten_lop ?? '-' }}</td>
                <td>{{ $lt->gio_bat_dau ?? '-' }}</td>
                <td>{{ $lt->ngay_thi }}</td>
                <td>{{ $lt->ten_ky }}</td>
                <td>{{ $lt->so_luong_sinhvien }}</td>

                <td>{{ $lt->so_gv[0] ?? '' }}</td>
                <td>{{ $lt->so_gv[1] ?? '' }}</td>
                <td>{{ $lt->so_gv[2] ?? '' }}</td>
                <td>{{ $lt->so_gv[3] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="pagination-container">
    {{ $lichThis->onEachSide(1)->links() }}
</div>

<div class="export_pcct">
    <a href="{{route('admin.export_pcct')}}"  class="btn-export_pcct">Export</a>
</div>

{{-- Nút quay lại --}}
<div class="back">
    <a href="{{ route('admin.lichthi.index') }}" class="btn-back">⬅ Quay lại</a>
</div>