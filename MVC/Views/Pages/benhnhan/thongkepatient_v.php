<?php
ob_start(); //<--- Dòng code yêu cầu Output Buffering

$patient = array();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
while ($row = mysqli_fetch_assoc($data['listNhapVien'])) {
    array_push($patient, $row);
}

if (isset($_POST['excel'])) {
    // Header row
    $headerRowData = ['Họ tên', 'Bệnh', 'Ngày nhập viện', 'Ngày xuất viện', 'Phòng', 'Giường', 'Bác sĩ phụ trách'];
    $columnIndex = 1;
    foreach ($headerRowData as $headerCell) {
        $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . '1';
        $sheet->setCellValue($cellCoordinate, $headerCell);
        $columnIndex++;
    }
    // Data rows
    $dataRow = 2;
    foreach ($patient as $rowData) {
        $columnIndex = 1;
        foreach ($rowData as $propertyValue) {
            $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex) . $dataRow;
            $sheet->setCellValue($cellCoordinate, $propertyValue);
            $columnIndex++;
        }
        $dataRow++;
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save('exNhapVien.xlsx');
    ob_end_flush(); //<--- Dòng code yêu cầu in ra tất cả và trả về reponse cho người dùng (Client) 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="http://localhost/ManagerPatientPHP/Public/css/medicalBox.css">
    <link rel="stylesheet" href="http://localhost/ManagerPatientPHP/Public/css/editdoctor.css">
</head>

<body>

    <div class="main">
        <header class="header">
            <div class="page">
                <h1 class="name__page">Bệnh Nhân</h1>
                <h3 class="desc__page">Thống kê bệnh nhân</h3>
            </div>
            <span class="btn--add" style="font-size: 13px; font-weight: 700 ;margin-left: auto; margin-right: 20px;" data-bs-toggle="modal" data-bs-target="#discharge">
                Xuất viện
            </span>

            <form method="POST" action="http://localhost/ManagerPatientPHP/hosobenhnhan/xuatvien" class="modal needs-validation" id="discharge">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Nhập viên</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <select required name="hoten" class="form-select" aria-label="Default select example">
                                    <option selected>Chọn bệnh nhân</option>
                                    <?php
                                    if (mysqli_num_rows($data['listNotYetDischarge']) > 0) {
                                        while ($row = mysqli_fetch_array($data['listNotYetDischarge'])) {
                                    ?>
                                            <option value="<?php echo $row['mabenhnhan'] ?>"><?php echo $row['name'] ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <option value="Null"><?php echo "Không còn bệnh nhân nào!" ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Ngày xuất viện</label>
                                <input required name="ngayxuatvien" type="date" class="form-control" id="date">
                            </div>

                            <!-- Modal footer -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </div>
                </div>
            </form>

        </header>
        <div class="container">
            <div class="container__header">
                <form method="POST" action="" class="main-functions">
                    <button name="excel" class="btn btn--excel">
                        <span>Excel</span>
                    </button>
                    <button class="btn btn--pdf">
                        <span>Pdf</span>
                    </button>
                </form>
                <form method="POST" action="http://localhost/ManagerPatientPHP/hosobenhnhan/timkiem" class="search">
                    <label for="" class="search__label">Tìm kiếm</label>
                    <input placeholder="Tìm kiếm" type="text" name="keyword" id="txtSearch" class="search__input">
                </form>
            </div>

            <div class="container__content">
                <table class="drug--info table table-striped table-hover">
                    <tr>
                        <th style="text-align: left">Mã bệnh nhân</th>
                        <th style="text-align: left">Họ tên</th>
                        <th style="text-align: left">Bệnh</th>
                        <th style="text-align: left">Ngày nhập viện</th>
                        <th style="text-align: left">Ngày xuất viện</th>
                        <th style="text-align: left">Phòng</th>
                        <th style="text-align: left">Giường</th>
                        <th style="text-align: left" class="col-2">Bác sĩ phụ trách</th>
                        <th class="col-1-4"></th>
                        <th class="col-1-4"></th>
                    </tr>
                    <?php
                    // B3: Xử lý kết quả
                    if (isset($data["listDocs"]) && $data["listDocs"] != null) {
                        while ($row = mysqli_fetch_array($data["listDocs"])) {
                    ?>
                            <tr>
                                <td style="text-align: left" class="col-1"><?php echo $row['mabenhnhan'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['name'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['tenbenh'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['ngaynhapvien'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['ngayxuatvien'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['tenphongbenh'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['sogiuong'] ?></td>
                                <td style="text-align: left" class="col-1"><?php echo $row['hoten'] ?></td>
                                <td style="text-align: left" class="col-1-4">
                                    <a href="http://localhost/ManagerPatientPHP/danhsachbenhnhan/suabenhnhan/?id=<?php echo $row['mabenhnhan']; ?>" class="btn--edit">
                                        <lord-icon src="https://cdn.lordicon.com/hiqmdfkt.json" trigger="hover" colors="primary:#26577C,secondary:#cb5eee" style="width:30px;height:30px">
                                        </lord-icon>
                                    </a>
                                </td>
                                <td style="text-align: left" class="col-1-4">
                                    <a href="#" class="btn--remove" data-bs-toggle="modal" data-bs-target="#delete<?php echo $row['mabenhnhan']; ?>">
                                        <lord-icon src="https://cdn.lordicon.com/tntmaygd.json" trigger="hover" colors="primary:#D80032,secondary:#cb5eee" style="width:30px;height:30px">
                                        </lord-icon>
                                    </a>

                                    <div class=" modal fade" id="delete<?php echo $row['mabenhnhan']; ?>" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteLabel">Xóa bác sĩ</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h5>Bạn có chắc muốn xóa bênh nhân không</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <a href="http://localhost/ManagerPatientPHP/danhsachbenhnhan/xoabenhnhan/?id=<?php echo $row['mabenhnhan']; ?>"> <button style="width: 200px;" type="button" class="btn btn-danger">Xác nhận</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

</body>

</html>