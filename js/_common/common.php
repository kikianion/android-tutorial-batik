<?php

class LZW {
    function compress($uncompressed) {
        $dictSize = 256;
        $dictionary = array();
        for ($i = 0; $i < 256; $i++) {
            $dictionary[chr($i)] = $i;
        }
        $w = "";
        $result = "";
        for ($i = 0; $i < strlen($uncompressed); $i++) {
            $c = $this->charAt($uncompressed, $i);
            $wc = $w.$c;
            if (isset($dictionary[$wc])) {
                $w = $wc;
            } else {
                if ($result != "") {
                    $result .= ",".$dictionary[$w];
                } else {
                    $result .= $dictionary[$w];
                }
                $dictionary[$wc] = $dictSize++;
                $w = "".$c;
            }
        }
        if ($w != "") {
            if ($result != "") {
                $result .= ",".$dictionary[$w];
            } else {
                $result .= $dictionary[$w];

            }
            return $result;
        }    
    }

    function decompress($compressed) {
        $compressed = explode(",", $compressed);
        $dictSize = 256;
        $dictionary = array();
        for ($i = 1; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }
        $w = chr($compressed[0]);
        $result = $w;
        for ($i = 1; $i < count($compressed); $i++) {
            $entry = "";
            $k = $compressed[$i];
            if (isset($dictionary[$k])) {
                $entry = $dictionary[$k];
            } else if ($k == $dictSize) {
                $entry = $w.$this->charAt($w, 0);
            } else {
                return null;
            }
            $result .= $entry;
            $dictionary[$dictSize++] = $w.$this->charAt($entry, 0);
            $w = $entry;
        }
        return $result;
    }
    function charAt($string, $index){
        if($index < mb_strlen($string)){
            return mb_substr($string, $index, 1);
        } else{
            return -1;
        }
    }
}



$str_tidak_terdefinisi="xxx--tidak terdefinisi--xxx";

$cek_nama_error[0]="xxx--nama salah--xxx";
$cek_nama_error[1]="";
$cek_nama_error[2]="xxx--tidak terdefinisi--xxx";

$cek_berhak_error[0]="xxx--tidak berhak--xxx";


/*function get_sesi_aktif(){
global $pdo_conn;

@$tahun=$_SESSION["tahun"];

if($tahun==''){
$s=" select * from master_tahun where active_=:f order by tahun desc limit 1";
$st=$pdo_conn->prepare($s);
$st->execute(array(
"f"=>1
));

$rs=$st->fetchAll();

if(count($rs)==1){
$_SESSION['tahun']=$rs[0]['tahun'];
return $rs[0]['tahun'];
}
else{
return -1;
}
}
else{
return $tahun;
}

}*/

function load_select_skpd(){
    global $pdo_conn;
    try{
        $s="
        (select '' as id,'-' as text)
        union
        (select kode_angka as id,nama as text from master_skpd order by text)
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

function load_select_skpd_dinas(){
    global $pdo_conn;
    try{
        $s="
        (select 'NA' as id,'NA' as text)
        union
        (select kode_angka as id,nama as text from master_skpd where type_='dinas' or type_='bagian' order by text)
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

function load_select_skpdByBidang($kode_bidang){
    global $pdo_conn;
    try{
        //back up in case error
        /*$s="
        (select 'NA' as id,'NA' as text)
        union        
        (        select skpd,ms.nama from
        (SELECT bidang, SUBSTRING_INDEX(SUBSTRING_INDEX(t.skpd, ',', n.n), ',', -1) skpd
        FROM bag_urusan_skpd t CROSS JOIN        (        SELECT a.N + b.N * 10 + 1 n
        FROM        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
        UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
        UNION ALL SELECT 8 UNION ALL SELECT 9) a        ,
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
        UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
        UNION ALL SELECT 8 UNION ALL SELECT 9) b        ORDER BY n        ) n
        WHERE        n.n <= 1 + (LENGTH(t.skpd) - LENGTH(REPLACE(t.skpd, ',', '')))

        ) tmain
        join master_skpd ms on ms.kode_angka=tmain.skpd
        where bidang='$kode_bidang'
        )        
        ";*/
        
        $s="
        (select '' as id,'-' as text, '' as kodebid)
        union
        (        select skpd, ms.nama, tmain.kode from
        (SELECT kode, SUBSTRING_INDEX(SUBSTRING_INDEX(t.skpd, ',', n.n), ',', -1) skpd
        FROM master_bmitra t CROSS JOIN        (        SELECT a.N + b.N * 10 + 1 n
        FROM        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
        UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
        UNION ALL SELECT 8 UNION ALL SELECT 9) a        ,
        (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3
        UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7
        UNION ALL SELECT 8 UNION ALL SELECT 9) b        ORDER BY n        ) n
        WHERE        n.n <= 1 + (LENGTH(t.skpd) - LENGTH(REPLACE(t.skpd, ',', '')))

        ) tmain
        join master_skpd ms on ms.kode_angka=tmain.skpd
        where tmain.kode='$kode_bidang'
        )
        
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

function load_select_bidang(){
    global $pdo_conn;
    try{
        $s="
        (select 'NA' as id,'NA' as text)
        union
        (select concat(kode_u,'.',kode_b) as id, concat(kode_u,'.',kode_b,'-',nama) as text from master_bidang order by text)
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

function load_select_bmitra(){
    global $pdo_conn;
    try{
        $s="
        (select kode as id,nama as text from master_bmitra order by text)
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

function load_select_kegiatan(){
    global $pdo_conn;
    try{
        $s="
        (select 'NA' as id,'NA' as text)
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


function load_autocomplete_sumberdana(){
    global $pdo_conn;
    try{
        $s="select distinct nama from master_sumberdana order by nama";
        $st=$pdo_conn->prepare($s);
        $st->execute();

        $rs=$st->fetchAll(PDO::FETCH_ASSOC);

        $resp["result"]="ok";
        $resp["data"]=$rs;

        //hack speed
        $resp=$rs;
    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }
    return $resp;

} 


define("ACT_USER_LOGIN", "login");
define("ACT_USER_LOGOUT", "logout");

define("MRBKAB_SKPD_INSERT", "musrenkab-skpd-kegiatan-tambah-usulan");
define("MRBKAB_SKPD_UPDATE", "musrenkab-skpd-kegiatan-ubah-usulan");
define("MRBKAB_SKPD_DELETE", "musrenkab-skpd-kegiatan-hapus-usulan");

define("MRBKAB_SKPD_BUAT_KEGIATAN", "musrenkab-skpd-kegiatan-definisi-baru");

/**
* untuk log keamatan entri usulan
*/
define("MRBC_KEC_INSERT", "musrencam-kec-tambah-kegiatan");
define("MRBC_KEC_UPDATE", "musrencam-kec-ubah-kegiatan");
define("MRBC_KEC_DELETE", "musrencam-kec-hapus-kegiatan");

/**
* bidang verifikasi, set skpd, pindah bidang, setujui 
*/
define("MRBC_BID_SETSKPD", "musrencam-bid-setskpd");
define("MRBC_BID_CHGBIDANG", "musrencam-bid-gantibidang");
define("MRBC_BID_ACC", "musrencam-bid-setujui");

/**
* untuk log keamatan entri usulan
*/
define("MRBC_ADM_INSERT",   "musrencam-admin-tambah-kegiatan");
define("MRBC_ADM_UPDATE",   "musrencam-admin-ubah-kegiatan");
define("MRBC_ADM_DELETE",   "musrencam-admin-hapus-kegiatan");
define("MRBC_ADM_SETSKPD",  "musrencam-admin-setskpd");
define("MRBC_ADM_CHGBIDANG","musrencam-admin-gantibidang");
define("MRBC_ADM_ACC",      "musrencam-admin-setujui");

function act_log($act_type , $data1="", $data2="", $data3="", $data4=""){
    global $pdo_conn;

    @$by_=$_SESSION['login']['user']?$_SESSION['login']['user']:'-';
    @$impersonate_on=$_SESSION["impersonate_on"]?1:0;

    $data1=str_replace("\r","",$data1);
    $data2=str_replace("\r","",$data2);
    $data3=str_replace("\r","",$data3);
    $data4=str_replace("\r","",$data4);

    try{
        $s="insert into act_log(dt,act_type, by_,impersonate_,  data1, data2, data3, data4)
        values(now(),?,?,?,?,?,?,?)
        ";
        $st=$pdo_conn->prepare($s);
        $st->execute(array(
            $act_type, $by_, $impersonate_on, $data1, $data2, $data3, $data4         
        ));

        $resp["result"]="ok";

    }
    catch(Exception $e){
        $resp["result"]="error";
        $resp["msg"]=$e->getMessage();
    }
    return $resp;

}

/**
* Replaces any parameter placeholders in a query with the value of that
* parameter. Useful for debugging. Assumes anonymous parameters from 
* $params are are in the same order as specified in $query
*
* @param string $query The sql query with parameter placeholders
* @param array $params The array of substitution parameters
* @return string The interpolated query
*/
function interpolateQuery($query, $params) {

    if(!isset($query) || !isset($params) ) return false;
    $keys = array();
    $values = $params;

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
        if (is_string($key)) {
            $keys[] = '/:'.$key.'/';
        } else {
            $keys[] = '/[?]/';
        }

        if (is_string($value))
            $values[$key] = "'" . $value . "'";

        if (is_array($value))
            $values[$key] = "'" . implode("','", $value) . "'";

        if (is_null($value))
            $values[$key] = 'NULL';
    }

    $query = preg_replace($keys, $values, $query, 1, $count);

    return $query;
}

function setPageTitle($t, $toolbar=""){
    ?>
    <script type="text/javascript">

        //$("#div-page-icon").html();
        document.title='<?php echo $t?>'+' - '+MAIN_TITLE;
        //var $scope = angular.element($("#div-page-icon")).scope();        
        //var page_icon=$scope.data.page_icon;

        //debugger;
        //$("#div-page-icon").html(decode_base64(page_icon));        
    </script>
    <div class="view-title">
        <span ng-bind-html="data.page_icon_decode"></span>
        <?php echo $t?>
        <span><?php echo $toolbar?></span>    
    </div>
    <hr>
    <?php
}

function rgbcode($id){
    return '#'.substr(md5($id), 0, 6);
}

function gen_slug($str){
    # special accents
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','Ð','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','?','?','J','j','K','k','L','l','L','l','L','l','?','?','L','l','N','n','N','n','N','n','?','O','o','O','o','O','o','Œ','œ','R','r','R','r','R','r','S','s','S','s','S','s','Š','š','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Ÿ','Z','z','Z','z','Ž','ž','?','ƒ','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','?','?','?','?','?','?');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/','/[ -]+/','/^-|-$/'),array('','-',''),str_replace($a,$b,$str)));
}

define('RW_ACTIVE_NOW_RANGE','1');
define('RW_ACTIVE_NOW_START','2');
define('RW_ACTIVE_NOW_FINISH','3');

/**
* untuk cek apakah tgl sekarang termasuk range jadwal, inklusif, juga termasuk cek tahun aktif di master tahun
* 
* @param mixed $kode
* @param mixed $type
*/
function isRWActiveNow($kode, $type=RW_ACTIVE_NOW_RANGE){
    
    @$tahun=$_SESSION['tahun'];
    
    if(!$tahun){
        $tahun=get_a_field("master_tahun","active_",'1',"tahun");
    }
    
    $tahun_aktif=get_a_field("master_tahun","tahun",$tahun,"active_");
    if($tahun_aktif!=1) return false;

    if($type==RW_ACTIVE_NOW_RANGE){
        $fEditable=
        get_a_field("jadwal","kode",$kode,"(now()>startdt and now()<=date_add(finishdt,interval 1 day) ) as insession");
        if($fEditable && $fEditable=='1') return true;

    }
    else if($type==RW_ACTIVE_NOW_START){
        $fEditable=
        get_a_field("jadwal","kode",$kode,"( now()>startdt ) as insession");
        if($fEditable && $fEditable=='1') return true;

    }
    else if($type==RW_ACTIVE_NOW_FINISH){
        $fEditable=
        get_a_field("jadwal","kode",$kode,"( now()<=date_add(finishdt,interval 1 day) ) as insession");
        if($fEditable && $fEditable=='1') return true;

    }

    return false;
}

function isThisTahunAktif(){
    $tahun=$_SESSION['tahun'];
    $tahun_aktif=get_a_field("master_tahun","tahun",$tahun,"active_");
    if($tahun_aktif==1) return true;
    else return false;

}
?>

