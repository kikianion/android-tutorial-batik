/// select2 plugin
(function (Handsontable) {
    "use strict";

    var Select2MultipleEditor = Handsontable.editors.TextEditor.prototype.extend();
    var select2Options_;
    Select2MultipleEditor.prototype.prepare = function (row, col, prop, td, originalValue, cellProperties) {
        Handsontable.editors.TextEditor.prototype.prepare.apply(this, arguments);

        this.options = {multiple:true};

        if (this.cellProperties.select2Options) {
            this.options = $.extend(this.options, cellProperties.select2Options);
            select2Options_=this.cellProperties.select2Options;
        }
    };

    Select2MultipleEditor.prototype.createElements = function () {

        Handsontable.editors.TextEditor.prototype.createElements.apply(this, arguments);
        $("body").append("<div id='divmultiselect2-holder99'>"+
            "<input id='multiselect2-99'/>"+

            "<input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>"+
            "</div>"
        );

        var dialog1=$( "#divmultiselect2-holder99" ).dialog({
            autoOpen: false,
            height: 400,
            width: 650,
            xmodal: true,
            buttons: {
                "Batal": function(){
                    dialog1.dialog( "close" );
                },
                "Simpan": function() {
                    dialog1.dialog( "close" );
                    //hotparent99.instance.setDataAtCell(hotparent99.row, hotparent99.col, tinymce.get('tmce99').getContent());
                }
            },
            close: function() {
                //dialog1.dialog( "close" );            
            }
        });

        //this.$body = $(document.body);

        //this.TEXTAREA = document.createElement('input');
        //this.TEXTAREA.focus=true;
        //this.TEXTAREA.setAttribute('type', 'text');
        this.$textarea = $("#multiselect2-99");

        //Handsontable.Dom.addClass(this.TEXTAREA, 'handsontableInput');

        //this.textareaStyle = this.TEXTAREA.style;
        //this.textareaStyle.width = 0;
        //this.textareaStyle.height = 0;

        //this.TEXTAREA_PARENT = document.createElement('DIV');
        //Handsontable.Dom.addClass(this.TEXTAREA_PARENT, 'handsontableInputHolder');

        //this.textareaParentStyle = this.TEXTAREA_PARENT.style;
        //this.textareaParentStyle.top = 0;
        //this.textareaParentStyle.left = 0;
        //this.textareaParentStyle.display = 'none';

        //this.TEXTAREA_PARENT.appendChild(this.TEXTAREA);

        //this.instance.rootElement.appendChild(this.TEXTAREA_PARENT);

        var that = this;
        this.instance._registerTimeout(setTimeout(function () {
            //that.refreshDimensions();
            }, 0));
    };

    var onSelect2Changed = function () {
        //console.log("select2 closed");
        //this.close();
        //this.finishEditing();
    };
    var onSelect2Closed = function () {
        //this.close();
        //this.finishEditing();
    };
    /*var onBeforeKeyDown = function (event) {
        //console.log("ht keydown");
        var instance = this;
        var that = instance.getActiveEditor();

        var keyCodes = Handsontable.helper.keyCode;
        var ctrlDown = (event.ctrlKey || event.metaKey) && !event.altKey; //catch CTRL but not right ALT (which in some systems triggers ALT+CTRL)

        //console.log("key:"+event.keyCode+"     ctrl:"+event.ctrlKey);

        if(event.ctrlKey && event.keyCode==13){
            that.close();
            //this.finishEditing();
            //console.log("finihs edit selet2");
            return;
        }

        if(event.keyCode==27){
            that.close();
            //this.finishEditing();
            //console.log("finihs edit selet2");
            return;
        }

        //Process only events that have been fired in the editor
        if (!$(event.target).hasClass('select2-input') || event.isImmediatePropagationStopped()) {
            return;
        }
        if (event.keyCode === 17 || event.keyCode === 224 || event.keyCode === 91 || event.keyCode === 93) {
            //when CTRL or its equivalent is pressed and cell is edited, don't prepare selectable text in textarea
            event.stopImmediatePropagation();
            return;
        }

        var target = event.target;

        switch (event.keyCode) {
            case keyCodes.ARROW_RIGHT:
                if (Handsontable.Dom.getCaretPosition(target) !== target.value.length) {
                    event.stopImmediatePropagation();
                } else {
                    that.$textarea.select2('close');
                }
                break;

            case keyCodes.ARROW_LEFT:
                if (Handsontable.Dom.getCaretPosition(target) !== 0) {
                    event.stopImmediatePropagation();
                } else {
                    that.$textarea.select2('close');
                }
                break;

            case keyCodes.ENTER:
                var selected = that.instance.getSelected();
                var isMultipleSelection = !(selected[0] === selected[2] && selected[1] === selected[3]);
                if ((ctrlDown && !isMultipleSelection) || event.altKey) { //if ctrl+enter or alt+enter, add new line
                    if (that.isOpened()) {
                        that.val(that.val() + '\n');
                        that.focus();
                    } else {
                        that.beginEditing(that.originalValue + '\n')
                    }
                    event.stopImmediatePropagation();
                }
                event.preventDefault(); //don't add newline to field
                break;

            case keyCodes.A:
            case keyCodes.X:
            case keyCodes.C:
            case keyCodes.V:
                if (ctrlDown) {
                    event.stopImmediatePropagation(); //CTRL+A, CTRL+C, CTRL+V, CTRL+X should only work locally when cell is edited (not in table context)
                }
                break;

            case keyCodes.BACKSPACE:
                event.stopImmediatePropagation(); //backspace, delete, home, end should only work locally when cell is edited (not in table context)
                break;

            case keyCodes.DELETE:
            case keyCodes.HOME:
            case keyCodes.END:
                event.stopImmediatePropagation(); //backspace, delete, home, end should only work locally when cell is edited (not in table context)
                break;
        }

    };*/

    /*var onSelect2KeyDown = function (event) {
        //console.log("select2 keydown");
    }*/

    Select2MultipleEditor.prototype.open = function (keyboardEvent) {


        //this.refreshDimensions();
        //this.textareaParentStyle.display = 'block';
        //this.instance.addHook('beforeKeyDown', onBeforeKeyDown);

        /*this.$textarea.css({
        height: $(this.TD).height() + 4,
        'min-width': $(this.TD).outerWidth() - 4
        });

        //display the list
        this.$textarea.show();

        //make sure that list positions matches cell position
        //this.$textarea.offset($(this.TD).offset());
        */
        var self = this;
        //debugger;

        this.$textarea.select2(this.options)
        //.on('change', onSelect2Changed.bind(this))
        //.on('select2-close', onSelect2Closed.bind(this));
        //.on('keydown',onSelect2KeyDown.bind(this));

        //self.$textarea.select2('open');

        var a=$(".select2-input");

        setTimeout(function(){
            //debugger;
            //$(".select2-input").focus();

            },300);

        if (keyboardEvent && keyboardEvent.keyCode) {
            var key = keyboardEvent.keyCode;
            var keyText = (String.fromCharCode((96 <= key && key <= 105) ? key-48 : key)).toLowerCase();
            self.$textarea.select2('search', keyText);
        }

        $( "#divmultiselect2-holder99" ).dialog('open');

    };

    Select2MultipleEditor.prototype.init = function () {
        Handsontable.editors.TextEditor.prototype.init.apply(this, arguments);
    };

    Select2MultipleEditor.prototype.close = function () {
        //this.instance.listen();
        //this.instance.removeHook('beforeKeyDown', onBeforeKeyDown);
        //this.$textarea.off();
        //this.$textarea.hide();


        Handsontable.editors.TextEditor.prototype.close.apply(this, arguments);
    };

    Select2MultipleEditor.prototype.val = function (value) {
        if (typeof value == 'undefined') {
            return this.$textarea.val();
        } else {
            var val=this.instance.getDataAtCell(this.row, this.col);
            if(val==null){
                val='';
            }; 
            this.$textarea.val(value);
        }
    };


    Select2MultipleEditor.prototype.focus = function () {

        this.instance.listen();

        // DO NOT CALL THE BASE TEXTEDITOR FOCUS METHOD HERE, IT CAN MAKE THIS EDITOR BEHAVE POORLY AND HAS NO PURPOSE WITHIN THE CONTEXT OF THIS EDITOR
        //Handsontable.editors.TextEditor.prototype.focus.apply(this, arguments);
    };

    Select2MultipleEditor.prototype.beginEditing = function (initialValue) {
        var onBeginEditing = this.instance.getSettings().onBeginEditing;
        if (onBeginEditing && onBeginEditing() === false) {
            return;
        }
        Handsontable.editors.TextEditor.prototype.beginEditing.apply(this, arguments);

    };

    Select2MultipleEditor.prototype.finishEditing = function (isCancelled, ctrlDown) {
        //this.instance.listen();
        return Handsontable.editors.TextEditor.prototype.finishEditing.apply(this, arguments);
    };


    Handsontable.editors.Select2MultipleEditor = Select2MultipleEditor;
    Handsontable.editors.registerEditor('select2multiple', Select2MultipleEditor);

})(Handsontable);            
