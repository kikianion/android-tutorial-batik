<?php

namespace aneon\fw\page;

//setting
$pageParams = array(
    'title' => "Artikel",
    'menuGroup' => "Master",
    'ds' => "crud_artic",
    'order' => 2,
);

//init
$pageInfo = array(
    'filename' => basename(__FILE__, '.php'),
);

function load_cat()
{
//    $this->load->database();
    $CI =& get_instance();
    $CI->load->model('Crud_cat_model');
    $rs = $CI->Crud_cat_model->allObj("order by order_");

    $data = array();
    foreach ($rs as $row) {
        $data[] = array(
            "id" => $row->id,
            "text" => $row->name,
        );
    }
    return $data;
}

$master_cat = load_cat();

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

        var master_cat =<?php echo json_encode($master_cat)?>;

        var catArticleRenderer = function (instance, td, row, col, prop, value, cellProperties) {

            var a = alasql("select text from ? where id='" + value + "'", [master_cat])
            if (a.length > 0) {
                value = a[0].text;
            }
            td.innerHTML = value;

            if (cellProperties.textColor && DEBUG_DETAIL == 0) {
                td.style.color = cellProperties.textColor;
            }

            if (cellProperties.isError) {
                td.style.backgroundColor = "#f00";
            } else if (cellProperties.readOnly) {
                td.style.backgroundColor = "#ddd";
            }

            if (col == 0) {
                if (cellProperties.processing) {
                    td.innerHTML = "<img src='assets/images/loading-small.gif'>";
                } else if (cellProperties.maskId) {
                    if (!isNaN(parseInt(value))) td.innerHTML = "<i class='fa fa-save'></i>";
                }
            }
            // debugger;
            if (cellProperties.nobreak == 1) {
                td.innerHTML = "<div style='white-space:nowrap'>" + td.innerHTML + "</div>";
            }

            if (!value) td.innerHTML = '';

        };


        var _SELF = {};
        (function (_SELF) {
            //init
            _SELF.hot = null;

            var hot_options = {
                colHeaders: ['id', 'Judul', 'Isi', 'Urut', 'Kategori'],
                colWidths: [50, 200, 400, 100, 100, 200,
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
                        editor: "tinymce",
                    }, {
                        renderer: htmlRenderer1,
                        nobreak: 1,
                    }, {
                        renderer: catArticleRenderer,
                        nobreak: 1,
                        editor: "select2",
                        select2Options: {
                            data: master_cat,
                            dropdownAutoWidth: true,
                            allowClear: true,
                            width: 'resolve',
                        },

                    },],
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
                        row[1] = res.data[i].title;
                        row[2] = res.data[i].cnt;
                        row[3] = res.data[i].order_;
                        row[4] = res.data[i].cat;
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