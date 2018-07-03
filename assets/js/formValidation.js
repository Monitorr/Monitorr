var fvId = 1;

$(document).ready(function() {
    $.each($('input'), function() {
        this.validations = 0;

        /**
         * fv-not-empty handling
         *
         * The text inside of the quotes is the error message to be added to the input
         * leave this blank to add none
         */
        if(typeof $(this).attr('fv-not-empty') != "undefined") {
            this.validations++;

            var message = $(this).attr('fv-not-empty');

            $(this).on('keyup', function() {
                if($(this).val().length == 0) {
                    addError(this, message);
                    return;
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
            this.validations++;

            $(this).on('keyup', function() {
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

                    var reverse = false;
                    if (typeof data.regex_reverse != "undefined") {
                        reverse = data.regex_reverse;
                    }

                    if(val.match(regex)) {
                        if (!reverse) {
                            addSuccess(this);
                        } else {
                            addError(this, message);
                        }
                    } else {
                        if (!reverse) {
                            addError(this, message);
                            return;
                        } else {
                            addSuccess(this);
                        }
                    }
                }
            });
        }

        /**
         * fv-email handling
         *
         * fv-simple-email is a simple check to see if `@` exists in the input.
         * fv-email is a more complicated regex taken from here: http://emailregex.com/#disqus_thread
         */
        if (typeof $(this).attr('fv-simple-email') != "undefined") {
            this.validations++;

            $(this).on('keyup', function() {
                var message = $(this).attr('fv-simple-email');
                var val = $(this).val();

                if (val.match(/@/) != null) {
                    addSuccess(this);
                } else {
                    addError(this, message);
                }
            });
        }

        if(typeof $(this).attr('fv-email') != "undefined") {
            this.validations++;

            $(this).on('keyup', function() {
                var message = $(this).attr('fv-email');
                var val = $(this).val();

                if(val.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/) != null) {
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
            this.validations++;

            $(this).on('keyup', function() {
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
            this.validations++;

            $(this).on('keyup', function() {
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
            this.validations++;

            $(this).on('keyup', function() {
                var func = $(this).attr('fv-func');
                var val = $(this).val();

                func = func.replace('this', '"' + val + '"');

                var resp = Function('"use strict";return(' + func + ')')();
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
        self.error = true;

        // If fv-valid-func exists then run the JS method
        if ($(self).attr('fv-invalid-func') != "undefined") {
            var func = $(self).attr('fv-invalid-func');

            Function('"use strict";return(' + func + ')')();
        }

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
        } else {
            // Error message already exists so replace text
            $(self).siblings(selector).text(message);
        }
    }

    /**
     * Add success class and remove error class from input
     * @param self
     */
    function addSuccess(self) {
        if (!self.error | self.validations == 1) {
            // If fv-valid-func exists then run the JS method
            if ($(self).attr('fv-valid-func') != "undefined") {
                var func = $(self).attr('fv-valid-func');

                Function('"use strict";return(' + func + ')')();
            }

            $(self).removeClass('fv-error').addClass('fv-success');
            if($(self).siblings('.fv-error-message').length > 0) {
                var id = $(self).attr('data-fvid');
                $('small[data-fvid="'+id+'"]').remove();
                $(self).removeAttr('data-fvid');
            }
            $(self).closest('form').find('input[type="submit"]').prop('disabled', false);
        } else {
            self.error = false;
        }
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
