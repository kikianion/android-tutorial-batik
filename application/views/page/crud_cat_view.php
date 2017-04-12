<?php

namespace aneon\fw\page;

//setting
$pageParams = array(
    'title' => "Kategori Artikel",
    'menuGroup' => "Master",
    'ds' => "crud_cat",
    'order' => 1,
);

//init
$pageInfo = array(
    'filename' => basename(__FILE__, '.php'),
        )
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php ?>
        <small></small>
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <?php
    $this->load->view('_comp/hot-single');
    ?>

    <div id="hottable"></div>

    <script type="text/javascript">
        var phpfilename = '<?php echo $pageInfo['filename'] ?>';
        var ds = '<?php echo $pageParams['ds'] ?>';

        var _SELF = {};
        (function (_SELF) {
            //init
            _SELF.hot = null;

            var hot_options = {
                colHeaders: ['id', 'Nama', 'Urut', 'created'],
                colWidths: [50, 300, 100, 200, 200, 200,
                    100, 100, 100, 100, 100
                ],
                columns: [
                    {
                        renderer: htmlRenderer1,
                        nobreak: 1,
                        readOnly: 1,
                    }, {
                        renderer: htmlRenderer1,
                        nobreak: 1,
                    }, {
                        renderer: htmlRenderer1,
                        nobreak: 1,
                    }, {
                        renderer: htmlRenderer1,
                        nobreak: 1,
                        readOnly: 1,
                    }, ],
                beforeRemoveRow: function (index, amount) {
                    _SELF.hot.on_before_remove_row(index, amount);
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
                        res.data = hotDataSortBy(res.data, "id");

                        for (var i = 0, ilen = res.data.length; i < ilen; i++) {
                            row = [];
                            row[0] = parseInt(res.data[i].id);
                            row[1] = res.data[i].name;
                            row[2] = res.data[i].keyword;
                            row[3] = res.data[i].startFind;
                            row[4] = res.data[i].endFind;
                            row[5] = UTCtoJakarta(res.data[i].created);

                            data[i] = row;
                        }
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
            if (_SELF.hot)
                _SELF.hot.hot.render();

            $(window).resize(function () {
                layout_start();
            });
        }
    </script>
    <!-- Your Page Content Here -->

</section>
<!-- /.content -->