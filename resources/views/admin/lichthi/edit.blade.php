    <form method="POST" action="{{ route('admin.lichthi.update', $ltptctnt->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Mã môn học</label>
            <input type="text" name="ma_monhoc"  value="{{old('ma_monhoc', $ltptctnt->ma_monhoc)}}" >
            @error('ma_monhoc')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
        <br>

        <div> 
            <label>Tên phòng thi</label>
            <input type="text" name="ten_phong" value="{{old('ten_phong',$ltptctnt->ten_phong)}}" >
            @error('ten_phong')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
        <br>

        <div>
            <label>Tên ca thi</label>
            <input type="text" name="ten_ca" value="{{old('ten_ca',$ltptctnt->ten_ca)}}" >
            @error('ten_ca')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
        <br>

        <div>
            <label>Ngày thi</label>
            <input type="date"  name="ngay_thi" value="{{old('ngay_thi',$ltptctnt->ngay_thi)}}" >
            @error('ngay_thi')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
        <br>

       
        <div>
            <label>Phân công giảng viên (tối đa 4 gv)</label><br>
            @for ($i = 0; $i < 4; $i++)
                <input type="text" name="ma_giangvien[]" value="{{ old("ma_giangvien.$i", $ltptctnt->so_gv[$i] ?? '')}}" placeholder="cán bộ coi thi {{ $i+1 }}">
                <br>
                @error("ma_giangvien.$i")
                    <p style="color:red">{{ $message }}</p>
                @enderror
            @endfor
            @error('ma_giangvien')
                <p style="color:red">{{ $message }}</p>
            @enderror        </div>
        <br>

        <div>
            <label>Kỳ loại thi</label>
            <select name="ky_loai_thi">
               <option value="Giữa kỳ"
                    {{ old('ky_loai_thi', $ltptctnt->ten_ky) == 'Giữa kỳ' ? 'selected' : '' }}>
                    Giữa kỳ
                </option>

                <option value="Cuối kỳ"
                    {{ old('ky_loai_thi', $ltptctnt->ten_ky) == 'Cuối kỳ' ? 'selected' : '' }}>
                    Cuối kỳ
                </option>

                <option value="Khác"
                    {{ old('ky_loai_thi', $ltptctnt->ten_ky) == 'Khác' ? 'selected' : '' }}>
                    Khác
                </option>
            </select>
        </div>
        <br>

        <div>
            <label>Password</label>
            <input type="text" name="password" value="{{old('password')}}"  >
            @error('password')
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
      
        <br>
        

        <button type="sumbit">Cập nhập</button>
        
        @if(session('success'))
            <div class="alert alert-success">
                <p style="color: aqua">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @error('database_error_update')
            <div style="color:red">
                {{ $message }}
            </div>
        @enderror
    </form>

    <div>
        <a href="{{ route('admin.lichthi.index')}}"> Quay lại</a>
    </div>
