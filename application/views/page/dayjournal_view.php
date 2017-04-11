<?php
namespace aneon\fw\page;

//setting
$pageParams = array(
    'title' => "Jurnal Transaksi Harian",
    'menuGroup' => "Analisis",
    'ds' => "dayjournal",
    'order' => 2,
);

//init
$pageInfo = array(
    'filename' => basename(__FILE__, '.php'),
)
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo $pageParams['title'] ?>
        <small>
            Dari <input type="text" id="fromDaysBefore" value="1">&nbsp;hyl hingga
            <input type="text" id="fromDaysAfter">&nbsp;hyl
            <button onclick="prosesData()">Proses</button>
        </small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <?php
    $this->load->view('_comp/hot-single');
    ?>

    <div id="hottable"></div>
    <script type="text/javascript">
        var ds = '<?php echo $pageParams['ds']?>';

        var _SELF = {};

        function prosesData() {
            daysBefore = parseInt($("#fromDaysBefore").val());
            if (isNaN(daysBefore)) {
                daysBefore = 1;
                $("#fromDaysBefore").val(daysBefore);
                flashMessage("masukkan angka dengan benar");
            } else {
                // debugger;
                _SELF.hot.custom_func_load = "moreGetParam:&daysBefore=" + daysBefore;
                _SELF.hot.loadData2();
            }
        }

        var daysBefore = 1;
        (function (_SELF) {
            //init
            _SELF.hot = null;

            var hot_options = {
                colHeaders: ['created', 'device id', 'from', 'prodname', 'beli', 'terhitung', 'stok asli', 'selisih', 'trxhappen'],
                colWidths: [220, 100, 100, 80, 60, 60, 60, 60, 800, 100,
                    100, 100, 100, 100, 100
                ],
                columns: [{
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,
                }, {
                    renderer: htmlRenderer1,
                    //                        backColor: "#ddd",
                    //                        maskId: "*",
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: lastStockRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: diffStockRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                },],
                beforeRemoveRow: function (index, amount) {
                    //todo
//                    _SELF.hot.on_before_remove_row(index, amount);
                    return false;
                },
                afterCreateRow: function (index, amount) {
                },
                afterChange: function (change, source) {
                    _SELF.hot.on_after_change(change, source);
                }
            }

            _SELF.hot = new HotSingle("hottable", hot_options, ds);

            _SELF.hot.exportData = function () {
                //TODO
                var a = [
                    ['#', 'No Induk', 'Nama', 'Username', 'Alamat', 'Telp']
                ]

                var d = _SELF.hot.hot.getData();

                for (var i = 0; i < d.length; i++) {
                    var o = d[i];
                    a.push([(i + 1), o[3], o[4], o[5], o[7], o[8]]);
                }
                export_excel(a);
            }
            _SELF.hot.build(
                function (hot_options) {
                    hot_options.minSpareRows = 1;
                },
                function (hot) {
                    hot.updateSettings({
                        cells: function (row, col, prop) {
                            var hot = self.hot;
                            var cellProperties = {};
                            return cellProperties;
                        }
                    })

                }
            );
            _SELF.hot.loadData(
                function (res) {
                    var data = [],
                        row;
                    res.data = hotDataSortBy(res.data, "created");


                    //buang duplikat 100%
                    for (var i = 0; i < res.data.length; i++) {
                        var currentItem = res.data[i].trxhappen;
                        for (var j = 0; j < res.data.length; j++) {
                            var toCekItem = res.data[j].trxhappen;
                            // console.log("1-" + currentItem);
                            // console.log("2-" + toCekItem);
                            if (currentItem.indexOf("8928431053") > -1 && toCekItem.indexOf("8928431053") > -1) {
                                // debugger;
                            }

                            if (i != j && currentItem == toCekItem) {
                                res.data.splice(j, 1);
                                j--;
                            }
                        }

                    }

                    var lastStok = -1;
                    for (var i = 0, ilen = res.data.length; i < ilen; i++) {

                        var start1 = res.data[i].startFindStock;
                        var startArr = start1.split("|");
                        var end1 = res.data[i].endFindStock;
                        var endArr = end1.split("|");

                        // debugger;
                        // if (res.data[i].trxhappen.indexOf("Stok anda telah ditambahkan") > -1) {
                        //     debugger;
                        // }

                        var idx1 = -1;
                        var lastLenStart = 0;
                        for (var j = 0; j < startArr.length; j++) {
                            var strCek1 = startArr[j];
                            strCek1 = strCek1.replace(/\{space\}/g, " ");
                            strCek1 = strCek1.replace(/\{coma\}/g, ",");
                            idx1 = res.data[i].trxhappen.toLowerCase().indexOf(strCek1.toLowerCase());
                            if (idx1 > -1) {
                                lastLenStart = strCek1.length;
                                break;
                            }
                        }
                        var idx2 = -1;
                        var lastLenEnd = 0;
                        for (var j = 0; j < endArr.length; j++) {
                            var strCek1 = endArr[j];
                            strCek1 = strCek1.replace(/\{space\}/g, " ");
                            strCek1 = strCek1.replace(/\{coma\}/g, ",");
                            idx2 = res.data[i].trxhappen.toLowerCase().indexOf(strCek1.toLowerCase(), idx1 + lastLenStart);
                            if (idx2 > -1) {
                                lastLenEnd = strCek1.length;
                                break;
                            }
                        }

                        var stockLast = res.data[i].trxhappen.substring(idx1 + lastLenStart, idx2);
                        // console.log(stockLast);
                        stockLast = stockLast.replace(/,/g, "");
                        row = [];
                        //row[0] = parseInt(res.data[i].___id);
                        row[0] = UTCtoJakarta(res.data[i].created);
                        row[1] = res.data[i].deviceId;
                        row[2] = res.data[i].from;
                        row[3] = res.data[i].prodName;
                        row[4] = res.data[i].buy;
                        row[5] = "xxx";
                        row[6] = stockLast;
                        row[7] = "xxx";
                        row[8] = res.data[i].trxhappen;

                        data[i] = row;
                    }
                    dataLastLoaded = res.data;
                    if (data.length == 0)
                        flashMessage("data kosong");

                    // data = alasql("select [0],[1],[2],[3],[4],[5],[6],[7],[distinct 8] from ? ", [data]);
                    return data;

                },
                function (textStatus, errorThrown) {
                    flashMessage(textStatus + ": " + errorThrown);
                },
                function () {
                    $("#hottable-btn-hot-goto-last").click();
                },
                "moreGetParam:&daysBefore=" + daysBefore
            );

        })(_SELF);

        function layout_start() {
            var clrBtn = $("#hottable-clear-search")[0];
            if (!clrBtn) {
                return;
            }
            var bt1 = clrBtn.getBoundingClientRect().bottom;
            var top1 = $(window).innerHeight();
            var vb1 = top1 - bt1 - 40;
            //            $("#hottable").css('height', vb1 + 50);
            $("#hottable-htable").css('height', vb1);
            if (_SELF.hot) _SELF.hot.hot.render();

            $(window).resize(function () {
                layout_start();
            });
        }
    </script>
    <!-- Your Page Content Here -->
</section>
<!-- /.content -->
