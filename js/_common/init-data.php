<script type="text/javascript">
    if (typeof KeyEvent == "undefined") {
        var KeyEvent = {
            DOM_VK_CANCEL: 3,
            DOM_VK_HELP: 6,
            DOM_VK_BACK_SPACE: 8,
            DOM_VK_TAB: 9,
            DOM_VK_CLEAR: 12,
            DOM_VK_RETURN: 13,
            DOM_VK_ENTER: 14,
            DOM_VK_SHIFT: 16,
            DOM_VK_CONTROL: 17,
            DOM_VK_ALT: 18,
            DOM_VK_PAUSE: 19,
            DOM_VK_CAPS_LOCK: 20,
            DOM_VK_ESCAPE: 27,
            DOM_VK_SPACE: 32,
            DOM_VK_PAGE_UP: 33,
            DOM_VK_PAGE_DOWN: 34,
            DOM_VK_END: 35,
            DOM_VK_HOME: 36,
            DOM_VK_LEFT: 37,
            DOM_VK_UP: 38,
            DOM_VK_RIGHT: 39,
            DOM_VK_DOWN: 40,
            DOM_VK_PRINTSCREEN: 44,
            DOM_VK_INSERT: 45,
            DOM_VK_DELETE: 46,
            DOM_VK_0: 48,
            DOM_VK_1: 49,
            DOM_VK_2: 50,
            DOM_VK_3: 51,
            DOM_VK_4: 52,
            DOM_VK_5: 53,
            DOM_VK_6: 54,
            DOM_VK_7: 55,
            DOM_VK_8: 56,
            DOM_VK_9: 57,
            DOM_VK_SEMICOLON: 59,
            DOM_VK_EQUALS: 61,
            DOM_VK_A: 65,
            DOM_VK_B: 66,
            DOM_VK_C: 67,
            DOM_VK_D: 68,
            DOM_VK_E: 69,
            DOM_VK_F: 70,
            DOM_VK_G: 71,
            DOM_VK_H: 72,
            DOM_VK_I: 73,
            DOM_VK_J: 74,
            DOM_VK_K: 75,
            DOM_VK_L: 76,
            DOM_VK_M: 77,
            DOM_VK_N: 78,
            DOM_VK_O: 79,
            DOM_VK_P: 80,
            DOM_VK_Q: 81,
            DOM_VK_R: 82,
            DOM_VK_S: 83,
            DOM_VK_T: 84,
            DOM_VK_U: 85,
            DOM_VK_V: 86,
            DOM_VK_W: 87,
            DOM_VK_X: 88,
            DOM_VK_Y: 89,
            DOM_VK_Z: 90,
            DOM_VK_CONTEXT_MENU: 93,
            DOM_VK_NUMPAD0: 96,
            DOM_VK_NUMPAD1: 97,
            DOM_VK_NUMPAD2: 98,
            DOM_VK_NUMPAD3: 99,
            DOM_VK_NUMPAD4: 100,
            DOM_VK_NUMPAD5: 101,
            DOM_VK_NUMPAD6: 102,
            DOM_VK_NUMPAD7: 103,
            DOM_VK_NUMPAD8: 104,
            DOM_VK_NUMPAD9: 105,
            DOM_VK_MULTIPLY: 106,
            DOM_VK_ADD: 107,
            DOM_VK_SEPARATOR: 108,
            DOM_VK_SUBTRACT: 109,
            DOM_VK_DECIMAL: 110,
            DOM_VK_DIVIDE: 111,
            DOM_VK_F1: 112,
            DOM_VK_F2: 113,
            DOM_VK_F3: 114,
            DOM_VK_F4: 115,
            DOM_VK_F5: 116,
            DOM_VK_F6: 117,
            DOM_VK_F7: 118,
            DOM_VK_F8: 119,
            DOM_VK_F9: 120,
            DOM_VK_F10: 121,
            DOM_VK_F11: 122,
            DOM_VK_F12: 123,
            DOM_VK_F13: 124,
            DOM_VK_F14: 125,
            DOM_VK_F15: 126,
            DOM_VK_F16: 127,
            DOM_VK_F17: 128,
            DOM_VK_F18: 129,
            DOM_VK_F19: 130,
            DOM_VK_F20: 131,
            DOM_VK_F21: 132,
            DOM_VK_F22: 133,
            DOM_VK_F23: 134,
            DOM_VK_F24: 135,
            DOM_VK_NUM_LOCK: 144,
            DOM_VK_SCROLL_LOCK: 145,
            DOM_VK_COMMA: 188,
            DOM_VK_PERIOD: 190,
            DOM_VK_SLASH: 191,
            DOM_VK_BACK_QUOTE: 192,
            DOM_VK_OPEN_BRACKET: 219,
            DOM_VK_BACK_SLASH: 220,
            DOM_VK_CLOSE_BRACKET: 221,
            DOM_VK_QUOTE: 222,
            DOM_VK_META: 224
        };
    };


    //------------------------------------- init

    $(function(){
        require.config({
            text: 'text.js',
            paths: {
                JSZip: 'libs//require.js-2.1.11/jszip'
            },
            shim: {
                'JSZip': {
                    exports: 'JSZip'
                }
            }
        });


        Highcharts.setOptions({
            lang: {
                thousandsSep: '.',
                decimalPoint: ',',
            }
        });

        moment.locale('id');
        act_tooltipster();
    });
    //------------------------------------- init-end


</script>

<?php
include "common/_dlg_logout.php";
include "common/_dlg_chpass.php";


define("INCLUDING_AS_LIB",1,true);
include "common/ds/common-ds.php";

$master_data=get_master_data();

?>

<script type="text/javascript">

    $("#top-loading-anim").hide();

    /**
    * build referensi master data cache
    */
    var master_kegiatan=<?php echo json_encode($master_data["master_kegiatan"])?>;
    var master_program= <?php echo json_encode($master_data["master_program"] )?>;
    var master_bidang=  <?php echo json_encode($master_data["master_bidang"]  )?>;
    var master_urusan=  <?php echo json_encode($master_data["master_urusan"]  )?>;
    var master_skpd=    <?php echo json_encode($master_data["master_skpd"]    )?>;
    var master_bmitra=  <?php echo json_encode($master_data["master_bmitra"]  )?>;


    function sync_master(){
        ds='common-ds';
        var f='get_master_data';
        showTopLoading(true);
        $.ajax({
            url: 'index.php?mod='+ds+'&f='+f+'&ajax=1',
            dataType: 'json',
            type: 'GET',
            success: function (response) {
                showTopLoading(false);

                if(response.result=='ok'){
                    //debugger;
                    master_kegiatan=response.master_kegiatan;
                    master_program=response.master_program;
                    master_bidang=response.master_bidang;
                    master_urusan=response.master_urusan;
                }
                else{
                    //error occured
                }
            }
        });

    }

    //user level to js
    var user_level='<?php echo $_SESSION["login"]["level"]?>';
    var username='<?php echo $_SESSION["login"]["user"]?>';
    var tahun='<?php echo $_SESSION['tahun'] ?>';

</script>

<script type="text/javascript" src="common/keyboard-hook.js"></script>
<style type="text/css">

    .width100{
        width: 200px;
    }
</style>

<script type="text/javascript">
    /**
    * definis js cek validasi
    */
    var str_tidak_terdefinisi='<?php echo $str_tidak_terdefinisi?>';

    var cek_nama_error=[];
    cek_nama_error.push('<?php echo $cek_nama_error[0]?>');
    /**
    * index 1 tidak usah karena untuk holder OK
    */
    cek_nama_error.push('<?php echo $cek_nama_error[1]?>');
    cek_nama_error.push('<?php echo $cek_nama_error[2]?>');

    var cek_berhak_error='<?php echo $cek_berhak_error[0]?>';

</script>


