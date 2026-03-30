<h2 class="title">📋 DANH SÁCH LỊCH THI</h2>
@vite(['resources/css/admin/index_admin.css'])
<div class="top-actions">
    <a class="btn btn-add" href="{{ route('admin.lichthi.create')}}">➕ Thêm lịch thi</a>
    <a class="btn btn-secondary" href="{{ route('admin.lichthi.pcct')}}">📄 Danh sách PCCT</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th rowspan="2">STT</th>
            <th rowspan="2">Mã MH</th>
            <th rowspan="2">Tên môn</th>
            <th rowspan="2">Phòng</th>
            <th rowspan="2">Ca</th>
            <th rowspan="2">Ngày</th>
            <th rowspan="2">Kỳ</th>
            <th rowspan="2">SV</th>
            <th colspan="4">Coi thi</th>
            <th rowspan="2">Thao tác</th>
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
            <td>{{ $lt->ds_phong ?? '-' }}</td>
            <td>{{ $lt->gio_bat_dau ?? '-' }}</td>
            <td>{{ $lt->ngay_thi }}</td>
            <td>{{ $lt->ten_ky }}</td>
            <td>{{ $lt->tong_sv }}</td>

            <td>{{ $lt->so_gv[0] ?? '' }}</td>
            <td>{{ $lt->so_gv[1] ?? '' }}</td>
            <td>{{ $lt->so_gv[2] ?? '' }}</td>
            <td>{{ $lt->so_gv[3] ?? '' }}</td>

            <td class="actions">
                <a class="btn btn-view" href="{{ route('admin.lichthi.show', $lt->ltptctnt_id) }}">Xem</a>
                <a class="btn btn-edit" href="{{ route('admin.lichthi.edit', $lt->ltptctnt_id) }}">Sửa</a>

                <form method="POST" action="{{ route('admin.lichthi.destroy', $lt->ltptctnt_id) }}"
                    onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-delete">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Pagination --}}
<div class="pagination-container">
    {{ $lichThis->onEachSide(1)->links() }}
</div>

{{-- Logout --}}
<div class="logout">
    <form method="GET" action="{{ route('admin.logout') }}"
        onsubmit="return confirm('Bạn có chắc muốn đăng xuất?')">
        @csrf
        <button class="btn btn-delete">Đăng xuất</button>
    </form>
</div>

{{-- Flash message --}}
@if(session('success_delete'))
    <p class="msg success">{{ session('success_delete') }}</p>
@endif

@if(session('error_delete'))
    <p class="msg error">{{ session('error_delete') }}</p>
@endif

@if(session('success_update'))
    <p class="msg success">{{ session('success_update') }}</p>
@endif