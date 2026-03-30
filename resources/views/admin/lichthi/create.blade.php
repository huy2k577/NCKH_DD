<form action="{{ route('admin.lichthi.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div>
        <label>Chọn file Excel</label>
        <input type="file" name="file" required>
    </div>

    <button type="submit">Import</button>
 
</form>
<div>
    <a href="{{ route('admin.lichthi.index')}}"> Quay lại</a>
</div>
<div>
    @if(session('success'))
        <div style="color: green">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('errors'))
        <div style="color: red">
            @foreach(session('errors') as $error)
                <p>Dòng {{ $error['row_number'] }}:</p>
                <ul>
                    @foreach($error['errors'] as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            @endforeach
        </div>
    @endif
</div>