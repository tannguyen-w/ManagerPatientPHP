<?php
class taikhoanModel extends connectDB
{
    function dangky($name, $username, $password, $sodienthoai)
    {
        $query = "INSERT INTO acount VALUES('','$name', '$username', '$password', '$sodienthoai', '0')";
        return mysqli_query($this->con, $query);
    }

    function dangnhap($username, $password, $role)
    {
        $query = "SELECT * FROM `acount` WHERE `username` = '$username' AND `password` = '$password' AND `role` = '$role'";
        return mysqli_query($this->con, $query);
    }

    function checkIdenticalAccout($username)
    {
        $query = "SELECT * FROM `acount` WHERE `username` = '$username'";
        return mysqli_query($this->con, $query);
    }

    function ngayhen($email) {
        $sql = "SELECT lichhen.ngayhen from `benhnhan`, `acount`, `lichhen` where benhnhan.idtaikhoan = acount.id and acount.username = '$email' and benhnhan.mabenhnhan = lichhen.mabenhnhan and lichhen.tinhtrang = '0'";
        return mysqli_query($this->con, $sql);
    }
}
