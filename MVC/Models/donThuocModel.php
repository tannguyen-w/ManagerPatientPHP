<?php
    class donThuocModel extends connectDB {
        function donThuocModel_find ($mdt) {
            $sql = "SELECT donthuoc.madonthuoc, acount.name, bacsi.hoten, donthuoc.ngaykedon, thuoc.tenthuoc, donthuoc.donvi, thuoc.hamluong, donthuoc.soluong, donthuoc.huongdan FROM `donthuoc`, `thuoc`, `bacsi`, `benhnhan`, `acount`  WHERE acount.id = benhnhan.idtaikhoan and donthuoc.mabacsi = bacsi.mabacsi and donthuoc.mabenhnhan = benhnhan.mabenhnhan and donthuoc.mathuoc = thuoc.mathuoc and madonthuoc like '%$mdt%'";
            return mysqli_query($this->con, $sql);
        }

        function donThuocModel_findOne ($mdt) {
            $sql = "SELECT donthuoc.madonthuoc, acount.name, bacsi.hoten, donthuoc.ngaykedon, donthuoc.donvi, thuoc.tenthuoc, thuoc.hamluong, donthuoc.soluong, donthuoc.huongdan 
            FROM `donthuoc`, `thuoc`, `bacsi`, `benhnhan`, `acount`  
            WHERE acount.id = benhnhan.idtaikhoan and donthuoc.mabacsi = bacsi.mabacsi and donthuoc.mabenhnhan = benhnhan.mabenhnhan and donthuoc.mathuoc = thuoc.mathuoc and madonthuoc like '$mdt'";
            return mysqli_query($this->con, $sql);
        }

        function checkId ($mdt) {
            $sql =  "SELECT * FROM donthuoc Where madonthuoc = '$mdt'";
            return mysqli_query($this->con, $sql);
        }

        function donThuocModel_ins ($mdt, $tbn, $nkd, $ngaykd, $tt, $sl, $dv, $hd) {
            $mbn = $this->mabn($tbn);
            $mbs = $this->mabs($nkd);
            $mt = $this->mathuoc($tt);
            $sql = "INSERT INTO `donthuoc` VALUES ('$mdt','$mbn','$mbs','$ngaykd','$mt','$sl','$dv','$hd')";
            return mysqli_query($this->con, $sql);
        }

        function tenbenhnhan () {
            $sql = "SELECT name From acount Where role = '0'";
            return mysqli_query($this->con, $sql);
        }

        function tenbacsi () {
            $sql = "SELECT hoten From bacsi";
            return mysqli_query($this->con, $sql);
        }

        function tenthuoc () {
            $sql = "SELECT tenthuoc From thuoc";
            return mysqli_query($this->con, $sql);
        }

        function mabn($tbn) {
            $sql = "SELECT benhnhan.mabenhnhan FROM `benhnhan`, `acount` WHERE acount.id = benhnhan.idtaikhoan and acount.name = '$tbn'";
            $result = mysqli_query($this->con, $sql);
            $row = mysqli_fetch_assoc($result);
            $mbn = $row['mabenhnhan'];
            return $mbn;
        }

        function mabs($nkd) {
            $sql = "SELECT mabacsi FROM `bacsi` where hoten = '$nkd'";
            $result = mysqli_query($this->con, $sql);
            $row = mysqli_fetch_assoc($result);
            $mbs = $row['mabacsi'];
            return $mbs;
        }

        function mathuoc($tt) {
            $sql = "SELECT mathuoc From `thuoc` where tenthuoc = '$tt'";
            $result = mysqli_query($this->con, $sql);
            $row = mysqli_fetch_assoc($result);
            $mt = $row['mathuoc'];
            return $mt;
        }

        function donThuocModel_upd ($mdt, $tbn, $nkd, $ngaykd, $tt, $sl, $dv, $hd) {
            $mbn = $this->mabn($tbn);
            $mbs = $this->mabs($nkd);
            $mt = $this->mathuoc($tt);
            $sql = "UPDATE `donthuoc` SET mabenhnhan = '$mbn', mabacsi = '$mbs', ngaykedon = '$ngaykd', mathuoc = '$mt', soluong = '$sl', donvi ='$dv', huongdan = '$hd' WHERE madonthuoc = '$mdt'";
            return mysqli_query($this->con, $sql);
        }

        function donThuocModel_del ($mdt) {
            $sql = "DELETE FROM donthuoc where madonthuoc = '$mdt'";
            return mysqli_query($this->con, $sql);
        }
    }
?>