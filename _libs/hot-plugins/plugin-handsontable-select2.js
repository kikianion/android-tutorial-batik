//**
// butuh alasql untuk validator
//

(function (Handsontable) {
    "use strict";
    var select2Options_data;
    var colOptions;

    var select2validator=function (value, callback) {
        var that=this;
        setTimeout(function(){
            //debugger;
            if(colOptions.allowInvalidBypass && colOptions.allowInvalidBypass==true) {
                callback(true);   
                return;
            };

            var a1=alasql("select text from ? where id='"+value+"' ",[select2Options_data]);
            if (a1.length>0 ) {
                callback(true);
            }
            else {
                callback(false);
            }
            }, 100);
    };


    var Select2Editor = Handsontable.editors.TextEditor.prototype.extend();

    Select2Editor.prototype.prepare = function (row, col, prop, td, originalValue, cellProperties) {
        Handsontable.editors.TextEditor.prototype.prepare.apply(this, arguments);
        cellProperties.validator=select2validator;

        this.options = {};

        if (this.cellProperties.select2Options) {
            this.options = $.extend(this.options, cellProperties.select2Options);
            if(typeof this.cellProperties.select2Options.data=='function'){
                select2Options_data=this.cellProperties.select2Options.data();

            }
            else{
                select2Options_data=this.cellProperties.select2Options.data;

            }
        }
        colOptions=this.cellProperties.select2Options;
    };

    Select2Editor.prototype.createElements = function () {
        this.$body = $(document.body);

        this.TEXTAREA = document.createElement('input');
        this.TEXTAREA.focus=true;
        this.TEXTAREA.setAttribute('type', 'text');
        this.$textarea = $(this.TEXTAREA);

        Handsontable.Dom.addClass(this.TEXTAREA, 'handsontableInput');

        this.textareaStyle = this.TEXTAREA.style;
        this.textareaStyle.width = 0;
        this.textareaStyle.height = 0;

        this.TEXTAREA_PARENT = document.createElement('DIV');
        Handsontable.Dom.addClass(this.TEXTAREA_PARENT, 'handsontableInputHolder');

        this.textareaParentStyle = this.TEXTAREA_PARENT.style;
        this.textareaParentStyle.top = 10;
        this.textareaParentStyle.left = 0;
        //this.textareaParentStyle.backgroundColor = '#ddd';
        this.textareaParentStyle.display = 'none';

        this.TEXTAREA_PARENT.appendChild(this.TEXTAREA);

        this.instance.rootElement.appendChild(this.TEXTAREA_PARENT);

        var that = this;
        this.instance._registerTimeout(setTimeout(function () {
            that.refreshDimensions();
            }, 0));
    };

    var onSelect2Changed = function () {
        //this.$textarea.select2('close');
        //echo('sel2 change');
        this.close();
        //this.finishEditing();
    };
    var onSelect2Closed = function () {
        this.close();
        this.finishEditing();
    };
    var onBeforeKeyDown = function (event) {


        //echo('before keydown');
        //echo('keycode:'+event.keyCode);
        Handsontable.Dom.stopImmediatePropagation(event);

        var instance = this;
        var that = instance.getActiveEditor();

        var keyCodes = KeyEvent;
        var ctrlDown = (event.ctrlKey || event.metaKey) && !event.altKey; //catch CTRL but not right ALT (which in some systems triggers ALT+CTRL)

        //Process only events that have been fired in the editor
        /*        if (!$(event.target).hasClass('"select2-search__field"') || event.isImmediatePropagationStopped()) {*/
        if ( 
            !$(event.target).hasClass('select2-search__field') &&
            !$(event.target).hasClass('select2-selection') 

        ) {
            //echo('event not from editor active');
            //debugger;
            return;
        }
        if (event.keyCode === 17 || event.keyCode === 224 || event.keyCode === 91 || event.keyCode === 93) {
            //when CTRL or its equivalent is pressed and cell is edited, don't prepare selectable text in textarea
            event.stopImmediatePropagation();
            echo('select2 dont prepare selectable text ');
            return;
        }

        var target = event.target;

        switch (event.keyCode) {
            case keyCodes.DOM_VK_RIGHT :
                if (Handsontable.Dom.getCaretPosition(target) !== target.value.length) {
                    event.stopImmediatePropagation();
                } else {
                    that.$textarea.select2('close');
                }
                break;

            case keyCodes.DOM_VK_LEFT :
                if (Handsontable.Dom.getCaretPosition(target) !== 0) {
                    event.stopImmediatePropagation();
                } else {
                    that.$textarea.select2('close');
                }
                break;

            case keyCodes.DOM_VK_ESCAPE:
                //echo ('escape to close');
                that.$textarea.select2('close');
                that.finishEditing(true);

                break;
            /*case keyCodes.DOM_VK_ENTER:
            //echo ('enter');
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
            //event.preventDefault(); //don't add newline to field
            that.$textarea.select2('close');
            that.finishEditing(true);
            break;*/

            case keyCodes.DOM_VK_A:
            case keyCodes.DOM_VK_X:
            case keyCodes.DOM_VK_C:
            case keyCodes.DOM_VK_V:
                if (ctrlDown) {
                    event.stopImmediatePropagation(); //CTRL+A, CTRL+C, CTRL+V, CTRL+X should only work locally when cell is edited (not in table context)
                }
                break;

            case keyCodes.DOM_VK_BACKSPACE:
                event.stopImmediatePropagation(); //backspace, delete, home, end should only work locally when cell is edited (not in table context)
                break;

            case keyCodes.DOM_VK_DELETE:
            case keyCodes.DOM_VK_HOME:
            case keyCodes.DOM_VK_END:
                event.stopImmediatePropagation(); //backspace, delete, home, end should only work locally when cell is edited (not in table context)
                break;
        }

    };

    Select2Editor.prototype.open = function (keyboardEvent) {
        Handsontable.editors.TextEditor.prototype.open.apply(this, arguments);
        this.refreshDimensions();
        this.textareaParentStyle.display = 'block';


        this.instance.addHook('beforeKeyDown', onBeforeKeyDown);
        //--------
        /*$('.select2-input').on("keydown", function(e) {
        if (e.keyCode == 13) {
        //$("#select2-drop-mask").click();
        //$('#name').focus();
        //e.preventDefault();
        echo('select2 enter');   
        }
        });*/

        this.$textarea.css({
            height: $(this.TD).height() + 4,
            'min-width': $(this.TD).outerWidth() - 4
        });

        //display the list
        this.$textarea.show();

        //make sure that list positions matches cell position
        //this.$textarea.offset($(this.TD).offset());

        var self = this;

        var sel2options_=JSON.parse(JSON.stringify(this.options));
        if(typeof this.options.data=='function'){
            sel2options_.data=this.options.data();
        }

        //debugger;
        var sel2comp=this.$textarea.select2(sel2options_)
        .on('change', onSelect2Changed.bind(this))
        .on('select2-close', onSelect2Closed.bind(this));

        $(".handsontable .select2-input").focus();
        $(".handsontable .select2-selection__rendered").css('backgroundColor','#ddd');
        self.$textarea.select2('open');
        //sel2comp.val(null).trigger('change');

        /*var a=$(".select2-input");

        setTimeout(function(){
        //debugger;
        },300);
        */
        if (keyboardEvent && keyboardEvent.keyCode) {
            var key = keyboardEvent.keyCode;
            var keyText = (String.fromCharCode((96 <= key && key <= 105) ? key-48 : key)).toLowerCase();
            //self.$textarea.select2('search', keyText);
        }
    };

    Select2Editor.prototype.init = function () {
        Handsontable.editors.TextEditor.prototype.init.apply(this, arguments);
    };

    Select2Editor.prototype.close = function () {
        this.instance.listen();
        this.instance.removeHook('beforeKeyDown', onBeforeKeyDown);
        this.$textarea.off();
        this.$textarea.hide();

        //echo('aa close');
        Handsontable.editors.TextEditor.prototype.close.apply(this, arguments);
        this.finishEditing(false, false);

    };

    Select2Editor.prototype.val = function (value) {
        if (typeof value == 'undefined') {
            return this.$textarea.val();
        } else {
            this.$textarea.val(value);
        }
    };

    Select2Editor.prototype.focus = function () {

        this.instance.listen();

        // DO NOT CALL THE BASE TEXTEDITOR FOCUS METHOD HERE, IT CAN MAKE THIS EDITOR BEHAVE POORLY AND HAS NO PURPOSE WITHIN THE CONTEXT OF THIS EDITOR
        //Handsontable.editors.TextEditor.prototype.focus.apply(this, arguments);
    };

    Select2Editor.prototype.beginEditing = function (initialValue) {
        var onBeginEditing = this.instance.getSettings().onBeginEditing;
        if (onBeginEditing && onBeginEditing() === false) {
            return;
        }
        Handsontable.editors.TextEditor.prototype.beginEditing.apply(this, arguments);

    };

    Select2Editor.prototype.finishEditing = function (isCancelled, ctrlDown) {
        this.instance.listen();
        return Handsontable.editors.TextEditor.prototype.finishEditing.apply(this, arguments);
    };


    Handsontable.editors.Select2Editor = Select2Editor;
    Handsontable.editors.registerEditor('select2', Select2Editor);

    /*Handsontable.Select2Validator = function (value, callback) {
    var that=this;
    setTimeout(function(){
    var a1=alasql("select text from ? where id='"+value+"' ",[select2Options_data]);
    if (a1.length>0 ) {
    callback(true);
    }
    else {
    callback(false);
    }
    }, 100);
    };*/



    Handsontable.Select2Cell = {
        editor: 'select2',
        //renderer: getRenderer('autocomplete'),
        validator: Handsontable.Select2Validator 
    };

    Handsontable.cellTypes.select2=Handsontable.Select2Cell ;

})(Handsontable);            
