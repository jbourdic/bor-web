/**
 * Prototype plugin
 */
;(function ($, window, document, undefined) {
    var pluginName = '', defaults = {
    };

    // @method constructor
    function Plugin(element, options) {
        this.el = element;
        this.$el = $(this.el);
        this.opt = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }
    Plugin.prototype = {
        //
        // Initialize the plugin instance
        //
        init: function() {
        },

        //
        // Free resources

        destroy: function() {

        }
    };

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
    }

})(jQuery, window, document);
