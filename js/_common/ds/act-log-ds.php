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

function getLast10act(){
    global $pdo_conn;

    try{
        $s="select * from act_log order by dt desc limit 10";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $rs=$st->fetchAll(PDO::FETCH_ASSOC);    

        $resp["result"]="ok";
        $resp["data"]=$rs;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }
    return $resp;
}

function getActAfter(){
    global $pdo_conn;
    
    
    @$dt_=$_REQUEST["dt_"];

    try{
        $s="select * from act_log where dt>? order by dt desc ";
        $st=$pdo_conn->prepare($s);
        $st->execute(array($dt_));
        $rs=$st->fetchAll(PDO::FETCH_ASSOC);    

        $resp["result"]="ok";
        $resp["data"]=$rs;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }
    return $resp;
}


function load(){
    global $pdo_conn;

    try{
        $s="select id, dt, act_type,by_ from act_log order by dt desc ";
        $st=$pdo_conn->prepare($s);
        $st->execute();
        $rs=$st->fetchAll(PDO::FETCH_ASSOC);    

        $resp["result"]="ok";
        $resp["data"]=$rs;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }
    return $resp;
    
}
?>
