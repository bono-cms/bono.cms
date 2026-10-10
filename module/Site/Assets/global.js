// Make sure jQuery is available
if (!(window.jQuery)) {
    throw new Error('jQuery is not loaded. Halting execution');
}

$(function () {
    /**
     * Validator constructor
     *
     * @param object $form jQuery form object
     * @return void
     */
    function Validator($form) {
        this.$form = $form;
    }

    Validator.prototype = {
        /**
         * Builds a scoped name-selector for a target element
         *
         * @param string name Element name
         * @return string
         */
        selector: function (name) {
            return '[name="' + name + '"]';
        },

        /**
         * All inputs matching a name inside the form
         *
         * @param string name
         * @return object
         */
        inputsOf: function (name) {
            return this.$form.find(this.selector(name));
        },

        /**
         * Whether the name refers to an array input (name[])
         *
         * @param string name
         * @return boolean
         */
        isArray: function (name) {
            var $el = this.inputsOf(name + '[]');
            return $el.length > 0 && $el.attr('name').indexOf('[]') !== -1;
        },

        /**
         * Whether the name refers to a radio input
         *
         * @param string name
         * @return boolean
         */
        isRadio: function (name) {
            return this.inputsOf(name).attr('type') === 'radio';
        },

        /**
         * Applies an error onto the matching field
         *
         * Bootstrap 5 notes:
         *   - .is-invalid must be placed on the <input> itself
         *   - .invalid-feedback is only revealed via the sibling selector
         *     (input.is-invalid ~ .invalid-feedback), so the message must
         *     be appended to a shared parent of the input.
         *
         * @param string name    HTML bracket name, e.g. field[3]
         * @param string message Error text to display
         * @param string rule    Optional rule identifier
         * @return void
         */
        showErrorOn: function (name, message, rule) {
            var isArray = this.isArray(name);
            var isRadio = this.isRadio(name);

            // Collection inputs carry a [] suffix
            if (isArray) {
                name += '[]';
            }

            var $inputs = this.inputsOf(name);

            if (!$inputs.length) {
                return;
            }

            // Tag every input so Bootstrap renders the red border
            $inputs.removeClass('is-valid')
                   .addClass('is-invalid');

            if (rule) {
                $inputs.attr('data-error-rule', rule);
            }

            // Radios and arrays: highlight only, no message block
            if (isRadio || isArray) {
                return;
            }

            // Remove any stale message before appending a fresh one
            $inputs.siblings('.invalid-feedback').remove();

            var $span = $('<span>').addClass('invalid-feedback').text(message);

            if (rule) {
                $span.attr('data-rule', rule);
            }

            $inputs.last().after($span);
        },

        /**
         * Renders a batch of error objects from the server
         *
         * @param array errors Collection of {input, label, rule, message, value, params}
         * @return void
         */
        renderErrors: function (errors) {
            for (var i = 0; i < errors.length; i++) {
                var e = errors[i];
                if (e && e.input && e.message) {
                    this.showErrorOn(e.input, e.message, e.rule);
                }
            }
        },

        /**
         * Clears all previous error state
         *
         * @return void
         */
        resetAll: function () {
            this.$form.find('.is-invalid, .is-valid')
                      .removeClass('is-invalid is-valid')
                      .removeAttr('data-error-rule');

            this.$form.find('.invalid-feedback').remove();
        },

        /**
         * Dispatches the server response onto the form
         *
         * Supported payloads:
         *   - { refresh: true }                                  -> reload
         *   - { redirect: url } | { backUrl: url }               -> navigate
         *   - { errors: [ {input, label, rule, message, ...} ] } -> render errors
         *
         * @param object response JSON-parsed server response
         * @param object $form    jQuery form object
         * @return void
         */
        handleAll: function (response, $form) {
            this.resetAll();

            if (response && typeof response === 'object' && !Array.isArray(response)) {
                if (response.redirect) {
                    window.location = response.redirect;
                    return;
                }

                if (response.refresh) {
                    window.location.reload();
                    return;
                }

                if (Array.isArray(response.errors)) {
                    this.renderErrors(response.errors);
                    return;
                }
            }

            console.log(response);

            if ($form.data('submit') == '1') {
                $form.off('submit').submit();
            }
        }
    };

    // Setup global AJAX settings
    $.ajaxSetup({
        cache: false,
        charset: 'UTF-8',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            if ($.isFunction($.fn.modal)) {
                $('#ajax-modal').modal('show');
            }
        },
        complete: function () {
            if ($.isFunction($.fn.modal)) {
                $('#ajax-modal').modal('hide');
            }
        }
    });

    // CAPTCHA refresh button
    $("[data-captcha='button-refresh']").click(function (event) {
        event.preventDefault();

        var $image = $("[data-captcha='image']");
        $image.attr('src', $image.attr('src') + Math.random());
    });

    // AJAX form submission
    $("[data-button='submit']").click(function () {
        var $form = $(this).closest('form');
        var $button = $(this);

        $form.off('submit').submit(function (event) {
            event.preventDefault();

            var $self = $(this);

            $.ajax({
                url: $self.attr('action') || '',
                type: $self.attr('method') || 'POST',
                contentType: false,
                processData: false,
                data: new FormData(this),
                beforeSend: function () {
                    $button.addClass('disabled').prop('disabled', true);
                },
                complete: function () {
                    $button.removeClass('disabled').prop('disabled', false);
                },
                success: function (response) {
                    var validator = new Validator($self);
                    validator.handleAll(response, $self);
                }
            });
        });
    });

    // Work flawlessly with <BASE> tag, if one exists
    if ($("base").length) {
        $("a[href^='\#']").each(function () {
            this.href = location.href.split('#')[0] + '#' + this.href.substr(this.href.indexOf('#') + 1);
        });
    }
});