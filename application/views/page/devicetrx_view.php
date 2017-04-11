<?php
namespace aneon\fw\page;

//setting
$pageParams = array(
    'title' => "Semua Transaksi",
    'menuGroup' => "Log",
    'ds' => "devicetrx_log",
    'order'=>2,
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
        </small>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <?php
    $this->load->view('_comp/hot-single');
    ?>

    <div id="hottable"></div>
    <script>
        var dataLastLoaded;

        function exportData() {
            var json = JSON.stringify(dataLastLoaded);

            var blob = new Blob([json], {
                type: "application/json"
            });
            var url = URL.createObjectURL(blob);

            var a = document.createElement('a');
            a.download = "smsin.json";
            a.href = url;
            a.textContent = "Download backup.json";
            a.click();
        }

        $("#dataImport").change(function () {
            if ($("#dataImport").val() == null || $("#dataImport").val() == "") return;
            //alert($(this).val());
            var fd = new FormData($("#formUploadFile")[0]);
            _SELF.hot.startNetWait();
            $.ajax({
                // Your server script to process the upload
                url: '/api/SmsIn/importData',
                type: 'POST',

                // Form data
                data: fd,

                // Tell jQuery not to process data or worry about content-type
                // You *must* include these options!
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    _SELF.hot.stopNetWait();
                    if (res.result == 'ok') {
                        flashMessage("Import data berhasil", "success");
                        _SELF.hot.loadData2();
                    } else {
                        flashMessage("Gagal", "error");
                    }
                },
                error: function () {
                    _SELF.hot.stopNetWait();
                    flashMessage("Gagal mengirim data", "error");
                },

                // Custom XMLHttpRequest
                xhr: function () {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {
                                $('progress').attr({
                                    value: e.loaded,
                                    max: e.total,
                                });
                            }
                        }, false);
                    }
                    return myXhr;
                },
            });

        });

        function importData() {
            document.getElementById('dataImport').click();

        }
    </script>

    <script type="text/javascript">
        var phpfilename = '<?php echo $pageInfo['filename']?>';
        var ds = '<?php echo $pageParams['ds']?>';

        var _SELF = {};
        (function (_SELF) {
            //init
            _SELF.hot = null;

            var hot_options = {
                colHeaders: ['#', 'guid', 'created', 'modified', 'localcreated', 'localmodified',
                    'prodname', 'target', 'trxstate', 'answer', 'aliastag1',
                    'aliastag2', 'nominal', 'prefix', 'ussdtrx', 'regexanswer',
                    'simslot', 'deviceid', 'activeuser'
                ],
                colWidths: [1, 1, 200, 200, 1, 1,
                    80, 100, 80, 120, 50,
                    50, 40, 100, 100, 100,
                    30, 150, 100, 100, 100
                ],
                columns: [{
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    //                        backColor: "#ddd",
                    //                        maskId: "*",
                    nobreak: 1,
                    readOnly: 1,
                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,
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
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,
                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,
                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,
                }, {
                    renderer: htmlRenderer1,
                    readOnly: 1,
                    nobreak: 1,
                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                }, {
                    renderer: htmlRenderer1,
                    nobreak: 1,
                    readOnly: 1,

                },

                ],
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

                    for (var i = 0, ilen = res.data.length; i < ilen; i++) {
                        row = [];
                        row[0] = parseInt(res.data[i].___id);
                        row[1] = res.data[i].guid;
                        row[2] = UTCtoJakarta(res.data[i].created);
                        row[3] = UTCtoJakarta(res.data[i].modified);
                        row[4] = UTCtoJakarta(res.data[i].localCreated);
                        row[5] = UTCtoJakarta(res.data[i].localModified);
                        row[6] = res.data[i].prodName;
                        row[7] = res.data[i].target;
                        row[8] = res.data[i].trxState;
                        row[9] = res.data[i].answer;
                        row[10] = res.data[i].aliasTag1;
                        row[11] = res.data[i].aliasTag2;
                        row[12] = res.data[i].nominal;
                        row[13] = res.data[i].prefix;
                        row[14] = res.data[i].ussdTrx;
                        row[15] = res.data[i].regexAnswer;
                        row[16] = res.data[i].simSlot;
                        row[17] = res.data[i].deviceId;
                        row[18] = res.data[i].activeUser;
                        row[19] = res.data[i].synced;

                        data[i] = row;
                    }

                    dataLastLoaded = res.data;
                    return data;

                },
                function (textStatus, errorThrown) {
                    flashMessage(textStatus + ": " + errorThrown);
                },
                function () {
                    $("#hottable-btn-hot-goto-last").click();
                },
                null
            );

        })(_SELF);

        function layout_start() {
            var clrBtn = $("#hottable-clear-search")[0];
            if (!clrBtn) {
                return;
            }
            var bt1 = clrBtn.getBoundingClientRect().bottom;
            var top1 = $(window).innerHeight();
            var vb1 = top1 - bt1 - 35;
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