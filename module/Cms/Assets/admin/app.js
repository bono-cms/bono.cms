// Global AJAX handler
$(function () {
    const $loader = $("#loader");

    if (!$loader.length) {
        console.warn("#loader does not exist in the DOM.");
        return; // Exit if loader is missing
    }

    // Bootstrap 5 modal instance helper (created lazily, reused)
    function getLoaderModal() {
        return bootstrap.Modal.getOrCreateInstance($loader[0]);
    }

    $.ajaxSetup({
        cache: false,
        charset: "UTF-8",
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        beforeSend: function () {
            getLoaderModal().show();
        },
        complete: function () {
            setTimeout(() => getLoaderModal().hide(), 500);
        },
        error: function (response) {
            console.error("AJAX Error:", response);
        }
    });

    $(document).ajaxStop(() => getLoaderModal().hide());
});


// Tab state persist for Bootstrap 5
(function () {
    var storageKey = 'currentTab';

    // BS5: data-toggle="tab" -> data-bs-toggle="tab"
    $(document).on('click', 'a[data-bs-toggle="tab"]', function (e) {
        var currentTab = $(this).attr('href');
        var activeTabs = (window.localStorage.getItem(storageKey)
            ? window.localStorage.getItem(storageKey).split(',')
            : []);
        var $children = $(e.target).parents('.nav-tabs').find('[data-bs-toggle="tab"]');

        $.each($children, function (index, element) {
            var tabId = $(element).attr('href');
            if (currentTab != tabId && activeTabs.indexOf(tabId) !== -1) {
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
        var activeTabs = (window.localStorage.getItem(storageKey)
            ? window.localStorage.getItem(storageKey).split(',')
            : []);
        $.each(activeTabs, function (index, element) {
            // BS5: use bootstrap.Tab via vanilla API instead of jQuery .tab('show')
            var tabTrigger = document.querySelector('[data-bs-toggle="tab"][href="' + element + '"]');
            if (tabTrigger) {
                bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
            }
        });
    }
})();

// Clipboard module
$(function () {
    /**
     * Copy string to clipboard
     * Credits: https://techoverflow.net/2018/03/30/copying-strings-to-the-clipboard-using-pure-javascript/
     * 
     * @param string str Target string
     * @return void
     */
    function copyStringToClipboard(str) {
        // Prefer modern Clipboard API when available
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(str);
            return;
        }

        // Fallback: legacy execCommand approach
        var el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';

        document.body.appendChild(el);
        el.select();

        document.execCommand('copy');

        document.body.removeChild(el);
    }

    $("[data-button='clipboard']").click(function (event) {
        event.preventDefault();

        var value = $(this).data('value');

        if (value) {
            copyStringToClipboard(value);
        }
    });
});

// Select-group plugin implementation
$(function () {
    // Default configuration
    var config = {
        // BS5 renamed .hidden -> .d-none
        hiddenClass: 'd-none',
        containerSelector: "[data-plugin='group']",
        attachedEntity: 'data-attached-entity',
        entityGroup: 'data-entity-group'
    };

    // Payment handler for ready and change
    $(config.containerSelector).change(function () {
        // Find the selected type
        var entity = $(config.containerSelector).find(':selected').attr(config.attachedEntity);

        // Now process hiding
        $("[data-entity-group]").addClass(config.hiddenClass).each(function () {
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
    }).change();
});

// Application
$(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    if (jQuery().datetimepicker) {
        // Use moment as formatter
        var language = $("[name='language']").val();
        // Default format
        var format = 'YYYY-MM-DD HH:mm:ss';

        if (language) {
            $.datetimepicker.setLocale(language);
        }

        $.datetimepicker.setDateFormatter('moment');

        $('[data-plugin="datetimepicker"]').each(function () {
            // Override if present
            if ($(this).data('format')) {
                format = $(this).data('format');
            }

            $(this).datetimepicker({
                defaultDate: new Date(),
                format: format
            });
        });
    }

    // Run datepicker if loaded
    if (jQuery().datepicker) {
        $('[data-plugin="datepicker"]').each(function () {
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

    // Simple WYSIWYG wrapper
    $.wysiwyg = {
        started: false,
        isStarted: function () {
            return this.started;
        },
        update: function () {
            if (!this.isStarted()) {
                return false;
            }

            if (typeof (CKEDITOR) != 'undefined') {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            } else if (typeof (tinyMCE) != 'undefined') {
                tinyMCE.triggerSave();
            } else {
                // add more later
            }
        },
        init: function (elements) {
            var language = $("input[name='language']").val();

            if (typeof (CKEDITOR) != 'undefined') {
                for (key in elements) {
                    var value = elements[key];
                    CKEDITOR.replace(value, {
                        language: language
                    });
                }

                this.started = true;

            } else if ((typeof tinyMCE != 'undefined')) {
                $.tinyMCE({
                    elements: elements.join(', ')
                });

                this.started = true;
            }
        }
    };

    var errorHandler = {
        displayModal: function (messages) {
            var $modal = $("#errors-modal");

            if ($.isArray(messages)) {
                var text = this.createUl(messages);
            } else {
                var text = messages;
            }

            $modal.find(".modal-body").empty().html(text);

            // BS5: use bootstrap.Modal instead of jQuery .modal("show")
            bootstrap.Modal.getOrCreateInstance($modal[0]).show();
        },

        handleResponse: function (response) {
            console.log(response);
            this.resetAll();

            try {
                var data = (typeof response === 'object') ? response : $.parseJSON(response);

                var errorList = null;
                if (Array.isArray(data)) {
                    errorList = data;
                } else if (data && data.errors && Array.isArray(data.errors)) {
                    errorList = data.errors;
                }

                if (errorList) {
                    var modalMessages = [];

                    for (var i = 0; i < errorList.length; i++) {
                        var errorItem = errorList[i];
                        var fieldName = errorItem.input;
                        var errorMessage = errorItem.message;

                        modalMessages.push(errorMessage);

                        this.highlightField(fieldName, errorMessage);
                    }

                    if (modalMessages.length > 0) {
                        this.displayModal(modalMessages.join('<br>'));
                    }

                    if (!Array.isArray(data)) {
                        this.render(data);
                    }

                } else if (data && data.messages) {
                    this.displayModal(data.messages);
                }

            } catch (e) {
                this.displayModal(response);
            }
        },

        // Bootstrap 5 error state highlight (is-invalid + invalid-feedback still valid in BS5)
        highlightField: function (fieldName, message) {
            var safeSelector = fieldName.replace(/(:|\.|\[|\]|,|=)/g, "\\$1");
            var $input = $('[name="' + safeSelector + '"]');

            if ($input.length) {
                // is-invalid works the same in BS5
                $input.addClass('is-invalid');

                // BS5: .invalid-feedback must be a sibling AFTER .input-group, not nested inside it.
                // Add .has-validation to the .input-group so it doesn't collapse the border-radius.
                var $inputGroup = $input.closest('.input-group');
                if ($inputGroup.length) {
                    $inputGroup.addClass('has-validation');
                    $inputGroup.after('<div class="invalid-feedback d-block">' + message + '</div>');
                } else {
                    $input.after('<div class="invalid-feedback d-block">' + message + '</div>');
                }
            }
        },

        resetAll: function () {
            // BS5: .form-control, .form-select and .form-check-input all use is-invalid
            $('.form-control, .form-select, .form-check-input').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.input-group').removeClass('has-validation');
        }
    };

    $.setFormGroup = function (group) {
        $.group = group;
    };

    $.showErrors = function (response) {
        errorHandler.handleResponse(response);
        $("#scroller").click();
    };

    // Shared chosen plugin
    if ($.fn.chosen) {
        $("[data-plugin='chosen']").chosen({
            disable_search_threshold: 5,
            width: "100%"
        });
    }

    // Automatic initialization based on element attribute
    $("[data-wysiwyg='true']").each(function () {
        var name = $(this).attr('name');
        $.wysiwyg.init([name]);
    });

    $("[data-button='upload']").click(function (event) {
        event.preventDefault();

        var selector = $(this).data('target');

        $(selector).click();
    });

    $("[data-button='generate']").click(function (event) {
        event.preventDefault();

        var url = $(this).data('url');
        var output = $(this).data('output');

        $.ajax({
            url: url,
            success: function (response) {
                $(output).text(response);
            }
        });
    });

    // Shared response handler
    function handleResponse(response) {
        console.log(response);

        // 1. Modern structured object responses
        if (typeof response === 'object' && response !== null) {
            if (response.errors) {
                $.showErrors(response.errors);
                return false;
            }
            if (response.redirect) {
                window.location = response.redirect;
                return false;
            }
            if (response.refresh) {
                window.location.reload();
                return false;
            }
        }

        // 2. Standard legacy check
        if (response == "1") {
            window.location.reload();
            return false;
        } else {
            $.showErrors(response);
            return false;
        }
    }

    $('[data-button="module-install"]').click(function (event) {
        event.preventDefault();
        $('[name="module"]').click().change(function () {

            var formData = new FormData();
            formData.append('module', $(this)[0].files[0]);

            $.ajax({
                contentType: false,
                processData: false,
                url: $(this).data('url'),
                data: formData,
                success: function (response) {
                    handleResponse(response);
                }
            });
        });
    });

    $("[data-button='mode']").click(function (event) {
        event.preventDefault();
        var mode = $(this).data('mode-id');

        $.ajax({
            url: $(this).data('url'),
            data: {
                mode: mode
            },
            success: function (response) {
                handleResponse(response);
            }
        });
    });


    $("[data-button='cancel']").click(function (event) {
        event.preventDefault();

        var url = $(this).data('url');
        window.location = url;
    });


    $("[data-button='refresh']").click(function (event) {
        event.preventDefault();
        window.location.reload();
    });

    $("[data-button='change-content-language']").click(function (event) {
        event.preventDefault();
        var id = $(this).data('language-id');

        $.ajax({
            url: $(this).data('url'),
            data: {
                id: id
            },
            success: function (response) {
                handleResponse(response);
            }
        });
    });

    // BS5: tooltips initialized via bootstrap.Tooltip. Iterate manually.
    document.querySelectorAll("[data-bs-toggle='tooltip']").forEach(function (el) {
        bootstrap.Tooltip.getOrCreateInstance(el);
    });

    // Run slug update on click
    $("[data-slug-selector]").click(function (event) {
        event.preventDefault();

        // BS5: form-group is gone; find the closest wrapper by utility class only
        var $input = $(this).closest('.mb-3, .row').find("input");

        if ($input.length == 0) {
            throw new Error('Could not find closest input element that contains slug');
        }

        var selector = $(this).attr('data-slug-selector');
        var raw = $(selector).val();
        var url = $("[name='slug-refresh-url']").val();

        $.ajax({
            method: "GET",
            url: url,
            data: {
                raw: raw
            },
            beforeSend: function () {
                // Cancel global beforeSend() with this empty function
            },
            success: function (response) {
                $input.val(response);
            }
        });
    });

    // Refactored
    $("[data-button='per-page-changer']").change(function (event) {
        var value = $(this).val();
        $.ajax({
            url: $(this).data('url'),
            data: {
                count: value,
            },
            success: function (response) {
                handleResponse(response);
            }
        });
    });

    $("[data-button='options']").click(function (event) {
        event.preventDefault();
        $("div.options").slideToggle(1000);
    });


    // Refactored
    $("[data-button='save-changes']").click(function (event) {
        event.preventDefault();
        var url = $(this).data('url');

        $.ajax({
            url: url,
            data: $("form").serialize(),
            success: function (response) {
                handleResponse(response);
            }
        });
    });

    // Refactored.
    $("[data-button='remove-selected']").click(function (event) {
        event.preventDefault();
        var data = $("form").serialize();
        var url = $(this).data('url');

        $.ajax({
            url: url,
            data: data,
            success: function (response) {
                handleResponse(response);
            }
        });
    });

    // Refactored.
    $(document).on('click', "[data-button]", function (event) {
        var $btn = $(this);
        var action = $btn.data('button');
        var url = $btn.data('url');
        var backUrl = $btn.data('back-url');

        $("form").send({
            url: url,
            before: function () {
                $.wysiwyg.update();
            },
            success: function (response) {
                // 1. Modern response handling (structured objects)
                if (typeof response === 'object' && response !== null) {
                    if (response.errors) {
                        $.showErrors(response.errors);
                        return;
                    }
                    if (response.redirect) {
                        window.location = response.redirect;
                        return;
                    }
                    if (response.refresh) {
                        window.location.reload();
                        return;
                    }
                    return;
                }

                // 2. Legacy response validation (inlined)
                var isSuccess = (action === 'add' || action === 'add-create')
                    ? $.isNumeric(response)
                    : (response == "1");

                if (!isSuccess) {
                    $.showErrors(response);
                    return;
                }

                // 3. Legacy routing switch
                switch (action) {
                    case 'add':
                        window.location = backUrl + response;
                        break;
                    case 'add-create':
                        window.location.reload();
                        break;
                    case 'save':
                        window.location.reload();
                        break;
                    case 'save-create':
                        window.location = backUrl;
                        break;
                }
            }
        });
    });


    // Removal buttons
    $('[data-button="delete"], [data-button="remove"]').click(function (event) {
        event.preventDefault();

        var url = $(this).data('url') || $(this).attr('href');
        var $self = $(this);
        var $modal = $('#confirmation-modal');
        var message = $(this).data('message');

        if (!url) {
            throw new Error('URL for delete button is not provided');
        }

        // If there's a custom message, then display it instead of default one
        if (message) {
            $modal.find('.modal-body').html(message);
        }

        // BS5: show modal via bootstrap.Modal API
        bootstrap.Modal.getOrCreateInstance($modal[0]).show();

        $("[data-button='confirm-removal']").off('click').click(function (event) {
            $.ajax({
                url: url,
                success: function (response) {
                    handleResponse(response);

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

    $("[data-button='ajax-view']").click(function (event) {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            success: function (response) {
                var $modal = $("#errors-modal");

                $modal.find(".modal-body").empty().html(response);

                // BS5 modal show
                bootstrap.Modal.getOrCreateInstance($modal[0]).show();
            }
        });
    });

    // Table rows
    (function () {
        var outputSelector = ".selected-counter";
        var headCheckboxSelector = "table > thead > tr > td > input[type='checkbox']";
        var rowCheckboxSelector = "table > tbody > tr > td > input[type='checkbox']";

        // Recompute the total selected count from actual DOM state to avoid stale counters
        function updateCounter() {
            var total = $(rowCheckboxSelector + ":checked").length;
            $(outputSelector).text(total > 0 ? '(' + total + ')' : null);
        }

        // Highlight a row on selecting
        $(rowCheckboxSelector).change(function () {
            // BS5: table-danger remains the correct contextual class
            var hg = 'table-danger';
            var $row = $(this).parent().parent();

            if ($(this).prop('checked') == true) {
                $row.addClass(hg);
            } else {
                $row.removeClass(hg);
            }

            updateCounter();
        });

        $(headCheckboxSelector).change(function () {
            var $self = $(this);
            var $children = $(this).parent().parent().parent().parent()
                .find("tbody > tr > td:first-child > input[type='checkbox']");
            var state = $self.prop('checked');

            $children.prop('checked', state);
            $self.prop('checked', state);

            $(rowCheckboxSelector).change();
            updateCounter();
        });
    })();

    $("td > a.view").click(function (event) {
        event.preventDefault();
    });

    $form = $("form");

    // BS5: data-group may have been removed from forms; guard against undefined
    if ($form.attr('data-group')) {
        $.setFormGroup($form.data('group'));
    }

    // If preview plugin is loaded
    if (jQuery().preview) {
        $("[data-plugin='preview']").each(function () {
            $(this).preview(function (data) {
                $("[data-image='preview']").fadeIn(1000).attr('src', data);
            });
        });
    }
});