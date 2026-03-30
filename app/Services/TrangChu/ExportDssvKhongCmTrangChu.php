<?php

namespace App\Services\TrangChu;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class ExportDssvKhongCmTrangChu implements FromCollection, WithHeadings,WithEvents
{   

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
       $dssv_comat=DB::table('diem_danh as dd')
                ->join('lich_thi_phong_thi_ca_thi_ngay_thi as ltptctnt','ltptctnt.id','=','dd.lich_thi_phong_thi_ca_thi_ngay_thi_id')
                ->join('sinh_vien as sv','sv.id','=','dd.sinh_vien_id')
                ->join('lop as l','sv.lop_id','=','l.id')
                ->where('dd.lich_thi_phong_thi_ca_thi_ngay_thi_id', $this->id)
                ->where('dd.co_mat',0)
                ->select(
                    'sv.ma_sinhvien',
                    'sv.ho_ten_sinhvien',
                    'l.ten_lop',
                    'sv.ngay_sinh',
                    'sv.gioi_tinh',
                    'sv.email',
                    'sv.so_dien_thoai',
                    'sv.anh_khuon_mat',
                    'sv.tinh_trang',
                    'dd.co_mat',
                    'l.khoa_hoc',
                    )
                ->orderBy('l.khoa_hoc','asc')
                ->orderBy('l.ten_lop','asc')
                ->get();

            return $dssv_comat->map(function ($item,$index) {
            return [
                $index + 1,
                $item->ma_sinhvien,
                $item->ho_ten_sinhvien,
                $item->ten_lop,
                $item->ngay_sinh,
                $item->gioi_tinh,
                $item->email,
                $item->so_dien_thoai,
                $item->anh_khuon_mat,
                $item->tinh_trang,
                'Vắng'
                
            ];
        });
    }

    public function headings(): array
    {
         return [
            [
                'DANH SÁCH SINH VIÊN VẮNG' 
            ],
            [
                'STT',
                'MSSV',
                'Họ tên',
                'Lớp',
                'Ngày sinh',
                'Giới tính',
                'Email',
                'SĐT',
                'Ảnh ',
                'Tình Trạng', 
                'Có mặt',
            ] 
         ];
    }

   public function registerEvents(): array
{
    return [
        //AfterSheet::class là muốn can thiệp sau khi execl đã được tạo xong
        //$event Là một đối tượng chứa toàn bộ thông tin của cái file Excel đang nằm trong bộ nhớ.
        AfterSheet::class => function(AfterSheet $event) {
            $sheet = $event->sheet->getDelegate();
            // $event->sheet->getDelegate()  xin quyền điều khiển trực tiếp
            $sheet->mergeCells('A1:K1');

            // 2. CĂN GIỮA TOÀN BỘ SHEET (Dữ liệu + Tiêu đề)
            // Lấy dòng cuối cùng có dữ liệu
            $highestRow = $sheet->getHighestRow(); // trả về số của  dòng dài nhất| ví dụ dòng dài nhất là 53 => lấy số 53
            $highestColumn = $sheet->getHighestColumn(); // trả  về chữ cái cột cuối cùng| ví dụ M
            
            $fullRange = 'A1:' . $highestColumn . $highestRow;
            
            //getAlignment() dẫn vào bản điều khiển định dạng căn lề của ô trong execl
            //applyFromArray() thực hiện hàng lọt các thao tác thiết lập căn lề  của các dòng mà không phải gõ từng dòng
            //Thay vì bạn phải viết 5-6 dòng code riêng lẻ như thế này:
            // $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            // $sheet->getStyle('A1')->getAlignment()->setVertical('center');
            // $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
            $sheet->getStyle($fullRange)->getAlignment()->applyFromArray([
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,// căn giữa theo chiều ngang
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,// căn giữa theo chiều dọc
                'wrapText' => false, // Để false để ưu tiên giãn cột thay vì xuống dòng
            ]);

            // 3. TỰ ĐỘNG GIÃN CỘT (AUTO SIZE)
            // Lặp qua tất cả các cột từ A đến M để tự động chỉnh độ rộng
            foreach (range('A', $highestColumn) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true); //getColumnDimension chọn cột cụ thể,setAutoSize tự động đo chữ dài nhất trong cột là bao nhiêu rồi tự rộng vừa khích
            }

            // 4. THÊM VIỀN (BORDERS) CHO TOÀN BỘ BẢNG (Tùy chọn - giúp giống ảnh mẫu hơn)
            $sheet->getStyle('A2:' . $highestColumn . $highestRow)
                ->getBorders()// xin quyền Execl để làm việc đường viền của vùng mà mình đã chọn
                ->getAllBorders() //Kẻ tất cả các đường ngăn cách (trong, ngoài, ngang, dọc)
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);// Kiểu đường kẻ mảnh, phổ biến nhất trong văn bản hành chính.

            // 5. RE-APPLY IN ĐẬM CHO TIÊU ĐỀ (Vì bạn vừa ghi đè style toàn sheet)a
            $sheet->getStyle('A1:K2')->getFont()->setBold(true);// Im đậm
            $sheet->getStyle('A1')->getFont()->setSize(14); // cỡ chữ 
        },
    ];
}

    
}