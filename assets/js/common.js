var DEBUG_DETAIL="0";
var skpd_init_form_mode='entry';
var MAIN_TITLE="Perencanaan & Pembangunan Lamongan";

var dtable_common_language={
    "lengthMenu": "Tampil _MENU_ ",
    "zeroRecords": "Tidak ada data yang ditemukan",
    "info": "Tampil _START_ - _END_ dari _TOTAL_ total",
    "infoEmpty": "Tidak ada record",
    "infoFiltered": "(disaring dari total _MAX_ record)" ,
    "paginate": {
        "previous": "Sebelum",
        "next": "Lanjut",
        "first": "Awal",
        "last": "Akhir",
    },
    "search": "Cari",

} ;

var dtable_common_sDom='Z<"row row_fix1"<"col-sm-9"l<"btn-div">> <"col-sm-3"f>> <"row row_fix1"t> <"row row_fix1"<"col-sm-6"i> <"col-sm-6"p>> ';

var gen_dtable_common_options=function(colMap,namespace){
    var flipped_colMap=array_flip_keyval(colMap);

    var dtable_common_options={
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "iDisplayLength":10,
        stateSave: true,
        "sDom":  dtable_common_sDom,
        "language": dtable_common_language,
        autoWidth: false,
        deferRender: true,
        scrollY:"300px",
        scrollX:true,
        scrollCollapse: true,
        order:[],       
        "rowCallback": function( row, data, index ) {
            var that=this;
            if(namespace){
                return namespace.row_callback(that, row, data, index )        
            }
            else{
                return dtable_common_row_callback(that, row, data, index )        
            }
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var that=this;
            if(namespace){
                return namespace.footer_callback (that, colMap, that, row, data, start, end, display );
            }
            else{
                return dtable_common_footer_callback (that, colMap, that, row, data, start, end, display );
            }
        } ,
        "columnDefs": [
            { //*** "visible": false,  
            },
            {//** set sortable false kolom 
                "sortable": false ,
                "targets": [
                    parseInt(flipped_colMap['id']),
                ], 
            } ,            
            {//*** mask id row
                "render": function ( data, type, row ) {
                    if(type=='display'){
                        return "#"+data+"";
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['num']),
                ]
            },
            {//*** mask id row
                "render": function ( data, type, row ) {
                    if(parseInt(data)>-1 && DEBUG_DETAIL=="0"){
                        return '*';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['id']),
                ]
            },
            {//*** hidden kode jika subkegiatan
                "render": function ( data, type, row ) {
                    if(DEBUG_DETAIL==1){
                        var s=data;
                    }
                    else{
                        if(row.kode_subk>0){
                            var s="<span style='display: none'>"+data+"</span>";
                        }
                        else{
                            var s=data;
                        }
                    }
                    return s;
                },
                "targets": [
                    parseInt(flipped_colMap['u']),
                    parseInt(flipped_colMap['b']),
                    isNaN(parseInt(flipped_colMap['skpd']))?0:parseInt(flipped_colMap['skpd']),
                    parseInt(flipped_colMap['p']),
                    parseInt(flipped_colMap['k']),
                ],
            },
            {// *** sum pagu
                "render": function( data, type, row ) {
                    if(type=='display'){
                        s=formatNumber(intVal(data),0,3,".",'.');
                        return s;
                    }
                    else if(type=='filter' && row.ct_subk>0){
                        return "total "+row.sumpagu;
                    }
                    else{
                        return data;
                    }
                },
                "targets": [                    
                    parseInt(flipped_colMap['pagu']),
                ],
            },
            {//*** sum pagu2
                "render": function( data, type, row ) {
                    if(type=='display'){
                        s=formatNumber(intVal(data),0,3,".",'.');
                        return s;
                    }
                    else if(type=='filter' && row.ct_subk>0){
                        return "total "+row.sumpagu2;
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['pagu2']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display' || type=='filter'){
                        s=formatNumber(intVal(data),0,3,".",'.');
                        return s;
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['total_pagu2']),
                    parseInt(flipped_colMap['total_pagu']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display'){
                        return data+' <button class="act-detail-jenis-k">+</button>';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['jenis_k']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display'){
                        return data+' <button class="act-detail-jenis-u">+</button>';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['jenis_u']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display'){
                        return data+' <button class="act-detail-jenis-b">+</button>';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['jenis_b']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display'){
                        return data+' <button class="act-detail-jenis-p">+</button>';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['jenis_p']),
                ],
            },
            {//*** sum pagu summary
                "render": function( data, type, row ) {
                    if(type=='display'){
                        return data+' <button class="act-detail-jenis-skpd">+</button>';
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['jenis_skpd']),
                ],
            },
            {//*** rp
                "render": function( data, type, row ) {
                    if(type=='display' || type=='filter'){
                        s=formatNumber(intVal(data),0,3,".",'.');
                        return s;
                    }
                    else{
                        return data;
                    }
                },
                "targets": [
                    parseInt(flipped_colMap['apbddes']),
                    parseInt(flipped_colMap['apbdkab']),
                    parseInt(flipped_colMap['apbdprov']),
                    parseInt(flipped_colMap['apbn']),
                    parseInt(flipped_colMap['total']),
                ],
            },
        ],
        "colResize": {
            "tableWidthFixed": false
        },
        /**
        * bentrok text filter tidak bisa di klik
        * 
        * @type Object
        */
        /*colReorder: {
        reorderCallback: function () {
        console.log( 'callback' );
        }
        },*/    

    };
    return dtable_common_options;
}

var hot_common_options={
    columnSorting: true,
    data: [],
    search: true,
    //stretchH: 'last',
    manualColumnResize: true,
    manualColumnFreeze: true,
    manualRowResize: true,
    //persistentState: true,
    rowHeaders: true,
    currentRowClassName: 'currentRow',
    currentColClassName: 'currentCol',
    minSpareColumns: 0,
    minSpareCols: 0,
    minSpareRows: 1,
    //contextMenu: ['remove_row','---------','undo','redo'],
    contextMenu: {
        items:{
            "remove_row":{
                name: "Hapus record"
            } ,
            "hsep2":"---------",
            "freeze_column":{
                name: "Bekukan kolom"

            },        
            "hsep2":"---------",
            "undo":{},
            "redo":{},
        }
    },
};

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

var htmlRenderer1= function(instance, td, row, col, prop, value, cellProperties){
    td.innerHTML = value;

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color = cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    if(col==0){
        if(cellProperties.processing){
            td.innerHTML="<img src='images/loading-small.gif'>";
        }
        else if(cellProperties.maskId){
            //if(!isNaN(parseInt(value))) td.innerHTML=cellProperties.maskId;
            if(!isNaN(parseInt(value))) td.innerHTML="<i class='fa fa-save'></i>";
        }
    }

    if (!value || value === '') {
        if(cellProperties.defaultValue)
        //td.style.background = '#EEE';
        td.innerHTML = cellProperties.defaultValue;
    }

};


var colorRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }


    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    var value1=value+"";
    if(value1 && value1.toLowerCase().indexOf("{{ro}}")>-1) {
        td.style.backgroundColor = "#ddd";
        //cellProperties.readOnly=true;
        //console.log('ro deteceted');
        var newVal1=value.replace('{{ro}}','');
        td.innerHTML=newVal1;    
    }

    if(col==0){
        if(cellProperties.maskId){
            if(value)     td.innerHTML=cellProperties.maskId;
        }
    }

    return td;
};

var colorCheckboxRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.CheckboxRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    return td;
};

var mustFillRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    /*    if(cellProperties.textColor && DEBUG_DETAIL==0){
    td.style.color=cellProperties.textColor;
    }*/

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    var value1=value+"";

    if(cellProperties.warnNewRow){
        var cek1=(value1=='' || value==null);
    }
    else{
        var cek1=(value1=='');
    }
    if(cek1) {
        if(cellProperties.textColor){
            td.style.color = cellProperties.textColor;
        }
        else{
            td.style.color = "#f00";
        }
        td.style.textAlign= "center";
        if(cellProperties.textWarning){
            td.innerHTML=cellProperties.textWarning;    
        }
        else{
            td.innerHTML="[kosong, harus di isi]";    
        }
    }

    return td;
};

var numericColorRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.NumericRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    if(cellProperties.prefix){
        td.innerHTML = cellProperties.prefix+''+td.innerHTML;
    }

};

var momentRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    if(moment(value).isValid()){
        td.innerHTML = moment(value).format("dddd D MMM YYYY H:mm:ss")+" ("+moment(value).fromNow()+")";
    }

    if(cellProperties.prefix){
        td.innerHTML = cellProperties.prefix+''+td.innerHTML;
    }

};

var kodeSkpdRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }


    //process kode skpd
    if(value!=null && value!=''){
        //debugger;
        var rows_ = alasql("SELECT nama FROM ? where kode_angka='"+value+"'",[master_skpd]);
        if(rows_.length>0){
            td.innerHTML = rows_[0]['nama'];
        }
    }

    var value1=value+'';
    if(value1==''){

        if(cellProperties.textWarnColor)
            td.style.color = cellProperties.textWarnColor;
        else
            td.style.color = "#aa0";

        td.style.textAlign = "center";
        td.innerHTML = '[Pilih SKPD penanggung jawab]';
    }

    if(cellProperties.prefix){
        td.innerHTML = cellProperties.prefix+''+td.innerHTML;
    }

};
var kodeSkpdRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }


    //process kode skpd
    if(value!=null && value!=''){
        //debugger;
        var rows_ = alasql("SELECT nama FROM ? where kode_angka='"+value+"'",[master_skpd]);
        if(rows_.length>0){
            td.innerHTML = value+'-'+rows_[0]['nama'];
        }
    }

    var value1=value+'';
    if(value1==''){

        if(cellProperties.textWarnColor)
            td.style.color = cellProperties.textWarnColor;
        else
            td.style.color = "#aa0";


        if(cellProperties.noWarning){

        }
        else{
            td.style.textAlign = "center";
            td.innerHTML = '[Pilih SKPD penanggung jawab]';
        }
    }
};

var multipleKodeSkpdRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    //process kode skpd
    if(value!=null && value!=''){

        var kodesplit=value.split(",");
        var disptext='<ol>';
        if(kodesplit.length>0){
            for(var i=0; i<kodesplit.length; i++){
                var rows_ = alasql("SELECT nama FROM ? where kode_angka='"+kodesplit[i]+"'",[master_skpd]);
                if(rows_.length>0){
                    disptext += '<li>'+kodesplit[i]+'-'+rows_[0]['nama']+'</li>';
                }
                else{
                    disptext += '<li>'+kodesplit[i]+''+'</li>';
                }

            }
            td.innerHTML=disptext+"</ol>";
        }
    }
};

var multipleKodeBidUrusan = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }

    //process kode skpd
    if(value!=null && value!=''){

        var kodesplit=value.split(",");
        var disptext='<ol>';
        if(kodesplit.length>0){
            for(var i=0; i<kodesplit.length; i++){
                var rows_ = alasql("SELECT nama FROM ? where (kode_u+'.'+kode_b)='"+kodesplit[i]+"'",[master_bidang]);
                if(rows_.length>0){
                    disptext += '<li>'+kodesplit[i]+'-'+rows_[0]['nama']+'</li>';
                }
                else{
                    disptext += '<li>'+kodesplit[i]+''+'</li>';
                }

            }
            //debugger;
            td.innerHTML=disptext+"</ol>";
        }
    }

    //revedel
    /*    if(cellProperties.prefix){
    td.innerHTML = cellProperties.prefix+''+td.innerHTML;
    } */

};

var kodeBMitraRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }


    //process kode bmitra
    if(value!=null && value!=''){
        //debugger;
        var rows_ = alasql("SELECT nama FROM ? where kode='"+value+"'",[master_bmitra]);
        if(rows_.length>0){
            td.innerHTML = rows_[0]['nama'];
        }
    }

    if(cellProperties.prefix){
        td.innerHTML = cellProperties.prefix+''+td.innerHTML;
    }

};

function castStringEmpty(s){
    if(String(s)=="undefined") return "";
    if(String(s)=="null") return "";
    return String(s);
}


function blockDiv(hashid,message,f){
    if(f==true){
        $(hashid).block({ 
            message: message, 
            css: { border: '1px solid #a00', margin: '5px' }         
        }); 
    }
    else{
        $(hashid).unblock();
    }


}

function nowDT(){
    var currentdate = new Date(); 
    var datetime = currentdate.getDate() + "-"
    + (currentdate.getMonth()+1)  + "-" 
    + currentdate.getFullYear() + " "  
    + currentdate.getHours() + ":"  
    + currentdate.getMinutes() + ":" 
    + currentdate.getSeconds() + ":"
    + currentdate.getMilliseconds()

    return datetime;            
}

var stringToColour = function(str) {

    // str to hash
    for (var i = 0, hash = 0; i < str.length; hash = str.charCodeAt(i++) + ((hash << 5) - hash));

    // int/hash to hex
    for (var i = 0, colour = "#"; i < 3; colour += ("00" + ((hash >> i++ * 8) & 0xFF).toString(16)).slice(-2));

    return colour;
}            

function getNowHMS(){
    var date = new Date;
    var seconds = date.getSeconds();
    var minutes = date.getMinutes();
    var hour = date.getHours();

    return hour+":"+minutes+":"+seconds;
}

var topMsgTimer;
function flashMessage(s, t){
    if(t===undefined) t='info';
    //toastr[t]("["+getNowHMS()+"] "+s);
    toastr[t](""+s);
}

//$("#top-msg").hide();
function top_msg(s){
    $("#top-msg").hide();
    $("#top-msg").html(s);
    $("#top-msg").show("blind","",500);
}

function stringToBoolean(string){
    switch(string.toLowerCase().trim()){
        case "true": case "yes": case "1": return true;
        case "false": case "no": case "0": case null: return false;
        default: return Boolean(string);
    }
}

function showTopLoading(b){
    if(b==true){
        $("#top-loading-anim").show();
    }
    else{
        $("#top-loading-anim").hide();
    }
}


//transform hot cell cahnge to group by cell row id
//hot column 0 must contain uniqe id to be processed by backend db, assosiate with rowid on table
function hot_transform_change_cell2row(hotInstance, change, transformInfo){
    //common
    //transform data multi cell ke model record table
    //param:
    // change is standar handosntable change cell format 
    var rows_json=[];

    var rows_ = alasql('SELECT * FROM ? group by [0]',[change]);
    for(var j=0; j<rows_.length; j++){
        var row1=alasql('SELECT * FROM ? where [0]='+rows_[j][0],[change]);

        for(var k=0; k<row1.length; k++){
            if(row1[k][2]===undefined || row1[k][2]===null){
                row1[k][2]=""; 
            } 
            if(row1[k][3]===undefined || row1[k][3]===null){
                row1[k][3]=""; 
            } 
        }

        var rowID=hotInstance.getDataAtCell(rows_[j][0], 0);

        if(!rowID){
            rowID=-1;
            transformInfo.containInsertNew=true;
        }

        rows_json.push(
            {
                "cellRow":rows_[j][0],
                "rowData":row1,
                "id":rowID
            }
        );
    }

    return rows_json;
}

var intVal = function ( i ) {
    return typeof i === 'string' ?
    i.replace(/[\$,]/g, '')*1 :
    typeof i === 'number' ?
    i : 0;
};

function reload_route_clear(){
    localStorage.clear();    
    reload_route();
}


/**
* Number.prototype.format(n, x, s, c)
* 
* @param integer n: length of decimal
* @param integer x: length of whole part
* @param mixed   s: sections delimiter
* @param mixed   c: decimal delimiter
*/
var formatNumber = function(numb, n, x, s, c, prefix) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
    num = (intVal(numb)).toFixed(Math.max(0, ~~n));

    if(prefix===undefined){
        prefix="Rp ";
    }
    return prefix+(c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};


function js_get_a_field(table_as_loadedarray,key,val,returnField){
    var rs=alasql("select "+returnField+" from ? where "+key+"='"+val+"' ", [table_as_loadedarray] );
    if(rs.length) {
        return rs[0][returnField];
    }
    else{
        return false;
    }
}

var DISABLE_LOCAL_DOWNLOAD=0;

function eb_download(data, fileName){
    var a = document.createElement('a');


    if (typeof a.download != "undefined" && DISABLE_LOCAL_DOWNLOAD==0) {
        /***
        * bila terdeteksi support a donwload lokal
        */
        var dh=$("#download-holder");
        dh.prop('download',fileName);

        data=atob(data);

        var arraybuffer = new ArrayBuffer(data.length);
        var view = new Uint8Array(arraybuffer);
        for (var i=0; i<data.length; i++) {
            view[i] = data.charCodeAt(i) & 0xff;
        }

        try {
            // This is the recommended method:
            var blob = new Blob(
                [arraybuffer], 
                {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}
            );
        } catch (e) {
            // The BlobBuilder API has been deprecated in favour of Blob, but older
            // browsers don't know about the Blob constructor
            // IE10 also supports BlobBuilder, but since the `Blob` constructor
            //  also works, there's no need to add `MSBlobBuilder`.
            var bb = new (window.WebKitBlobBuilder || window.MozBlobBuilder);
            bb.append(arraybuffer);
            var blob = bb.getBlob('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // <-- Here's the Blob
        }

        var u1=window.URL.createObjectURL(blob);
        // ori dh.prop('href','data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data);
        dh.prop('href',u1);
        //dh.click();

        document.getElementById("download-holder").click()        
        //window.location='data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data;
        flashMessage("Download lokal");
        //var b1= new Blob( data, {type : 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64'});
        //var u1=window.URL.createObjectURL([b1]);

        //window.location.assign(u1) ;
    }
    else{
        /***
        * download via server
        */
        flashMessage("Download via server...");
        var form = $("<form>").attr({
            xtarget: '_BLANK',
            action: '_public/echo.php',
            method: 'post'
        });

        var fileName=fileName;
        form.append($("<input>").attr({name: 'fileName', value: fileName}));
        form.append($("<input>").attr({name: 'contentType', value: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'}));
        form.append($("<input>").attr({name: 'content', value: data }));
        form.appendTo($("body"))
        form.submit();
        window.setTimeout(function () {form.remove();}, 10000);
    }
}


//-----------------excell builder
var eb_cell_border={
    bottom: {color: '000000', style: 'thin'},
    top: {color: '000000', style: 'thin'},
    left: {color: '000000', style: 'thin'},
    right: {color: '000000', style: 'thin'}

};

var eb_font_size=10;
var eb_rp_format="\Rp #,##0;-\Rp #,##0;;@";

var eb_format_left,
eb_format_left_bold, 
eb_format_left_bold_red, 
eb_format_rp, 
eb_format_rp_bold, 
eb_format_number, 
eb_format_center_bold;

function create_excel_style(stylesheet){

    eb_format_left=stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "left",
            vertical: "center",
        },
        font: {
            size: eb_font_size
        },
        border: eb_cell_border                 

    });

    eb_format_left_bold=stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "left",
            vertical: "center",
        },
        font: {
            bold: true,
            size: eb_font_size
        },
        border: eb_cell_border                    
    });

    eb_format_left_bold_red=stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "left",
            vertical: "center",
        },
        font: {
            color: 'FFFF0000',
            bold: true,
            size: eb_font_size
        },
        border: eb_cell_border                    
    });

    eb_format_rp = stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "right",
            vertical: "center",
        },
        font: {
            size: eb_font_size
        },
        border: eb_cell_border,                    
        format:  eb_rp_format,
    });                

    eb_format_rp_bold = stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "right",
            vertical: "center",
        },
        font: {
            bold: true,
            size: eb_font_size
        },
        border: eb_cell_border,                    
        format:  eb_rp_format,
    });                

    eb_format_number = stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "right",
            vertical: "center",
        },
        font: {
            size: eb_font_size
        },
        border: eb_cell_border,                    
    });                

    eb_format_center_bold = stylesheet.createFormat({
        alignment: {
            wrapText: true,
            horizontal: "center",
            vertical: "center",
        },
        font: {
            bold: true,
            size: eb_font_size
        },
        border: eb_cell_border              

    });
}

//--------------------eb end

function array_flip_keyval( trans )
{
    var key, tmp_ar = {};

    for ( key in trans )
    {
        if ( trans.hasOwnProperty( key ) )
        {
            tmp_ar[trans[key]] = key;
        }
    }

    return tmp_ar;
}


//LZW Compression/Decompression for Strings
var LZW = {
    compress: function (uncompressed) {
        "use strict";
        // Build the dictionary.
        var i,
        dictionary = {},
        c,
        wc,
        w = "",
        result = [],
        dictSize = 256;
        for (i = 0; i < 256; i += 1) {
            dictionary[String.fromCharCode(i)] = i;
        }

        for (i = 0; i < uncompressed.length; i += 1) {
            c = uncompressed.charAt(i);
            wc = w + c;
            //Do not use dictionary[wc] because javascript arrays 
            //will return values for array['pop'], array['push'] etc
            // if (dictionary[wc]) {
            if (dictionary.hasOwnProperty(wc)) {
                w = wc;
            } else {
                result.push(dictionary[w]);
                // Add wc to the dictionary.
                dictionary[wc] = dictSize++;
                w = String(c);
            }
        }

        // Output the code for w.
        if (w !== "") {
            result.push(dictionary[w]);
        }
        return result;
    },


    decompress: function (compressed) {
        "use strict";
        // Build the dictionary.
        var i,
        dictionary = [],
        w,
        result,
        k,
        entry = "",
        dictSize = 256;
        for (i = 0; i < 256; i += 1) {
            dictionary[i] = String.fromCharCode(i);
        }

        w = String.fromCharCode(compressed[0]);
        result = w;
        for (i = 1; i < compressed.length; i += 1) {
            k = compressed[i];
            if (dictionary[k]) {
                entry = dictionary[k];
            } else {
                if (k === dictSize) {
                    entry = w + w.charAt(0);
                } else {
                    return null;
                }
            }

            result += entry;

            // Add w+entry[0] to the dictionary.
            dictionary[dictSize++] = w + entry.charAt(0);

            w = entry;
        }
        return result;
    }
}; 

function dtable_common_apply_filter(dtable_instance,colMap_arr, that, colIdx){
    if(
        colMap_arr[colIdx]=='u' ||
        colMap_arr[colIdx]=='b' ||
        colMap_arr[colIdx]=='p' ||
        colMap_arr[colIdx]=='k' ||
        colMap_arr[colIdx]=='kode_u' ||
        colMap_arr[colIdx]=='kode_b' ||
        colMap_arr[colIdx]=='kode_p' ||
        colMap_arr[colIdx]=='kode_k' ||
        colMap_arr[colIdx]=='sub' 
    ){
        dtable_instance.column( colIdx ).search(that.value ? '^'+that.value+'$' : '', true, false ,true)                
    }
    else if(colMap_arr[colIdx]=='apbddes'){
        dtable_instance.column( colIdx ).search(that.value ? '^'+that.value+'$' : '', false, false ,true)                
    }
    else{
        var break1=that.value.replace(/ /gi,'|');
        //console.log("filter break:"+break1);
        dtable_instance.column( colIdx ).search( "^.*("+break1+").*$", true,false,true)
    }
    dtable_instance.draw();
}



function terbilang(angka){
    var bilangan=angka;
    var kalimat="";
    var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
    var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
    var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');
    var panjang_bilangan = bilangan.length;

    /* pengujian panjang bilangan */
    if(panjang_bilangan > 15){
        kalimat = "Diluar Batas";
    }else{
        /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
        for(i = 1; i <= panjang_bilangan; i++) {
            angka[i] = bilangan.substr(-(i),1);
        }

        var i = 1;
        var j = 0;

        /* mulai proses iterasi terhadap array angka */
        while(i <= panjang_bilangan){
            subkalimat = "";
            kata1 = "";
            kata2 = "";
            kata3 = "";

            /* untuk Ratusan */
            if(angka[i+2] != "0"){
                if(angka[i+2] == "1"){
                    kata1 = "Seratus";
                }else{
                    kata1 = kata[angka[i+2]] + " Ratus";
                }
            }

            /* untuk Puluhan atau Belasan */
            if(angka[i+1] != "0"){
                if(angka[i+1] == "1"){
                    if(angka[i] == "0"){
                        kata2 = "Sepuluh";
                    }else if(angka[i] == "1"){
                        kata2 = "Sebelas";
                    }else{
                        kata2 = kata[angka[i]] + " Belas";
                    }
                }else{
                    kata2 = kata[angka[i+1]] + " Puluh";
                }
            }

            /* untuk Satuan */
            if (angka[i] != "0"){
                if (angka[i+1] != "1"){
                    kata3 = kata[angka[i]];
                }
            }

            /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
            if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")){
                subkalimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
            }

            /* gabungkan variabe sub kalimat (untuk Satu blok 3 angka) ke variabel kalimat */
            kalimat = subkalimat + kalimat;
            i = i + 3;
            j = j + 1;
        }

        /* mengganti Satu Ribu jadi Seribu jika diperlukan */
        if ((angka[5] == "0") && (angka[6] == "0")){
            kalimat = kalimat.replace("Satu Ribu","Seribu");
        }
    }

    return kalimat;
}

function act_tooltipster(){
    $('.tooltips').tooltipster({
        delay: 2000,
        theme: 'tooltipster-shadow',
        position: 'bottom-right',
        maxWidth: 200,
    });    

}

function stopPropagation(evt) {
    if (evt.stopPropagation !== undefined) {
        evt.stopPropagation();
    } else {
        evt.cancelBubble = true;
    }
}

function decode_base64 (s)
{
    var e = {}, i, k, v = [], r = '', w = String.fromCharCode;
    var n = [[65, 91], [97, 123], [48, 58], [43, 44], [47, 48]];

    for (z in n)
    {
        for (i = n[z][0]; i < n[z][1]; i++)
        {
            v.push(w(i));
        }
    }
    for (i = 0; i < 64; i++)
    {
        e[v[i]] = i;
    }

    for (i = 0; i < s.length; i+=72)
    {
        var b = 0, c, x, l = 0, o = s.substring(i, i+72);
        for (x = 0; x < o.length; x++)
        {
            c = e[o.charAt(x)];
            b = (b << 6) + c;
            l += 6;
            while (l >= 8)
            {
                r += w((b >>> (l -= 8)) % 256);
            }
        }
    }
    return r;
}

function convertToSlug(Text)
{
    return Text
    .toLowerCase()
    .replace(/ /g,'-')
    .replace(/[^\w-]+/g,'')
    ;
}

function soundex(str) {
    //  discuss at: http://phpjs.org/functions/soundex/
    // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // original by: Arnout Kazemier (http://www.3rd-Eden.com)
    // improved by: Jack
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // bugfixed by: Onno Marsman
    // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    //    input by: Brett Zamir (http://brett-zamir.me)
    //  revised by: Rafał Kukawski (http://blog.kukawski.pl)
    //   example 1: soundex('Kevin');
    //   returns 1: 'K150'
    //   example 2: soundex('Ellery');
    //   returns 2: 'E460'
    //   example 3: soundex('Euler');
    //   returns 3: 'E460'

    str = (str + '')
    .toUpperCase();
    if (!str) {
        return '';
    }
    var sdx = [0, 0, 0, 0],
    m = {
        B: 1,
        F: 1,
        P: 1,
        V: 1,
        C: 2,
        G: 2,
        J: 2,
        K: 2,
        Q: 2,
        S: 2,
        X: 2,
        Z: 2,
        D: 3,
        T: 3,
        L: 4,
        M: 5,
        N: 5,
        R: 6
    },
    i = 0,
    j, s = 0,
    c, p;

    while ((c = str.charAt(i++)) && s < 4) {
        if (j = m[c]) {
            if (j !== p) {
                sdx[s++] = p = j;
            }
        } else {
            s += i === 1;
            p = 0;
        }
    }

    sdx[0] = str.charAt(0);
    return sdx.join('');
}

function processCellProps(that, td, cellProperties){

    if(cellProperties.backColor){
        td.style.backgroundColor = cellProperties.backColor;
    }

    if(cellProperties.textColor && DEBUG_DETAIL==0){
        td.style.color=cellProperties.textColor;
    }

    if(cellProperties.isError){
        td.style.backgroundColor = "#f00";
    }
    else if(cellProperties.readOnly) {
        td.style.backgroundColor = "#ddd";
    }
}

var kodeKecRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);

    processCellProps(this, td, cellProperties);

    //process kode skpd
    if(value!=null && value!=''){
        //debugger;
        var rows_ = alasql("SELECT text FROM ? where id='"+value+"'",[master_kecamatan]);
        if(rows_.length>0){
            td.innerHTML = rows_[0]['text'];
        }
    }

};

var kodeKomodRenderer = function(instance, td, row, col, prop, value, cellProperties) {
    Handsontable.renderers.TextRenderer.apply(this, arguments);
    processCellProps(this, td, cellProperties);

    //process kode skpd
    if(value!=null && value!=''){
        //debugger;
        var rows_ = alasql("SELECT text FROM ? where id='"+value+"'",[master_komoditas]);
        if(rows_.length>0){
            td.innerHTML = rows_[0]['text'];
        }
    }

};
