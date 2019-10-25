
// Tab state persist for Bootstrap 4
(function(){
    var storageKey = 'currentTab';

    $('a[data-toggle="tab"]').on('click', function (e) {
        var currentTab = $(this).attr('href');
        var activeTabs = (window.localStorage.getItem(storageKey) ? window.localStorage.getItem(storageKey).split(',') : []);
        var $children = $(e.target).parents('.nav-tabs').find('[data-toggle="tab"]');

        $.each($children, function(index, element){
            var tabId = $(element).attr('href');
            if(currentTab != tabId && activeTabs.indexOf(tabId) !== -1) {
                activeTabs.splice(activeTabs.indexOf(tabId), 1);
            }
        });

        if (activeTabs.indexOf($(e.target).attr('href')) === -1) {
            activeTabs.push($(e.target).attr('href'));
        }

        window.localStorage.setItem(storageKey, activeTabs.join(','));
    });

    var activeTabs = window.localStorage.getItem(storageKey);

    if (activeTabs) {
        var activeTabs = (window.localStorage.getItem(storageKey) ? window.localStorage.getItem(storageKey).split(',') : []);
        $.each(activeTabs, function (index, element) {
            $('[data-toggle="tab"][href="' + element + '"]').tab('show');
        });
    }
})();

// Clipboard module
$(function(){
    /**
     * Copy string to clipboard
     * Credits: https://techoverflow.net/2018/03/30/copying-strings-to-the-clipboard-using-pure-javascript/
     * 
     * @param string str Target string
     * @return void
     */
    function copyStringToClipboard(str){
        // Create new element
        var el = document.createElement('textarea');
        // Set value (string to be copied)
        el.value = str;
        // Set non-editable to avoid focus and move outside of view
        el.setAttribute('readonly', '');
        el.style = {position: 'absolute', left: '-9999px'};
        
        document.body.appendChild(el);
        // Select text inside element
        el.select();

        // Copy text to clipboard
        document.execCommand('copy');

        // Remove temporary element
        document.body.removeChild(el);
    }

    $("[data-button='clipboard']").click(function(event){
        event.preventDefault();

        var value = $(this).data('value');

        if (value) {
            copyStringToClipboard(value)
        }
    });
});

// Select-group plugin implementation
$(function(){
    // Default configuration
    var config = {
        hiddenClass: 'hidden',
        containerSelector: "[data-plugin='group']",
        attachedEntity: 'data-attached-entity',
        entityGroup: 'data-entity-group'
    };


    // Payment handler for ready and change
    $(config.containerSelector).change(function(){
        // Find the selected type
        var entity = $(config.containerSelector).find(':selected').attr(config.attachedEntity);

        // Now process hiding
        $("[data-entity-group]").addClass(config.hiddenClass).each(function(){
            // Find attached groups
            var group = $(this).attr(config.entityGroup);
            var groups = group.split(', ');

            for (var key in groups) {
                // A single group without spaces
                var singleGroup = groups[key].trim();

                if (entity == singleGroup) {
                    $(this).removeClass(config.hiddenClass);
                }
            }
        });

        // And trigger immediately
    }).change();
});

// Application
$(function(){
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    if (jQuery().datetimepicker) {
        $('[data-plugin="datetimepicker"]').datetimepicker({
            defaultDate: new Date(),
            format: 'DD-MM-YYYY hh:mm:ss',
        });
    }

    // Run datepicker if loaded
    if (jQuery().datepicker) {
        $('[data-plugin="datepicker"]').each(function(){
            // Default date format
            var format = 'yyyy-mm-dd';

            // Override if present
            if ($(this).data('format')) {
                format = $(this).data('format');
            }

            $(this).datepicker({
                format: format
            });
        });
    }

    // Global settings for the whole panel
    $.ajaxSetup({
        cache : false,
        charset : "UTF-8",
        type : "POST",
        beforeSend  : function() {
            $("#loader").modal("show");
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        complete : function() {
            $('#loader').on('shown.bs.modal', function(e){
                $(this).modal("hide");
            });
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
            $("div.form-group").removeClass('has-danger').addClass('has-success');
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

                        $(this).addClass('has-danger');
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

    // Shared chosen plugin
    if ($.fn.chosen){
        $("[data-plugin='chosen']").chosen({
            disable_search_threshold: 5,
            width: "100%"
        });
    }
    
    // Automatic initialization based on element attribute
    $("[data-wysiwyg='true']").each(function(){
        var name = $(this).attr('name');
        $.wysiwyg.init([name]);
    });
    
    $("[data-button='upload']").click(function(event){
        event.preventDefault();

        // Grab the attached selector
        var selector = $(this).data('target');

        // And trigger clicking
        $(selector).click();
    });

    $("[data-button='generate']").click(function(event){
        event.preventDefault();

        var url = $(this).data('url');
        var output = $(this).data('output'); // Output selector

        $.ajax({
            url: url,
            success: function(response){
                $(output).text(response);
            }
        });
    });

    $('[data-button="module-install"]').click(function(event){
        event.preventDefault();
        $('[name="module"]').click().change(function(){

            var formData = new FormData();
            formData.append('module', $(this)[0].files[0]);

            $.ajax({
                contentType: false,
                processData: false,
                url : $(this).data('url'),
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
    
    $("[data-button='mode']").click(function(event){
        event.preventDefault();
        var mode = $(this).data('mode-id');
        
        $.ajax({
            url : $(this).data('url'),
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
            url : $(this).data('url'),
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

    $("[data-toggle='tooltip']").tooltip();

    // Run slug update on click
    $("[data-slug-selector]").click(function(event){
        event.preventDefault();

        // Value container
        var $input = $(this).parentsUntil(".form-group").find("input"); // Assume, there must be only one input per unique selector

        // Make sure input with provided selector really exist, first
        if ($input.length == 0) {
            throw new Error('Could not find closest input element that contains slug');
        }

        var selector = $(this).attr('data-slug-selector');  // Target selector
        var raw = $(selector).val(); // Raw value from selector
        var url = $("[name='slug-refresh-url']").val(); // This input is available in layout globally

        $.ajax({
            method: "GET",
            url : url, 
            data : {
                raw : raw
            },
            beforeSend : function(){
                // Cancel global beforeSend() with this empty function
            },
            success : function(response){
                // Update selector's value
                $input.val(response);
            }
        });
    });

    $("[data-button='per-page-changer']").change(function(event){
        var value = $(this).val();
        $.ajax({
            url : $(this).data('url'),
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

        var url = $(this).data('url') || $(this).attr('href');
        var $self = $(this);
        var $modal = $('#confirmation-modal');
        var message = $(this).data('message');

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
        $("[data-button='confirm-removal']").off('click').click(function(event){
            $.ajax({
                url : url,
                success : function(response) {
                    if (response == "1") {
                        if ($self.data('back-url')) {
                            window.location = $self.data('back-url');
                            return false;
                        }

                        if ($self.data('success-url')) {
                            window.location = $self.data('success-url');
                            return false;
                        }
                        
                        // By default
                        window.location.reload();
                        
                    } else {
                        $.showErrors(response);
                    }
                }
            });
        });
    });

    $("[data-button='ajax-view']").click(function(event){
        event.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            success: function(response){
                var $modal = $("#errors-modal");

                $modal.find(".modal-body").empty().html(response);
                $modal.modal("show");
            }
        });
    });

    // Table rows
    (function(){
        // Counter of checked items
        var counter = 0;
        var outputSelector = ".selected-counter";
        var headCheckboxSelector = "table > thead > tr > td > input[type='checkbox']";

        // Highlight a row on selecting
        $("table > tbody > tr > td > input[type='checkbox']").change(function(){
            // Bootstap class
            var hg = 'table-danger';
            var $row = $(this).parent().parent();

            if ($(this).prop('checked') == true) {
                $row.addClass(hg);
            } else {
                $row.removeClass(hg);
            }

            // Depending on state, increment or decrement the counter
            if ($(this).is(':checked')) {
                counter++;
            } else {
                counter--;
            }

            if (counter < 0) {
                counter = 0;
            }

            // Format output
            if (counter > 0) {
                var text = '(' + counter + ')';
            } else {
                var text = null;
            }

            // Update value
            $(outputSelector).text(text);
        });

        $(headCheckboxSelector).change(function(){
            var $self = $(this);
            var $children = $(this).parent().parent().parent().parent().find("tbody > tr > td:first-child > input[type='checkbox']");
            var state = $self.prop('checked');

            $children.prop('checked', state);
            $self.prop('checked', state);

            // Update state
            $("table > tbody > tr > td > input[type='checkbox']").change();
        });
    })();
    
    $("td > a.view").click(function(event) {
        event.preventDefault();
    });
    
    $form = $("form");
    
    if ($form.attr('data-group')) {
        $.setFormGroup($form.data('group'));
    }

    // If preview plugin is loaded
	if (jQuery().preview){
        $("[data-plugin='preview']").each(function(){
            $(this).preview(function(data) {
                $("[data-image='preview']").fadeIn(1000).attr('src', data);
            });
        });
    }
});
