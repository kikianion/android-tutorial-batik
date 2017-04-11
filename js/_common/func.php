<?php

function contentHeader($h_big,$h_small){
    ?>
    <script type="text/javascript">
        $("#header_big").html("<?php echo $h_big?>");
        $("#header_small").html("<?php echo $h_small?>");
    </script>
    <?php
}

function expandHTMLSelectFromTable($_c74b11fc020d,$_b74b2874ea7f,$_76bc452142d9,$_b8d3151429f9,$_f54de1875b8d){
    $_ea73b8369107="select $_b74b2874ea7f,$_76bc452142d9 from $_c74b11fc020d order by $_76bc452142d9 asc";
    $_2d7124e7e8fb=mysql_query($_ea73b8369107);
    $_b8237d0915fd=array("...");
    while($_e3236e163903=mysql_fetch_array($_2d7124e7e8fb)){
        $_b8237d0915fd[$_e3236e163903[0]]=$_e3236e163903[1];
    }
    return expandHTMLSelect($_b8237d0915fd, $_b8d3151429f9, $_f54de1875b8d);
}

function expandHTMLSelect($_b8237d0915fd,$_b8d3151429f9,$_f54de1875b8d){
    $_23e8d6ad432c=<<<EOF
    <select name="$_f54de1875b8d" id="$_f54de1875b8d">
EOF;
    foreach($_b8237d0915fd as $_d76fb022f018=>$_5ac52ed99f5d){
        $_921c3a6a6922="";
        if($_b8d3151429f9==$_d76fb022f018) $_921c3a6a6922="selected=selected";
        $_23e8d6ad432c.=<<<EOF
        <option $_921c3a6a6922 value="$_d76fb022f018">$_5ac52ed99f5d</option>
EOF;
    }
    $_23e8d6ad432c.="</select>";

    return $_23e8d6ad432c;
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);        $_7fd9be85e598 = strtoupper(md5(uniqid(rand(), true)));
        $_b3feff3b31cb = chr(45);        $_8c8d8ee1fade = chr(123)        .substr($_7fd9be85e598, 0, 8).$_b3feff3b31cb
        .substr($_7fd9be85e598, 8, 4).$_b3feff3b31cb
        .substr($_7fd9be85e598,12, 4).$_b3feff3b31cb
        .substr($_7fd9be85e598,16, 4).$_b3feff3b31cb
        .substr($_7fd9be85e598,20,12)
        .chr(125);        return $_8c8d8ee1fade;
    }
}

function care_isset(& $_5ac52ed99f5d){
    return addslashes((isset($_5ac52ed99f5d)?$_5ac52ed99f5d:""));
}

function redirect_out($_dc41673fa0ad){
    ob_end_clean();

    $_dc41673fa0ad=(($_dc41673fa0ad));
    header("location: $_dc41673fa0ad");
    exit;
}

function error_box($_5de8de3f6245){
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
                    echo care_isset($_5de8de3f6245);
                    ?>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

function page_title($_ea73b8369107){
    echo "<h1 style='text-align: center;'>$_ea73b8369107</h1>";
}


function gen_crudform($_c74b11fc020d,$_c8cfdbf2cf8a, $_f261d9e84f7c,$_db542ae617df, $_637836ae8f88,$_1b09128c4f0b='',$_1c8ef100f7ab="",$_75e55149a046="", $_603229e37f0e="POST"){
    if($_c8cfdbf2cf8a=='update'){
        $_e6d78f30a576="doUpdate";
    }
    else if($_c8cfdbf2cf8a=='insert'){
        $_e6d78f30a576="doInsert";
    }
    else if($_c8cfdbf2cf8a=='delete'){
        $_e6d78f30a576='doDelete';
    }


    $_36bb786bf4a6=getGUID();
    $_SESSION["form-$_36bb786bf4a6"]=1;

    $_23e8d6ad432c=<<<EOF
    <div class="table-responsive">
      <form method='$_603229e37f0e' action='$_75e55149a046' id="$_1b09128c4f0b" name="$_1b09128c4f0b">
      <input type='hidden' name='$_e6d78f30a576' value='1'>
      <input type='hidden' name='pk' value='$_637836ae8f88'>
      <input type='hidden' name='form-guid' value='form-$_36bb786bf4a6'>
      
      <table class='table table-condensed'>
EOF;
    for($_d181e8a209b5=0; $_d181e8a209b5<count($_f261d9e84f7c); $_d181e8a209b5++){
        $_ff22190a3ed5=array_keys($_f261d9e84f7c)[$_d181e8a209b5];
        $_384dc6d13850=$_f261d9e84f7c[$_ff22190a3ed5]["title"];
        $_61d2236a6807=getAField($_c74b11fc020d,$_db542ae617df,$_637836ae8f88,$_ff22190a3ed5);

        $_2d1febcf190a="";
        if(isset($_f261d9e84f7c[$_ff22190a3ed5]["ro"])){
            $_2d1febcf190a=" readonly=readonly ";
        }

        $_0625ea549e8f="";
        if(isset($_f261d9e84f7c[$_ff22190a3ed5]["disabled"])){
            $_0625ea549e8f=" disabled ";
        }


        if(isset($_f261d9e84f7c[$_ff22190a3ed5]["asterix"])){
            $_61d2236a6807="***";
        }

        if(isset($_f261d9e84f7c[$_ff22190a3ed5]["lookup"])){
            $_23e8d6ad432c.="
            <tr>
            <td>
            $_384dc6d13850
            </td>
            <td>
            <select name='$_ff22190a3ed5' id='$_ff22190a3ed5' class='form-control' $_0625ea549e8f>
            <option value='0'>...</option>
            ";

            for($_ebd0d83ecefa=0; $_ebd0d83ecefa<count($_f261d9e84f7c[$_ff22190a3ed5]["lookup"]); $_ebd0d83ecefa++){
                $_76bc452142d9=$_f261d9e84f7c[$_ff22190a3ed5]["lookup"][$_ebd0d83ecefa]["val"];

                $_921c3a6a6922="";
                if($_76bc452142d9==$_61d2236a6807){
                    $_921c3a6a6922=" selected ";
                }

                $_c6a81352d7ce=$_f261d9e84f7c[$_ff22190a3ed5]["lookup"][$_ebd0d83ecefa]["txt"];
                $_23e8d6ad432c.= "
                <option value='$_76bc452142d9' $_921c3a6a6922>$_c6a81352d7ce</option>
                ";
            }

            $_23e8d6ad432c.="
            </select>
            </td>
            </tr>
            ";

        }
        else{
            $_23e8d6ad432c.="
            <tr>
            <td>
            $_384dc6d13850
            </td>
            <td>
            <input type='text' value='$_61d2236a6807' name='$_ff22190a3ed5' class='form-control' $_2d1febcf190a>
            </td>
            </tr>
            ";

        }
    }

    $_23e8d6ad432c.=<<<EOF
      <tr>
      <td colspan='2'>
                <div class='btn-group  btn-group-justified' role='group' aria-label='...'>
                    <div class='btn-group' role='group' aria-label='...''>
                        <input type='button' value='Batal' class='btn btn-default' 
                        onclick="javascript:location='$_1c8ef100f7ab'">
                    </div>
                    <div class='btn-group' role='group' aria-label='...''>
                        <input type='button' value='$_c8cfdbf2cf8a' class='btn btn-default' onclick='begin$_c8cfdbf2cf8a()'>
                    </div>
                </div>
      </td>
      </tr>
      </table>
      </form>
      </div>
EOF;

    $_23e8d6ad432c.=<<<EOF
      
<div id="dlg_$_c8cfdbf2cf8a" style="display: none;" title="Logout" class="fade in">
    <table class="table table-condensed">
        <tr>
            <td width="50px">
                <span style="font-size: 30pt;" class="glyphicon glyphicon-question-sign"></span>
            </td>
            <td>Apakah anda ingin meng$_c8cfdbf2cf8a data ?</td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <div class="btn-group  btn-group-justified" role="group" aria-label="...">
                    <div class="btn-group" role="group" aria-label="...">
                        <input type="button" value="Tidak" class="btn btn-default" onclick="close_dlg()">
                    </div>
                    <div class="btn-group" role="group" aria-label="...">
                        <input type="button" value="Ya" class="btn btn-default" onclick="do$_c8cfdbf2cf8a()">
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
      
      
      <script type="text/javascript">
      
      function begin$_c8cfdbf2cf8a(){
        //alert("apakah ingi meng$_c8cfdbf2cf8a data");
        
        $( "#dlg_$_c8cfdbf2cf8a" ).dialog({
            resizable: false,
            xheight:180,
            width: 350,
            modal: true,
            autoOpen: true,
        });
        
        return false;
      }
      
      function do$_c8cfdbf2cf8a(){
        $("#$_1b09128c4f0b").submit();
      }
      
      function close_dlg(){
        $( "#dlg_$_c8cfdbf2cf8a" ).dialog('close');
      }
      </script>
      
      
EOF;
    return $_23e8d6ad432c;
}

function getAField($_c74b11fc020d,$_db542ae617df,$_06550b4b46ba,$_307e9febe2f7){
    $_ea73b8369107="select $_307e9febe2f7 from $_c74b11fc020d where $_db542ae617df='$_06550b4b46ba' ";
    $_2d7124e7e8fb=mysql_query($_ea73b8369107);

    if(mysql_num_rows($_2d7124e7e8fb)>0){
        return mysql_fetch_array($_2d7124e7e8fb)[0];
    }
    else{
        return null;
    }

}


function gen_jadwal_angsur($_4aabacb153f2){
    $_7e1af29c7850=<<<EOF2
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

    $_ea73b8369107="select * from tbl_angsuran where id_kredit=$_4aabacb153f2 order by tgl_tempo asc";
    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    while($_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb)){
        $_9379af796aa4=$_e3236e163903["byr_ke"];
        $_8531bc83a4c2=$_e3236e163903["tgl_tempo"];
        $_a9c7ddfb8892=$_e3236e163903["pokok"];
        $_bc27b3fd98a4=$_e3236e163903["jasa"];
        $_04e5eaaeab9a=$_a9c7ddfb8892+$_bc27b3fd98a4;
        $_7e1af29c7850.="
        <tr>
        <td>$_9379af796aa4</td>
        <td>$_8531bc83a4c2</td>
        <td>$_a9c7ddfb8892</td>
        <td>$_bc27b3fd98a4</td>
        <td>$_04e5eaaeab9a</td>
        </tr>
        ";
    }

    $_7e1af29c7850.="
    </table>    
    ";

    return $_7e1af29c7850;
}

function gen_anggota_kelompok($_94b32aa94856){
    $_7e1af29c7850=<<<EOF2
        Anggota Kelompok
        <table class="table table-condensed" border=0 width='100%'>
        <tr>
            <td>#</td>
            <td>Nama</td>
        </tr>
EOF2;

    $_ea73b8369107="select * from tbl_anggota where id_kelompok=$_94b32aa94856 order by nama asc";
    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_d181e8a209b5=1;
    while($_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb)){
        $_feeeebe30233=$_e3236e163903["nama"];
        $_7e1af29c7850.="
        <tr>
        <td>$_d181e8a209b5</td>
        <td>$_feeeebe30233</td>
        </tr>
        ";

        $_d181e8a209b5++;
    }

    $_7e1af29c7850.="
    </table>    
    ";

    return $_7e1af29c7850;
}


function angsuran_sudah_lunas_ke($_75030b20cfda){
    $_4189efcd2f32="
    select * from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=1
    order by tgl_tempo asc
    limit 1
    ";
    $_c4b737d7d03d=mysql_query($_4189efcd2f32) or die(mysal_error());

    $_3e5ab422ff7f=mysql_fetch_assoc($_c4b737d7d03d);

    $_7e0a8078b784=$_3e5ab422ff7f["byr_ke"];

    return $_7e0a8078b784;

}

function angsuran_mau_lunas_ke($_75030b20cfda){
    $_4189efcd2f32="
    select * from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=0
    order by tgl_tempo asc
    limit 1
    ";
    $_c4b737d7d03d=mysql_query($_4189efcd2f32) or die(mysal_error());

    $_3e5ab422ff7f=mysql_fetch_assoc($_c4b737d7d03d);

    $_7e0a8078b784=$_3e5ab422ff7f["byr_ke"];
    return $_7e0a8078b784;

}

function total_sisa_pokok_angsur($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=0";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["totalpokok"];

}

function total_sisa_jasa_angsur($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=0";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["totaljasa"];

}

function total_pokok_angsur($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=1";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["totalpokok"];

}

function total_jasa_angsur($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as totalpokok, sum(jasa) as totaljasa
    from tbl_angsuran where id_kredit='$_75030b20cfda' and lunas=1";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["totaljasa"];

}

function total_tunggak_pokok($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as tunggak_pokok, sum(jasa) as tunggak_jasa 
    from tbl_angsuran where id_kredit=$_75030b20cfda and lunas=0 and tgl_tempo<now()";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["tunggak_pokok"];

}

function total_tunggak_jasa($_75030b20cfda){
    $_ea73b8369107="select sum(pokok) as tunggak_pokok, sum(jasa) as tunggak_jasa 
    from tbl_angsuran where id_kredit=$_75030b20cfda and lunas=0 and tgl_tempo<now()";   

    $_2d7124e7e8fb=mysql_query($_ea73b8369107) or die(mysql_error());

    $_e3236e163903=mysql_fetch_assoc($_2d7124e7e8fb);

    return $_e3236e163903["tunggak_jasa"];

}

?>
