<?php
// ham defined kieerm tra bien const có tồn tại hay ko
if(!defined('_CODE')){ 
    die('Access denide...');
}

function query($sql, $data=[],$check = false ) {
    global $conn;
    $ketqua = false;
    try{
        $statement = $conn -> prepare($sql);
    
        if(!empty($data)) {
            $ketqua = $statement -> execute($data);
        }
        else{
            $ketqua = $statement -> execute();
        }
    
    }catch(Exception $exp) {
        echo $exp -> getMessage().'<br>';
        echo 'FILE'.$exp -> getFile().'<br>';
        echo 'Line'.$exp -> getLine().'<br>';
        die();
    }

    if($check) {
        return $statement;
    }
    return $ketqua;
    
}

function insert($table, $data) {
    $key = array_keys($data);
    $truong = implode(",", $key);
    $valuetb = ":".implode(",:",$key);
    
    $sql = "INSERT INTO ". $table ."(" .$truong. ") VALUES (". $valuetb .")";

    $kq = query($sql,$data);
    return $kq;
}

function update ($table, $data,$condition='') {
    $update = '';
    foreach($data as $key=>$value){
        $update .= $key.'= :'. $key .',';  
    }
    $update = trim($update,',');
    if(!empty($condition)) {
        $sql = 'UPDATE '. $table . ' SET ' .$update. ' WHERE '. $condition;
    }

    $kq = query($sql, $data);
    return $kq;
}

function delete ($table, $condition) {
    if(!empty($condition)) {
        $sql = 'DELETE FROM '.$table.' WHERE '.$condition;
    } else {
        $sql = 'DELETE FROM '.$table;
    }
    $kq = query($sql);
    return $kq;
}

//lấy nhiều dòng dữ liệu
function getRaw ($sql) {
    $kq = query($sql,'',true) ;
    if(is_object($kq)) {
        $dataFetch = $kq -> fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}

//lấy 1 dòng dữ liệu
function oneRaw ($sql) {
    $kq = query($sql,'',true) ;
    if(is_object($kq)) {
        $dataFetch = $kq -> fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}

//lấy số dòng dữ liệu
function getRows ($sql) {
    $kq = query($sql,'',true) ;
    if($kq) {
        return $kq -> rowCount();
    }
}
