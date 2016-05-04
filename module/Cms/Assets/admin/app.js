
$(function(){

    // Global settings for the whole panel
    $.ajaxSetup({
        cache : false,
        charset : "UTF-8",
        type : "POST",
        beforeSend  : function() {
            $("#loader").modal();
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete : function() {
            $("#loader").modal("hide");
        },
        error : function(res) {
            console.log(res);
        }
    });

    // Simple WYSIWYG wrapper
    $.wysiwyg = {
        // Indicated whether a WYSIWYG editor is initialized
        started : false,
        isStarted : function(){
            return this.started;
        },
        // This method should be invoked on each AJAX request
        update : function(){
            if (!this.isStarted()){
                return false;
            }
            
            if (typeof(CKEDITOR) != 'undefined'){
                for (instance in CKEDITOR.instances){
                    CKEDITOR.instances[instance].updateElement();
                }
            } else if (typeof(tinyMCE) != 'undefined'){
                tinyMCE.triggerSave();
            } else {
                // add more later
            }
        },
        // The array of elements to be replaced
        init : function(elements){
            // Interface language
            var language = $("input[name='language']").val();

            if (typeof(CKEDITOR) != 'undefined'){
                for (key in elements) {
                    var value = elements[key];
                    CKEDITOR.replace(value, {
                        language : language
                    });
                }
                
                this.started = true;

            } else if ((typeof tinyMCE != 'undefined')){
                $.tinyMCE({
                    elements : elements.join(', ')
                });

                this.started = true;
            }
        }
    };
    
    // Error handler class written for Bootstrap 3.x
    var errorHandler = {
        /**
         * Builds element name
         * 
         * @param string Field name
         * @param string
         */
        buildName : function(name){
            // If group is specified, then gotta build a selector for that
            if ($.group) {
                return $.group + "[" + name + "]";
            } else {
                return name;
            }
        },

        /**
         * Builds appropriate element selector
         * 
         * @param string name
         * @return string Prepared selector name
         */
        buildSelector : function(name){
            // If group is specified, then gotta build a selector for that
            if ($.group) {
                return "[name='" + $.group + "[" + name + "]" + "']";
            } else {
                return "[name='" + name + "']";
            }
        },

        /**
         * Remove all previous error classes if present
         * 
         * @return void
         */
        resetAll : function(){
            $("div.form-group").removeClass('has-error').addClass('has-success');
        },

        /**
         * Creates populated UL element
         * 
         * @param object messages JSON object with error messages
         * @return HTMLElement
         */
        createUl : function(messages){
            var ul = document.createElement('ul');

            for (var key in messages) {
                var li = document.createElement('li');
                var text = messages[key];

                $(li).text(text);
                $(ul).append(li);
            }

            // Return populated UL element
            return ul;
        },

        /**
         * Displays modal dialog with error messages
         * 
         * @param mixed response
         * @return void
         */
        displayModal : function(messages){
            var $modal = $("#errors-modal");

            if ($.isArray(messages)){
                var text = this.createUl(messages);
            } else {
                var text = messages;
            }

            $modal.find(".modal-body").empty().html(text);
            $modal.modal("show");
        },

        /**
         * Renders error messages highlighting fields
         * 
         * @param object data
         * @return void
         */
        render : function(data){
            // To access errorHandler instance inside functions
            var self = this;

            for (var key in data.names) {
                // Name represents a name of an Element, that contains an error
                var name = data.names[key];
                // OK, we got an instance of current element which contain an error
                var $targetElement = $(self.buildSelector(name));
                // That's not fast, but reliable at least
                var $row = $targetElement.closest('div.form-group');

                $row.each(function(){
                    if ($targetElement.attr('name') == self.buildName(name)){
                        if ($(this).hasClass('has-success')) {
                            $(this).removeClass('has-success');
                        }

                        $(this).addClass('has-error');
                    }
                });
            }
        },
        
        /**
         * Handles server response
         * 
         * @param string response
         * @return void
         */
        handleResponse : function(response){
            this.resetAll();

            try {
                var data = $.parseJSON(response);
                var messages = data.messages;

                this.render(data);

            } catch(e) {
                var messages = response;
            }

            this.displayModal(messages);
        }
    };
    
    $.setFormGroup = function(group){
        $.group = group;
    };

    $.showErrors = function(response){
        errorHandler.handleResponse(response);
        $("#scroller").click();
    }
    
    // Automatic initialization based on element attribute
    $("[data-wysiwyg='true']").each(function(){
        var name = $(this).attr('name');
        $.wysiwyg.init([name]);
    });
    
    $('[data-button="module-install"]').click(function(event){
        event.preventDefault();
        $('[name="module"]').click().change(function(){

            var formData = new FormData();
            formData.append('module', $(this)[0].files[0]);

            $.ajax({
                contentType: false,
                processData: false,
                url : '/admin/kernel/install-module.ajax',
                data : formData,
                success : function(response){
                    if (response == "1"){
                        window.location.reload();
                    } else {
                        console.log(response);
                    }
                }
            });
        });
    });
    
    $("a.mode-link").click(function(event){
        event.preventDefault();
        var mode = $(this).data('mode-id');
        
        $.ajax({
            url : "/admin/kernel/mode-change",
            data : {
                mode : mode
            },
            success : function(response) {
                if (response == "1") {
                    window.location.reload();
                } else {
                    console.log(response);
                }
            }
        });
    });
    
    
    $("[data-button='cancel']").click(function(event){
        event.preventDefault();
        
        var url = $(this).data('url');
        window.location = url;
    });
    
    
    $("[data-button='remove-selected']").click(function(event){
        event.preventDefault();
        var data = $("form").serialize();
        var url = $(this).data('url');
        
        $.ajax({
            url : url,
            data : data,
            success : function(response) {
                if (response == "1") {
                    window.location.reload();
                } else {
                    $.showErrors(response);
                }
            }
        });
    });
    
    $("[data-button='save-changes']").click(function(event){
        event.preventDefault();
        var url = $(this).data('url');
        
        $.ajax({
            url : url,
            data : $("form").serialize(),
            success : function(response) {
                if (response == "1") {
                    window.location.reload();
                } else {
                    $.showErrors(response);
                }
            }
        });
    });
    
    
    $("[data-button='refresh']").click(function(event){
        event.preventDefault();
        window.location.reload();
    });
    
    
    $("[data-button='change-content-language']").click(function(event){
        event.preventDefault();
        // Get selected language id
        var id = $(this).data('language-id');
        
        $.ajax({
            url : "/admin/languages/change.ajax",
            data : {
                id : id
            },
            success : function(response) {
                if (response == "1") {
                    window.location.reload();
                } else {
                    console.log(response);
                }
            }
        });
    });
    
    
    $("[data-toggle='tooltip']").tooltip({
        placement: $(this).data('placement')
    });
    
    
    $("[data-button='slug']").click(function(event){
        event.preventDefault();
        $.ajax({
            url : "/admin/kernel/generate-slug",
            data : {
                title : $("[data-input='title']").val()
            },
            beforeSend : function(){
                // Cancel global beforeSend() with this empty function
            },
            success : function(response){
                $("[data-input='slug']").val(response);
            }
        });
    });
    
    
    $("[data-button='per-page-changer']").change(function(event){
        var value = $(this).val();
        $.ajax({
            url : "/admin/kernel/items-per-page",
            data : {
                count : value,
            },
            success : function(response) {
                if (response == "1") {
                    window.location.reload();
                } else {
                    console.log(response);
                }
            }
        });
    });
    
    $("[data-button='options']").click(function(event) {
        event.preventDefault();
        $("div.options").slideToggle(1000);
    });
    
    
    function add(url, callback) {
        $("form").send({
            url : url,
            success : callback,
            before : function(){
                $.wysiwyg.update();
            }
        });
    }
    
    function update(url, callback) {
        $("form").send({
            url : url,
            success : callback,
            before : function(){
                $.wysiwyg.update();
            }
        });
    }
    
    $("[data-button='add']").click(function(event){
        var url = $(this).data('url');
        var backUrl = $(this).data('back-url');
        
        add(url, function(response) {
            if ($.isNumeric(response)) {
                window.location = backUrl + response;
            } else {
                $.showErrors(response);
            }
        });
    });
    
    $("[data-button='add-create']").click(function(){
        var url = $(this).data('url');
        
        add(url, function(response){
            if ($.isNumeric(response)) {
                window.location.reload();
            } else {
                $.showErrors(response);
            }
        });
    });
    
    $("[data-button='save']").click(function(){
        var url = $(this).data('url');
        
        update(url, function(response) {
            if (response == "1") {
                window.location.reload();
            } else {
                $.showErrors(response);
            }
        });
    });
    
    $("[data-button='save-create']").click(function(){
        var url = $(this).data('url');
        var backUrl = $(this).data('back-url');
        
        update(url, function(response){
            if (response == "1"){
                window.location = backUrl;
            } else {
                $.showErrors(response);
            }
        });
    });
    
    // Removal buttons
    $('[data-button="delete"], [data-button="remove"]').click(function(event){
        event.preventDefault();
        
        var url = $(this).data('url');
        var $self = $(this);
        var $modal = $('#myModal');
        var message = $modal.data('message');
        
        if (!url) {
            throw new Error('URL for delete button is not provided');
        }
        
        // If there's a custom message, then display it instead of default one
        if (message){
            $modal.find('.modal-body').html(message);
        }

        // Then show the modal box
        $modal.modal();
        
        // Then every time attach the click listener
        $("#delete-yes-modal-btn").off('click').click(function(event){
            $.ajax({
                url : url,
                success : function(response) {
                    if (response == "1") {
                        if ($self.data('success-url')) {
                            window.location = $self.data('success-url');
                        } else {
                            // By default
                            window.location.reload();
                        }
                        
                    } else {
                        $.showErrors(response);
                    }
                }
            });
        });
    });

    // Highlight a row on selecting
    $("table > tbody > tr > td > input[type='checkbox']").change(function(){
        // Bootstap class
        var hg = 'warning';
        var $row = $(this).parent().parent();

        if ($(this).prop('checked') == true) {
            $row.addClass(hg);
        } else {
            $row.removeClass(hg);
        }
    });


    $("table > thead > tr > th > input[type='checkbox']").change(function(){
        var $self = $(this);
        var $children = $(this).parent().parent().parent().parent().find("tbody > tr > td:first-child > input[type='checkbox']");
        var state = $self.prop('checked');

        $children.prop('checked', state);
        $self.prop('checked', state);
    });
    
    
    $("td > a.view").click(function(event) {
        event.preventDefault();
    });
    
    $form = $("form");
    
    if ($form.attr('data-group')) {
        $.setFormGroup($form.data('group'));
    }
    
});
