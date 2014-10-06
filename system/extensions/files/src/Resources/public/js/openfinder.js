/*!
 * OpenFinder Jquery Plugin
 * Version: 0.1.0
 * Author: egig <egigundari@gmail.com>
 * https://github.com/egig/openfinder
 *
 * Free file finder for web
 * make use of twbs and font awesome.
 *
 * Licensed under MIT
 * ========================================================= */

;(function ($, window, document, undefined) {

    // Create the defaults
    var pluginName = "openFinder",
        defaults = {
            width: '100%',
            height: 600,
            onSelect: false,
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        // The first object is generally empty because we don't
        // want to alter the default options for future instances
        // of the plugin
        this.options = $.extend( {}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function() {

            this.data = [];
            this.data['currentPath'] = '/';
            
            this.createContainer(this.element, this.options);

            this.listen(this.element, this.options);

            this.handleHight();
        },

        listen: function (el, options) {

            $(el).on('click', 'a.of-node', $.proxy(this.toggle, this));

            // context menu
            $(el).contextmenu({
              target: '#item-context-menu',
              onItem: $.proxy(this.handleContext, this),
              before: function (e, element, target) {
                    
                    var contextTarget = null;

                    if($(e.target).hasClass('of-context-holder')) {
                        contextTarget = $(e.target).data('context-target');
                    } else {
                                        
                        var containers = $(e.target).parents();
                        
                        $.each(containers, function(key, value){
                            if($(value).hasClass('of-context-holder')) {
                                contextTarget = $(value).data('context-target');
                                return false;
                            }
                        });
                    }


                    $(el).data('target', contextTarget);

                    console.log(containers);
                    console.log(contextTarget);

                    return true;
                  }
            });

            this.listenUpload('/');

            // item click
            $(el).on('click', '.of-item a', function(e){
                e.preventDefault();
            });

            // item image
            $(el).on('click', '.img-item a', function(e){
                e.preventDefault();

                if($.isFunction(options.onISelect)) {
                    options.onISelect(e);
                }
            });

        },

        listenUpload: function(p) {

            $('#upload-form').ajaxForm({
               data: {
                    path: p,
                    op: 'upload'
               },
               success: $.proxy(function(data){
                    this.refresh(p);

                    for(i=0;i<data.length;i++) {
                        // console.log(data[i]);
                        $('.uploaded').append('<p> New file: '+data[i].uploaded+'</p>');
                    }

               }, this)
            });
        },

        toggle: function(e) {

            e.preventDefault();
            var a = e.currentTarget;
            var path = $(a).attr('href');

            // load from
            if(!$(a).data('loaded')) {
                this.load(path);
                //save data
                this.data[path] = data;
                
                ul = this.buildRoot(data);
                $(ul).hide(); // hide first to slide
                $(a).after(ul);
                $(a).data('loaded', true);
            }

            this.data['currentPath'] = path;
            
            //upload
            this.listenUpload(path);

            this.toggleSlides(a);
            this.handleHight();
        },

        handleHight: function() {
            var h = $('.ctn-bro').height();
            $('.ctn-nav').height(h);
        },

        handleContext: function(context, e) {
            var op = $(e.target).parent().data('action');
            var path = $(context).children('a').attr('href');
            
            switch(op) {
                case 'delete':

                    if(confirm('Are you sure you want to delete '+path+' ?, this cannot be undone.')) {
                        this.request('delete', path);
                    } else {
                        return false;
                    }

                    break;
                case 'new-folder':
                    // ask folder name use dialog

                    //create folder on server

                break;
                default:
                    break;
            }

            this.refresh();
        },

        updateNav: function (data){
            ul = this.buildRoot(data);
            $(this.ctn.nav).append(ul);
        },

        updateBrowser: function (data){
            ul = this.createEl('UL');

            for(i=0; i<data.length; i++ ) {
                    
                node = this.createFileItem(data[i]);
                $(ul).append(node);
            }

            $(this.ctn.bro).html(ul)
        },

        toggleSlides: function(a){
            i = $(a).children('i');
            path = $(a).attr('href');
            
            if(i.hasClass('fa-folder-open-o')) {
                i.removeClass('fa-folder-open-o');
                i.addClass('fa-folder-o');

                $(a).siblings('ul').slideUp('fast');
            } else {

                i.removeClass('fa-folder-o');
                i.addClass('fa-folder-open-o');
                $(a).siblings('ul').slideDown('fast');                
            }

            this.updateBrowser(this.data[path]);
        },

        load: function(path){
            
            data = this.request('ls', '/'+path);

            return data;
        },

        request: function(op, path) {
            $.ajax({
                url: this.options.url,
                data: {op: op, path: path },
                async: false
            }).done(function(res){
                result = res;  
            });

            return result;
        },

        refresh: function(path) {

            if(typeof path == 'undefined') {
                path = this.data['currentPath'];
            }

            data = this.request('ls', '/'+path);
            this.data[path] = data;
            this.updateBrowser(data);
        },

        createNode: function(path, label)
        {

            var icon = this.createEl('I').addClass('fa fa-folder-o');
            
            var a = this.createEl('A', {href: path})
                .addClass('of-node')
                .append(icon)
                .append(' '+label);

            var li = this.createEl('LI').append(a);

            return li;
        },

        buildRoot: function (data){
            
            if(data.length > 0) {
                ul =  this.createEl('UL');
                
                for(i=0; i<data.length; i++ ) {
                    
                    if(data[i].type === 'dir') {
                        node = this.createNode(data[i].path, data[i].label);
                        $(ul).append(node);
                    }
                }
                return ul;
            }

            return null;

        },

        createFileItem: function(file) {
            
            var li = this.createEl('LI').addClass('of-item of-context-holder');
             li.data('context-target', '#item-context-menu');

            if(file.type == 'image') {
                 var icon = this.createEl('IMG',{
                    src: file.base64
                 })
                    .addClass('icon')

                $(li).addClass('img-item');

            } else {

                if(file.type == 'file') {
                    faClass = 'fa fa-file-o';
                    $(li).addClass('file-item');
                
                } else if(file.type == 'dir') {
                    faClass = 'fa fa-folder-o';

                    $(li).addClass('folder-item');
                } else {
                    faClass = null;
                }

                var icon = this.createEl('I')
                    .addClass('icon')
                    .addClass(faClass);
            }

            var a = this.createEl('A', {href: file.path})
                .append(icon)
                .append('<br/>'+file.label);
            
            $(li).append(a);

            return li;
        },

        createContainer: function(el, options) {

            this.ctn = {};

            this.ctn.nav = this.createEl('DIV').addClass('col-sm-3 ctn-nav ctn');
            
            this.ctn.bro = this.createEl('DIV').addClass('col-sm-9 ctn-bro ctn of-context-holder');

            $(this.ctn.bro).data('context-target', '#bro-context-menu');
            
            var row = this.createEl('DIV').addClass('row');

            var wrapper = this.createEl('DIV').addClass('wrapper container-fluid');

            var toolBar = this.createToolbar();
            var uploadDialog = this.createUploadDialog();
            var itemContext = this.createItemContext();      
            var broContext = this.createBroContext();      

            $(row)
                .append(this.ctn.nav)
                .append(this.ctn.bro)

            $(wrapper)
                .append(toolBar)
                .append(row)

            $(el)
                .width(options.width)
                .append(wrapper)
                .after(itemContext)
                .after(broContext)
                .after(uploadDialog);

            var data = this.request('ls', '/');

            this.updateNav(data);
            this.updateBrowser(data);
        },

        createToolbar: function(){

            /*
            settingBtn = this.createEl('A')
                .addClass('tool btn btn-sm btn-default pull-right')
                .html('<i class="fa fa-gears"></i>');

            listBtn =  this.createEl('A')
                .addClass('tool btn btn-sm btn-default pull-right')
                .html('<i class="fa fa-list"></i>');

            gridBtn = this.createEl('A')
                .addClass('tool btn btn-sm btn-default pull-right')
                .html('<i class="fa fa-th"></i>');
                */


            uploadBtn =  this.createEl('A', {
                    href: '#',
                    'data-toggle': 'modal',
                    'data-target': '#upload-dialog'
                }).addClass('upload-btn tool btn btn-sm btn-primary pull-left')
                .html('<i class="fa fa-upload"></i>');

            /*searchForm = this.createEl('FORM').addClass('form-inline');
            searchInput = this.createEl('INPUT', {
                type: 'text',
                name: 'q',
                placeholder: 'Type to search'
            }).addClass('tool input-sm form-control pull-right')
            
            $(searchForm).append(searchInput);*/

            toolBar = this.createEl('DIV').addClass('row toolBar');

            $(toolBar)
                .append(uploadBtn);
                //.append(searchForm)

            return toolBar;
        },

        createUploadDialog: function(){
            
            modal = this.createEl('DIV', {
                id: 'upload-dialog'
            }).addClass('modal fade');
            
            body = this.createEl('DIV').addClass('modal-body');

            uploadUrl = this.options.uploadUrl || this.options.url;
            
            $(body).html([
                '<form method="POST" enctype="multipart/form-data" class="form clearfix" id="upload-form" action="'+uploadUrl+'">',
                '<input multiple type="file" name="files[]" style="margin-bottom:10px;">',
                '<div class="uploaded"></div>',
                '<input type="submit" class="btn btn-primary pull-right" value="Submit">',
                '</form>'].join(''));
            
            dialog = this.createEl('DIV').addClass('modal-dialog');
            content = this.createEl('DIV').addClass('modal-content');

            $(content).append(body);
            $(dialog).append(content);
            $(modal).append(dialog);

            return modal;
        },

        createBroContext: function() {

            var dropUL = this.createEl('UL', {role: 'menu'}).addClass('dropdown-menu');

            var menu = {
                //'rename': 'Rename',
                'new-folder': 'New Folder',
                //'copy': 'Copy...',
                //'move': 'Move...',
            }

            $.each(menu, $.proxy(function(key, value) {
                li = this.createContextAction(key, value);
                $(dropUL).append(li);
            }, this));

            var contextWrapper = this.createEl('DIV', {
                id: 'bro-context-menu'
            }).append(dropUL);

            return contextWrapper;
        },

        // right click context menu
        createItemContext: function() {

            var dropUL = this.createEl('UL', {role: 'menu'}).addClass('dropdown-menu');

            var menu = {
                //'rename': 'Rename',
                'delete': 'Delete...',
                //'copy': 'Copy...',
                //'move': 'Move...',
            }

            $.each(menu, $.proxy(function(key, value) {
                li = this.createContextAction(key, value);
                $(dropUL).append(li);
            }, this));

            var contextWrapper = this.createEl('DIV', {
                id: 'item-context-menu'
            }).append(dropUL);

            return contextWrapper;
        },

        // context action
        createContextAction: function(act, text) {
            a = this.createEl('A', {href: '#'}).text(text);
            li = this.createEl('LI', {'data-action': act}).append(a);

            return li;
        },

        
        // Create element
        createEl: function(name, attr) {
            el = document.createElement(name);

            if(typeof attr != 'undefined') {
                $.each( attr, function( key, value ) {
                    $(el).attr(key, value);
                });
            }

            return $(el);
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})(jQuery, window, document);