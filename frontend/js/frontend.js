// IE Polyfills
require('@babel/polyfill');

$ = require('jquery');

$(function () {
    $(document).ready(() => {
        // scroll to top on page load

        var timeline = $('.timeline');
        var duration = timeline.data('duration');
        var permanentActors = $('.set > .actor');
        var scenes = $('.scene');

        // Initialize the play

        $('.actor').each((indexActor, actor) => {
            var minMax = {};
            $(actor).children('.promise').each((index, promise) => {
                var property = $(promise).data('property');
                var value = $(promise).data('start-value');
                var unit = $(promise).data('unit');
                var currentVal = value;
                var min = $(promise).data('start');
                var max = $(promise).data('end')

                if (!minMax[property]) {
                    minMax[property] = {
                        'min': min,
                        'max': max
                    };
                }

                if (min <= minMax[property]['min']) {
                    minMax[property]['min'] = min;
                    $(actor).children('.promise[data-property="'+property+'"]').removeClass('start-value');
                    $(promise).addClass('start-value');
                }

                if (max >= minMax[property]['max']) {
                    minMax[property]['max'] = max;
                    $(actor).children('.promise[data-property="'+property+'"]').removeClass('end-value');
                    $(promise).addClass('end-value')
                }

                if (unit) {
                    currentVal = currentVal+unit;
                }

                $(actor).css(property,currentVal);
            });
        });

        timeline.css('height', duration + 'px');

        scenes.each((index, scene) => {
            var min = duration;
            var max = 0;
            $(scene).children('.actor').each((actorIndex, actor) => {
                $(actor).children('.promise').each((promiseIndex, promise) => {
                    min = Math.min(min, $(promise).data('start'));
                    max = Math.max(min, $(promise).data('end'));
                })
            });

            $(scene).data('start', min);
            $(scene).data('end', max);
        });

        scenes.last().data('end',duration + 10);

        $(window).scrollTop(0);

        resetScene(scenes.first());

        // Start the scrolling
        $(window).on('scroll', () => {
            var currentTime = $(window).scrollTop() / duration * 100;

            // Activate the relevant scenes for better performance
            activateScenes(scenes, currentTime, 1);

            // If scrolled at top, reset first scene, else handle each actor of the active scene(s)
            if (currentTime === 0) {
                resetScene(scenes.first());
                resetScene($('.set'));
            } else {
                $('.scene.active > .actor').each((index, actor) => {
                    handleActor(actor,currentTime);
                });
            }

            // The set is always active, so handle these actors all the time.
            permanentActors.each((index, actor) => {
                handleActor(actor,currentTime);
            });
        })
    })
});

function activateScenes(scenes, currentTime, threshold = 5) {
    scenes.each((index, scene) => {
        if ($(scene).data('start') - threshold < currentTime && $(scene).data('end') + threshold > currentTime) {
            $(scene).addClass('active');
        } else {
            if ($(scene).hasClass('active')) {
                if ($(scene).data('start') > currentTime) {
                    resetScene(scene);
                }
                else {
                    resetScene(scene,'end-value')
                }

                $(scene).removeClass('active');
            }
        }
    });
}

function resetScene(scene, state = 'start-value') {
    $(scene).children('.actor').each((indexActor, actor) => {
        $(actor).children('.promise.'+state).each((index, promise) => {
            var css = $(promise).data('property');
            var value = $(promise).data(state);
            var unit = $(promise).data('unit');
            var currentVal = value;

            if (unit) {
                currentVal = currentVal+unit;
            }

            $(actor).css(css,currentVal);
        });
    });
}

function handleActor(actor, currentTime) {
    $(actor).children('.promise').each((index, promise) => {
        var start = $(promise).data('start');
        var end = $(promise).data('end');
        var startValue = $(promise).data('start-value');
        var endValue = $(promise).data('end-value');
        var unit = $(promise).data('unit');
        var currentPercentage = (start - currentTime) / (start - end);
        var property = $(promise).data('property');
        var currentValue = (1 - currentPercentage) * startValue + currentPercentage * endValue;

        if (start < currentTime && end > currentTime) {
            if (unit) {
                currentValue = currentValue + unit;
            }
            $(promise).addClass('active');
            $(actor).css(property,currentValue);
            return;
        }

        if (end < currentTime && $(promise).hasClass('active')) {
            if (unit) {
                endValue = endValue + unit;
            }
            $(promise).removeClass('active');
            $(actor).css(property,endValue);
            return
        }

        if (start > currentTime && $(promise).hasClass('active')) {
            if (unit) {
                startValue = startValue + unit;
            }
            $(promise).removeClass('active');
            $(actor).css(property,startValue);
        }

    })
}
