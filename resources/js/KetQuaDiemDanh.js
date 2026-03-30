window.KhungKetQua = function(id) {
    document.getElementById('manHinhMo').style.display = 'block';
    document.getElementById('khungKetQua').style.display = 'block';
    document.getElementById('dangTai').style.display = 'block';
    document.getElementById('noiDungChinh').style.display = 'none';

    fetch(`/lichthi/diemdanhketqua/${id}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('hien_Tong').innerText = data.TongSoluong_SinhVien;
            document.getElementById('hien_CoMat').innerText = data.SoLuong_SinhVien_CoMat;
            document.getElementById('hien_Vang').innerText = data.SoLuong_SinhVien_KhongCoMat;
            document.getElementById('dangTai').style.display = 'none';
            document.getElementById('noiDungChinh').style.display = 'block';
        })
        .catch(err => {
            document.getElementById('dangTai').innerText = "Lỗi kết nối Server!";
            console.error(err);
        });
};

window.dongKhung = function() {
    document.getElementById('manHinhMo').style.display = 'none';
    document.getElementById('khungKetQua').style.display = 'none';
};