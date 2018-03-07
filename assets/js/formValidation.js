var fvId = 1;

$(document).ready(function() {
    $.each($('input'), function() {
        /**
         * fv-not-empty handling
         *
         * The text inside of the quotes is the error message to be added to the input
         * leave this blank to add none
         */
        if(typeof $(this).attr('fv-not-empty') != "undefined") {
            var message = $(this).attr('fv-not-empty');

            $(this).off('keyup').on('keyup', function() {
                if($(this).val().length == 0) {
                    addError(this, message);
                } else {
                    addSuccess(this);
                }
            });
        }

        /**
         * fv-regex handling
         *
         * Supported attributes:
         *  - min
         *  - max
         *  - regex
         */
        if(typeof $(this).attr('fv-advanced') != "undefined") {
            $(this).off('keyup').on('keyup', function() {
                var data = JSON.parse($(this).attr('fv-advanced'));
                var val = $(this).val();
                var message = data.message || "";

                if(typeof data.min != "undefined") {
                    if(val.length < data.min) {
                        addError(this, message);
                        return;
                    } else {
                        addSuccess(this);
                    }
                }

                if(typeof data.max != "undefined") {
                    if(val.length > data.max) {
                        addError(this, message);
                        return;
                    } else {
                        addSuccess(this);
                    }
                }

                if(typeof data.regex != "undefined") {
                    var regex = new RegExp(data.regex);
                    if(val.match(regex)) {
                        addSuccess(this);
                    } else {
                        addError(this, message);
                        return;
                    }
                }
            });
        }

        /**
         * fv-email handling
         */
        if(typeof $(this).attr('fv-email') != "undefined") {
            $(this).off('keyup').on('keyup', function() {
                var message = $(this).attr('fv-email');
                var val = $(this).val();

                if(val.match(/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i) != null) {
                    addSuccess(this);
                } else {
                    addError(this, message);
                    return;
                }
            });
        }

        /**
         * fv-number handling
         */
        if (typeof $(this).attr('fv-number') != 'undefined') {
            $(this).off('keyup').on('keyup', function() {
                var message = $(this).attr('fv-number');
                var val = $(this).val();

                if (isNaN(val) || val.length == 0) {
                    addError(this, message);
                    return;
                } else {
                    addSuccess(this);
                }
            });
        }

        /**
         * fv-alphanum handling
         */
        if (typeof $(this).attr('fv-alphanum') != 'undefined') {
            $(this).off('keyup').on('keyup', function() {
                var message = $(this).attr('fv-alphanum');
                var val = $(this).val();

                if (val.match(/^[a-z0-9]+$/i) != null) {
                    addSuccess(this);
                } else {
                    addError(this, message);
                    return;
                }
            });
        }

        if (typeof $(this).attr('fv-func') != 'undefined') {
            $(this).off('keyup').on('keyup', function() {
                var func = $(this).attr('fv-func');
                var val = $(this).val();

                func = func.replace('this', '"' + val + '"');

                var resp = eval(func);
                if (resp === true) {
                    addSuccess(this);
                } else {
                    addError(this, resp);
                    return;
                }
            });
        }
    });

    /**
     * Add error class and remove success class from input
     * @param self
     * @param message
     */
    function addError(self, message) {
        message = message || '';
        var id = $(self).attr('data-fvid');
        if(typeof id != "undefined") {
            var selector = '.fv-error-message[data-fvid="'+id+'"]';
        } else {
            var selector = '.fv-error-message:not([data-fvid])';
        }

        if($(self).siblings(selector).length === 0) {
            $(self).removeClass('fv-success').addClass('fv-error').attr('data-fvid', fvId).after('<small class="fv-error-message ' + formValidation.errorMessageClasses + '" data-fvId="'+ fvId++ +'">' + message + '</small>');
            $(self).closest('form').find('input[type="submit"]').prop('disabled', true);
        }
    }

    /**
     * Add success class and remove error class from input
     * @param self
     */
    function addSuccess(self) {
        $(self).removeClass('fv-error').addClass('fv-success');
        if($(self).siblings('.fv-error-message').length > 0) {
            var id = $(self).attr('data-fvid');
            $('small[data-fvid="'+id+'"]').remove();
            $(self).removeAttr('data-fvid');
        }
        $(self).closest('form').find('input[type="submit"]').prop('disabled', false);
    }
});

/**
 * Create base formValidation object
 */
var formValidation = {};

/**
 * Create variables with their default values
 * @type {string}
 */
formValidation.errorMessageClasses = "";

/**
 * Add setup function to allow for some data to be passed
 * @param data
 */
formValidation.setup = function(data) {
    /**
     * Check to see if custom classes have been added to error messages, if they have, store them
     * for use later in the script
     */
    if(typeof data.errorMessageClasses != "undefined") {
        formValidation.errorMessageClasses = data.errorMessageClasses;
    }
};

/**
 * Create shorthand variable for lazy people like me
 */
var FV = formValidation;
