<?php

if(!defined("INCLUDING_AS_LIB")){
    //param
    @$f=$_REQUEST["f"];

    //main
    if(function_exists($f)){
        echo json_encode($f());
    }
    else{
        echo '{"result":"fungsi tidak ada"}';
    }

}

function get_master_data(){
    global $pdo_conn;

    $master_program=array();
    $master_bidang=array();
    $master_urusan=array();
    $master_skpd=array();
    $master_kegiatan=array();
    $master_bmitra=array();

    try{
        $s="select * from master_urusan";
        $st=$pdo_conn->prepare($s);

        $st->execute();

        $master_urusan=$st->fetchAll(PDO::FETCH_ASSOC);    

        $s="select * from master_bidang";
        $st=$pdo_conn->prepare($s);

        $st->execute();

        $master_bidang=$st->fetchAll(PDO::FETCH_ASSOC);    

        $s="select * from master_program order by kode_u, kode_b , kode_p";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $master_program=$st->fetchAll(PDO::FETCH_ASSOC);    

        $s="select * from master_skpd";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $master_skpd=$st->fetchAll(PDO::FETCH_ASSOC);    

        $s="select * from master_bmitra";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $master_bmitra=$st->fetchAll(PDO::FETCH_ASSOC);    

        $s="select * from master_kegiatan order by kode_u, kode_b , kode_p, kode_k";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $master_kegiatan=$st->fetchAll(PDO::FETCH_ASSOC);    


        foreach($master_kegiatan as $key=>&$val){
            foreach($val as $key2=>&$val2){
                if($val2==null) $val2='';
            }
        }

        $resp["result"]="ok";
        $resp["master_urusan"]=$master_urusan;
        $resp["master_bidang"]=$master_bidang;
        $resp["master_program"]=$master_program;
        $resp["master_kegiatan"]=$master_kegiatan;
        $resp["master_skpd"]=$master_skpd;
        $resp["master_bmitra"]=$master_bmitra;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }

    return $resp;
}

function get_news(){
    global $pdo_conn;

    try{
        $s="select concat('<i>{{',dt,'}}</i>','<br>',isi) as news1 from news order by dt desc limit 200";
        $st=$pdo_conn->prepare($s);

        $st->execute();

        $data=$st->fetchAll(PDO::FETCH_ASSOC);    

        $resp["result"]="ok";
        $resp["data"]=$data;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }

    return $resp;
}

function chpass_save(){
    global $pdo_conn;

    @$lama=$_REQUEST["lama"];
    @$baru=$_REQUEST["baru"];
    $user=$_SESSION["login"]["user"];

    $s="update user_ set passwd_=:baru where user_=:user and passwd_=:lama ";
    try{
        $st=$pdo_conn->prepare($s);
        $st->execute(array(
            "baru"=>$baru,
            "lama"=>$lama,
            "user"=>$user
        ));

        if($st->rowCount()>0){
            $resp["result"]="ok";

        }
        else{
            $resp["result"]="error";
        }
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();

    }

    return $resp;

}

function load_select_soundexkegiatan(){
    global $pdo_conn;
    
    @$s=$_REQUEST['s'];
    
    try{
        $s="
        (select id as id, nama as text from master_kegiatan where soundex(nama)=soundex('$s') order by text)
        union
        (select '-' as id, '----------------------' as text)
        union
        
        (select id as id, nama as text from master_kegiatan order by text)
        ";
        $st=$pdo_conn->prepare($s);
        $st->execute();

        $rs=$st->fetchAll(PDO::FETCH_ASSOC);
        return $rs;
    }
    catch(Exception $e){
        return $e->getMessage();
    }
}


function getSrvTime(){
    $res['result']='ok';
    $res['srvtime']=date("Y-m-d H:i:s");
    
    return $res;
}
?>
