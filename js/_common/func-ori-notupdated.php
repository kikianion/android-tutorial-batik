<?php

function expandHTMLSelectFromTable($tbl,$key,$val,$def,$attr_name){
    $s="select $key,$val from $tbl order by $val asc";
    $rs=mysql_query($s);
    $opt=array("...");
    while($r=mysql_fetch_array($rs)){
        $opt[$r[0]]=$r[1];
    }
    return expandHTMLSelect($opt, $def, $attr_name);
}
/*$opt in form of array key=>val
**
**
**
*/
function expandHTMLSelect($opt,$def,$attr_name){
    $res=<<<EOF
    <select name="$attr_name" id="$attr_name">
EOF;
    foreach($opt as $k=>$v){
        $selected="";
        if($def==$k) $selected="selected=selected";
        $res.=<<<EOF
        <option $selected value="$k">$v</option>
EOF;
    }
    $res.="</select>";

    return $res;
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid,12, 4).$hyphen
        .substr($charid,16, 4).$hyphen
        .substr($charid,20,12)
        .chr(125);// "}"
        return $uuid;
    }
}

function care_isset(& $v){
    return addslashes((isset($v)?$v:""));
}

function redirect_out($url){
    ob_end_clean();

    $url=(($url));
    header("location: $url");
    exit;
}

function error_box($msg){
    ?>
    <div style="margin: 25px 15px;">
        <table class="table table-condensed" 
            style="border: 1px solid #D0D0D0; box-shadow: 2px 2px 11px #D0D0D0; margin: 0 auto; width: 350px;">
            <tr>
                <td colspan="2" style="text-align: center;">
                    <h4 style="color: red;">PERINGATAN</h4>
                </td>
            </tr>
            <tr>
                <td width="50px" style="font-size: 38pt; color: red;"><span class="glyphicon glyphicon-ban-circle"></span></td>
                <td style="text-align: center;">
                    <?php
                    echo care_isset($msg);
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

function page_title($s){
    echo "<h1 style='text-align: center;'>$s</h1>";
}


function gen_crudform($tbl,$formfor, $tmpl,$keyfield, $pk,$name='',$cancelUrl="",$action="", $method="POST"){
    if($formfor=='update'){
        $actname="doUpdate";
    }
    else if($formfor=='insert'){
        $actname="doInsert";
    }
    else if($formfor=='delete'){
        $actname='doDelete';
    }


    $guid=getGUID();
    $_SESSION["form-$guid"]=1;

    $res=<<<EOF
    <div class="table-responsive">
      <form method='$method' action='$action' id="$name" name="$name">
      <input type='hidden' name='$actname' value='1'>
      <input type='hidden' name='pk' value='$pk'>
      <input type='hidden' name='form-guid' value='form-$guid'>
      
      <table class='table table-condensed'>
EOF;
    for($i=0; $i<count($tmpl); $i++){
        $fieldname=array_keys($tmpl)[$i];
        $title=$tmpl[$fieldname]["title"];
        $value=getAField($tbl,$keyfield,$pk,$fieldname);

        $ro="";
        if(isset($tmpl[$fieldname]["ro"])){
            $ro=" readonly=readonly ";
        }

        $disabled="";
        if(isset($tmpl[$fieldname]["disabled"])){
            $disabled=" disabled ";
        }


        if(isset($tmpl[$fieldname]["asterix"])){
            $value="***";
        }

        if(isset($tmpl[$fieldname]["lookup"])){
            $res.="
            <tr>
            <td>
            $title
            </td>
            <td>
            <select name='$fieldname' id='$fieldname' class='form-control' $disabled>
            <option value='0'>...</option>
            ";

            for($j=0; $j<count($tmpl[$fieldname]["lookup"]); $j++){
                $val=$tmpl[$fieldname]["lookup"][$j]["val"];

                $selected="";
                if($val==$value){
                    $selected=" selected ";
                }

                $txt=$tmpl[$fieldname]["lookup"][$j]["txt"];
                $res.= "
                <option value='$val' $selected>$txt</option>
                ";
            }

            $res.="
            </select>
            </td>
            </tr>
            ";

        }
        else{
            $res.="
            <tr>
            <td>
            $title
            </td>
            <td>
            <input type='text' value='$value' name='$fieldname' class='form-control' $ro>
            </td>
            </tr>
            ";

        }
    }

    $res.=<<<EOF
      <tr>
      <td colspan='2'>
                <div class='btn-group  btn-group-justified' role='group' aria-label='...'>
                    <div class='btn-group' role='group' aria-label='...''>
                        <input type='button' value='Batal' class='btn btn-default' 
                        onclick="javascript:location='$cancelUrl'">
                    </div>
                    <div class='btn-group' role='group' aria-label='...''>
                        <input type='button' value='$formfor' class='btn btn-default' onclick='begin$formfor()'>
                    </div>
                </div>
      </td>
      </tr>
      </table>
      </form>
      </div>
EOF;

    $res.=<<<EOF
      
<div id="dlg_$formfor" style="display: none;" title="Logout" class="fade in">
    <table class="table table-condensed">
        <tr>
            <td width="50px">
                <span style="font-size: 30pt;" class="glyphicon glyphicon-question-sign"></span>
            </td>
            <td>Apakah anda ingin meng$formfor data ?</td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <div class="btn-group  btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group" aria-label="...">
                        <input type="button" value="Tidak" class="btn btn-default" onclick="close_dlg()">
                    </div>
                    <div class="btn-group" role="group" aria-label="...">
                        <input type="button" value="Ya" class="btn btn-default" onclick="do$formfor()">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
      
      
      <script type="text/javascript">
      
      function begin$formfor(){
        //alert("apakah ingi meng$formfor data");
        
        $( "#dlg_$formfor" ).dialog({
            resizable: false,
            xheight:180,
            width: 350,
            modal: true,
            autoOpen: true,
        });
        
        return false;
      }
      
      function do$formfor(){
        $("#$name").submit();
      }
      
      function close_dlg(){
        $( "#dlg_$formfor" ).dialog('close');
      }
      </script>
      
      
EOF;
    return $res;
}

function getAField($tbl,$keyfield,$keyval,$sel){
    $s="select $sel from $tbl where $keyfield='$keyval' ";
    $rs=mysql_query($s);

    if(mysql_num_rows($rs)>0){
        return mysql_fetch_array($rs)[0];
    }
    else{
        return null;
    }

}


function gen_jadwal_angsur($id){
    $jadwal=<<<EOF2
        Jadwal Angsuran
        <table class="table table-condensed" border=1 width='100%'>
        <tr>
            <td>#</td>
            <td>Tgl tempo</td>
            <td>Pokok</td>
            <td>Jasa</td>
            <td>Total</td>
        </tr>
EOF2;

    $s="select * from tbl_angsuran where id_kredit=$id order by tgl_tempo asc";
    $rs=mysql_query($s) or die(mysql_error());

    while($r=mysql_fetch_assoc($rs)){
        $byr_ke=$r["byr_ke"];
        $tgl_tempo=$r["tgl_tempo"];
        $angsur_pokok=$r["pokok"];
        $angsur_jasa=$r["jasa"];
        $angsur_total=$angsur_pokok+$angsur_jasa;
        $jadwal.="
        <tr>
        <td>$byr_ke</td>
        <td>$tgl_tempo</td>
        <td>$angsur_pokok</td>
        <td>$angsur_jasa</td>
        <td>$angsur_total</td>
        </tr>
        ";
    }

    $jadwal.="
    </table>    
    ";

    return $jadwal;
}

function gen_anggota_kelompok($id_kelompok){
    $jadwal=<<<EOF2
        Anggota Kelompok
        <table class="table table-condensed" border=0 width='100%'>
        <tr>
            <td>#</td>
            <td>Nama</td>
        </tr>
EOF2;

    $s="select * from tbl_anggota where id_kelompok=$id_kelompok order by nama asc";
    $rs=mysql_query($s) or die(mysql_error());

    $i=1;
    while($r=mysql_fetch_assoc($rs)){
        $nama=$r["nama"];
        $jadwal.="
        <tr>
        <td>$i</td>
        <td>$nama</td>
        </tr>
        ";
        
        $i++;
    }

    $jadwal.="
    </table>    
    ";

    return $jadwal;
}


function angsuran_sudah_lunas_ke($id_pinjam){
    $s1="
    select * from tbl_angsuran where id_kredit='$id_pinjam' and lunas=1
    order by tgl_tempo asc
    limit 1
    ";
    $rs1=mysql_query($s1) or die(mysal_error());

    $r1=mysql_fetch_assoc($rs1);

    $ke=$r1["byr_ke"];

    return $ke;

}

function angsuran_mau_lunas_ke($id_pinjam){
    $s1="
    select * from tbl_angsuran where id_kredit='$id_pinjam' and lunas=0
    order by tgl_tempo asc
    limit 1
    ";
    $rs1=mysql_query($s1) or die(mysal_error());

    $r1=mysql_fetch_assoc($rs1);

    $ke=$r1["byr_ke"];
    return $ke;

}

function total_sisa_pokok_angsur($id_pinjam){
    $s="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$id_pinjam' and lunas=0";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["totalpokok"];
    
}

function total_sisa_jasa_angsur($id_pinjam){
    $s="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$id_pinjam' and lunas=0";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["totaljasa"];
    
}

function total_pokok_angsur($id_pinjam){
    $s="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$id_pinjam' and lunas=1";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["totalpokok"];
    
}

function total_jasa_angsur($id_pinjam){
    $s="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$id_pinjam' and lunas=1";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["totaljasa"];
    
}

function total_tunggak_pokok($id_pinjam){
    $s="select sum(pokok) as tunggak_pokok, sum(jasa) as tunggak_jasa 
    from tbl_angsuran where id_kredit=$id_pinjam and lunas=0 and tgl_tempo<now()";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["tunggak_pokok"];
    
}

function total_tunggak_jasa($id_pinjam){
    $s="select sum(pokok) as tunggak_pokok, sum(jasa) as tunggak_jasa 
    from tbl_angsuran where id_kredit=$id_pinjam and lunas=0 and tgl_tempo<now()";   
    
    $rs=mysql_query($s) or die(mysql_error());
    
    $r=mysql_fetch_assoc($rs);
    
    return $r["tunggak_jasa"];
    
}

?>
