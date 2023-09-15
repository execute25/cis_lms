// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

var pluginModule = (function () {

    function init() {
        eventBind();
        easyPieChartReload()
    }

    function eventBind() {
    }

    function  easyPieChartReload() {

        var updatePie = function ($that) {
            var $this = $that,
                $text = $('span', $this),
                $oldValue = $text.html(),
                $newValue = Math.round(100 * Math.random());

            $this.data('easyPieChart').update($newValue);
            $({v: $oldValue}).animate({v: $newValue}, {
                duration: 1000,
                easing: 'swing',
                step: function () {
                    $text.text(Math.ceil(this.v));
                }
            });
        };

        $('.easypiechart').each(function () {

            var $barColor = $(this).data("barColor") || function ($percent) {
                    $percent /= 100;
                    return "rgb(" + Math.round(255 * (1 - $percent)) + ", " + Math.round(255 * $percent) + ", 125)";
                },
                $trackColor = $(this).data("trackColor") || "#c8d2db",
                $scaleColor = $(this).data("scaleColor"),
                $lineWidth = $(this).data("lineWidth") || 12,
                $size = $(this).data("size") || 130,
                $animate = $(this).data("animate") || 1000;

            $(this).easyPieChart({
                barColor: $barColor,
                trackColor: $trackColor,
                scaleColor: $scaleColor,
                lineCap: 'butt',
                lineWidth: $lineWidth,
                size: $size,
                animate: $animate,
                onStop: function () {
                    var $this = this.$el;
                    $this.data("loop") && setTimeout(function () {
                        $this.data("loop") && updatePie($this)
                    }, 2000);
                }
            });
        });
    }
    setTimeout(function () {
        easyPieChartReload()
    }, 100);

    return {
        init: init,
        easyPieChartReload: easyPieChartReload,
    };
})();

$(function () {
    pluginModule.init();
})

// Place any jQuery/helper plugins in here.
