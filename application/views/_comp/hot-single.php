<div id="cnt-hottable" style="display: none">
    <form enctype="multipart/form-data" style="height:1px;width:1px;" id="<<parent>>-hot-formUploadFile">
        <input type="file" id="<<parent>>-hot-dataImport" style="height:1px;width:1px;" name='dataImport'/>
    </form>
    <div class="hot-toolbar pull-right">
        <div class="form-inline" action="#">
            <button class="form-control btn btn-default" id="<<parent>>-btn-hot-goto-last"
                    title='Pergi ke paling bawah'>
                <span class="glyphicon glyphicon-menu-down"></span>
            </button>
            <button name="load" class="btn btn-default form-control" id='<<parent>>-load-htable'
                    title='Muat ulang data tabel'><span
                    class="fa fa-refresh" id="<<parent>>-load-htable-icon"></span></button>
            <button name="excel" class="btn btn-default form-control" id='<<parent>>-import-htable' title='Import Json'><span
                    class="glyphicon glyphicon-import"></span></button>
            <button name="excel" class="btn btn-default form-control" id='<<parent>>-export-htable' title='Export data'><span
                    class="glyphicon glyphicon-export"></span></button>

            <div class="input-group ">
                <input id="<<parent>>-search_field" type="search" placeholder="Cari" class="form-control "/>
                <span class="input-group-btn">
                <button class="btn btn-default form-control" type="button" id="<<parent>>-clear-search"
                        title='Bersihkan pencarian'>
                    <span class="glyphicon glyphicon-remove-circle"></span>
                </button>
                </span>
            </div>
        </div>
    </div>
    <div id="<<parent>>-htable"
         style="overflow: hidden; height:300px; width: 100%; border: 1px solid #aaaadd; display: block"></div>
</div>

<script>
    //class hot table
    function HotSingle(divId, params, page) {
        var myData;
        var _SELF = this;
        this.dataLastLoaded = null;
        this.hot = null;
        this.hot_options = params;
        this.page = page;
        //del rev
        //var isset_custom_toolbar=stringToBoolean('<?php echo isset($custom_hot_toolbar)?>');
        //common function


        this.exportAsJson = function () {
            var json = JSON.stringify(dataLastLoaded);

            var blob = new Blob([json], {
                type: "application/json"
            });
            var url = URL.createObjectURL(blob);

            var a = document.createElement('a');
            a.download = page + ".json";
            a.href = url;
            a.textContent = "Download Json backup";
            a.click();
        }


        this.cancelAltKey = function (e) {
            if (e.altKey) {
                if (Handsontable.Dom.stopImmediatePropagation && $.isFunction(Handsontable.Dom.stopImmediatePropagation)) {
                    Handsontable.Dom.stopImmediatePropagation(e);
                }
            }
        }

        this.exportData = function () {
            return false;
        }

        this.importFromJson = function () {
            if ($("#" + divId + "-hot-dataImport").val() == null || $("#" + divId + "-hot-dataImport").val() == "") return;

            var fd = new FormData($("#" + divId + "-hot-formUploadFile")[0]);
            _SELF.startNetWait();
            $.ajax({
                url: 'api/' + _SELF.page + '/importJsonBackup',
                type: 'POST',
                data: fd,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    _SELF.stopNetWait();
                    $("#" + divId + "-hot-dataImport").val("");
//                    debugger;
                    if (res.result == 'ok') {
                        flashMessage("Import data berhasil", "success");
                        _SELF.loadData2();
                    } else {
                        flashMessage("Gagal", "error");
                    }
                },
                error: function () {
                    $("#" + divId + "-hot-dataImport").val("");
                    _SELF.stopNetWait();
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
        }

        this.loadData2 = function () {
            $("#" + divId + "-search_field").val('');
            // debugger;
            _SELF.loadData(
                _SELF.callback_success,
                _SELF.callback_error,
                null,
                this.custom_func_load
            );
        }

        this.build = function (on_before_build_table, on_after_build_table) {
            $.extend(this.hot_options, hot_common_options);
            //            debugger;

            if (on_before_build_table !== undefined) {
                on_before_build_table(this.hot_options);
            }


            var s1 = $("#cnt-hottable").html();
            s1 = s1.replace(/<<parent>>/g, divId);

            $("#" + divId).html(s1);
            $("#" + divId + "-htable").handsontable(this.hot_options);
            this.hot = $("#" + divId + "-htable").handsontable("getInstance");

            this.hot.addHook('beforeKeyDown', this.cancelAltKey);

            $("#" + divId + "-load-htable").on("click", function () {
                _SELF.loadData2();
            });

            $("#" + divId + "-export-htable").on("click", function () {
                _SELF.exportData();
            });

            $("#" + divId + "-import-htable").on("click", function () {
//                document.getElementById(divId + '-hot-dataImport').click();
                $("#" + divId + "-hot-dataImport")[0].click();
//                var aa = $("#" + divId + "-hot-dataImport");
//                debugger;
            });

            $("#" + divId + "-hot-dataImport").change(function () {
                _SELF.importFromJson();
            });

            //set toolbar
            if (this.hot_options.btnExport == false) {
                $("#" + divId + "-export-htable").addClass("hidden");
            }

            $("#" + divId + "-clear-search").on("click", function () {
                $("#" + divId + "-search_field").val('');
                _SELF.handleTableFilter('');
            });

            var executeHotFilterTimeout;

            $("#" + divId + "-search_field").on('keyup', function (event) {
                if (executeHotFilterTimeout) clearTimeout(executeHotFilterTimeout);
                var that = this;
                executeHotFilterTimeout = setTimeout(function () {
                    clearTimeout(executeHotFilterTimeout);
                    _SELF.handleTableFilter(that.value);
                }, 1000);

            });

            $("#" + divId + "-btn-hot-goto-last").on("click", function () {
                var max_rows = $("#" + divId + "-htable").handsontable('countRows');
                $("#" + divId + "-htable").handsontable('selectCell', max_rows - 1, 0, max_rows - 1, 0, scrollToSelection = true);
            });

            if (on_after_build_table !== undefined) {
                on_after_build_table(this.hot);
            }
        }

        this.on_after_change = function (change, source) {
            //common
            //skip jika dari event sendiri
            if (source === 'loadData' || source == 'self') {
                return;
            }

            //common
            //jika perubahan single
            if (change.length == 1) {
                var s1 = castStringEmpty(change[0][2]);
                var s2 = castStringEmpty(change[0][3]);

                //skip jika perubahan sama
                if (s1 == s2) return;
            }

            //transform info
            //containInsertNew=true jika ada row dengan id kosong, yang nantinya akan dproses query insert
            var transform_info = {
                containInsertNew: false,
            };

            //mengubah format cell menajdi rows sblm di kirim ke backend
            var transformed2rows = hot_transform_change_cell2row(this.hot, change, transform_info);

            /**
             * progress
             */
            for (var k = 0; k < transformed2rows.length; k++) {
                //hot.setDataAtCell(transformed2rows[k].cellRow,0,"<img src='images/loading-small.gif'>","self");
                this.hot.setCellMeta(transformed2rows[k].cellRow, 0, 'processing', '1');
                this.hot.render();
            }


            //jika ad data yang perlu di sql insert, block hot supaya tidak bisa entry
            if (transform_info.containInsertNew) {
                this.hot.deselectCell();
                //                blockDiv("#htable", "", true);
            }

            //common
            //send ajax to ds
            var r = Math.random();
            this.startNetWait();
            $.ajax({
                //                url: "?page=" + this.page + "&ds=1&f=save&r=" + r,
                url: "api/" + _SELF.page + "/save?r=" + r,
                dataType: 'json',
                type: 'POST',
                data: {
                    changes: JSON.stringify(transformed2rows)
                }, // contains changed cells data
                success: function (response) {
                    //                    blockDiv("#htable", "", false);
                    _SELF.stopNetWait();

                    if (response.result == 'ok') {
                        flashMessage("Berhasil disimpan", "success");
                    } else {
                        if (response.msg.toLowerCase().indexOf('duplicate') > -1) {
                            flashMessage("Gagal: duplikat data", "warning");
                        } else {
                            flashMessage(response.msg, "warning");
                        }
                    }

                    setTimeout(function () {
                        _SELF.loadData(
                            _SELF.callback_success,
                            _SELF.callback_error,
                            function () {
                                var v1 = $("#" + divId + "-search_field").val();
                                _SELF.handleTableFilter('' + v1);
                            });

                    }, 2000);

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    _SELF.stopNetWait();
                    flashMessage("Gagal mengirim data", "error");
                }
            });
        }

        /**
         * hot filter processing, value get from search textfield
         */
        this.handleTableFilter = function (val) {
            console.log(val);
            var strAllSearch = ('' + val).toLowerCase(),
                row, col, r_len, c_len, td;
            var data = myData;
            var searcharray = [];
            if (strAllSearch) {

                for (row = 0, r_len = data.length; row < r_len; row++) {
                    var allRowData = "";
                    for (col = 0, c_len = data[row].length; col < c_len; col++) {
                        if (data[row][col] == null) {
                            continue;
                        }
                        allRowData = allRowData + "_+*" + data[row][col];
                    }

                    var srcLevelOr = strAllSearch.split("|");
                    for (var i = 0; i < srcLevelOr.length; i++) {
                        var strLevelAnd = srcLevelOr[i].split(",");
                        var cek = "";
                        for (var j = 0; j < strLevelAnd.length; j++) {
                            var strSingleWord = strLevelAnd[j];
                            if (allRowData.toLowerCase().indexOf(strSingleWord.toLowerCase()) > -1) {
                                cek += "1";
                            } else {
                                cek += "0";
                            }
                        }
                        if (cek.indexOf("0") == -1) {
                            // debugger;
                            searcharray.push(data[row]);
                            break;
                        }
                    }
                }
                _SELF.hot.loadData(searcharray);
                //                hot.loadData(searcharray);
            } else {
                _SELF.hot.loadData(myData);
            }
        }

        this.on_before_remove_row = function (index, amount) {
            var data = [];
            for (i = 0; i < amount; i++) {
                rowID = _SELF.hot.getDataAtCell(index + i, 0);
                data[i] = rowID;
            }

            BootstrapDialog.confirm({
                title: 'Peringatan',
                message: 'Apakah ingin menghapus data sebanyak ' + data.length + '?',
                type: BootstrapDialog.TYPE_WARNING, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
                closable: true, // <-- Default value is false
                draggable: true, // <-- Default value is false
                btnCancelLabel: 'Batal', // <-- Default value is 'Cancel',
                btnOKLabel: 'Hapus', // <-- Default value is 'OK',
                btnOKClass: 'btn-warning', // <-- If you didn't specify it, dialog type will be used,
                callback: function (result) {
                    // result will be true if button was click, while it will be false if users close the dialog directly.
                    if (result) {
                        flashMessage("Menghapus...", "info");
                        _SELF.startNetWait();
                        $.ajax({
                            //                            url: '?page=' + _SELF.page + '&ds=1&f=delete',
                            url: 'api/' + _SELF.page + '/delete?r=' + Math.random(),
                            data: {
                                'ids': data
                            }, // contains changed cells' data
                            dataType: 'json',
                            type: 'POST',
                            success: function (res) {
                                _SELF.stopNetWait();
                                if (res.result == 'ok') {
                                    flashMessage("Berhasil", "success");

                                    _SELF.loadData(
                                        _SELF.callback_success,
                                        _SELF.callback_error,
                                        function () {
                                            var v1 = $("#" + divId + "-search_field").val();
                                            _SELF.handleTableFilter('' + v1);
                                        });
                                } else {
                                    flashMessage("Gagal", "error");
                                }
                            },
                            error: function () {
                                _SELF.stopNetWait();
                                flashMessage("Gagal mengirim data", "error");
                            }
                        });
                    } else {
                    }
                }
            });
        }

        var origDocTitle = "";
        var cloudChar = '\u2601';
        this.startNetWait = function () {
            $("#" + divId + "-load-htable-icon").addClass('fa-spin');
            origDocTitle = document.title;
            if (document.title.indexOf(cloudChar) == -1)
                document.title = cloudChar + " " + document.title;
        }

        this.stopNetWait = function () {
            document.title = origDocTitle;
            $("#" + divId + "-load-htable-icon").removeClass('fa-spin');
        }

        this.callback_success;
        this.callback_error;
        this.custom_func_load;
        this.loadData = function (callback_success_, callback_error_, callback_final, custom_func_load) {
            // $("#" + divId + "-load-htable-icon").addClass('fa-spin');
            this.startNetWait();

            this.callback_success = callback_success_;
            this.callback_error = callback_error_;
            var r = Math.random();

            var f = "load";
            if (custom_func_load != null) f = custom_func_load;
            this.custom_func_load = custom_func_load;
            var moreGetParam = "&";
            // debugger;
            var idxMoreGet = f.indexOf("moreGetParam:");
            if (idxMoreGet > -1) {
                moreGetParam = moreGetParam + f.substring(idxMoreGet + 10);
            }

            $.ajax({
                url: 'api/' + this.page + '/all?r=' + r + moreGetParam,
                dataType: 'json',
                type: 'GET',
                success: function (res) {
                    // $("#" + divId + "-load-htable-icon").removeClass('fa-spin');
                    _SELF.stopNetWait();

                    if (res.result == "ok") {
                        var data = [];
                        if (callback_success_ != null && typeof callback_success_ === 'function') {
                            data = callback_success_(res);
                        }
                        myData = data;
                        _SELF.hot.loadData(data);
                    } else {
                        flashMessage("error: " + res.msg, "error");
                    }

                    //                    debugger;
                    //eksekusi callback tambahn, untuk internal template
                    if (callback_final != null && typeof callback_final === 'function') {
                        callback_final();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    _SELF.stopNetWait();

                    if (callback_error_ != null && typeof callback_error_ === 'function') {
                        callback_error_(textStatus, errorThrown);
                    }
                }
            });
        }

    }
</script>