// IE Polyfills
require('@babel/polyfill');

$ = require('jquery');

$(() => {
    $(document).ready(() => {
        let scrollDown = true;
        let lastScroll = 0;
        let actors = $('.actor');

        actors.each((index, actor) => {
            let currentActor = $(actor);
            currentActor.data('relevant-start',100);
            currentActor.data('relevant-stop',0);
            currentActor.children('.promise').each((index, promise) => {
                let currentPromise = $(promise);
                let currentStart = 100;
                currentPromise.children('.data').each((index, data) => {
                    let currentData = $(data);

                    if (currentData.data('start') < currentActor.data('relevant-start')) {
                        currentActor.data('relevant-start',currentData.data('start'));
                    }

                    if (currentData.data('stop') > currentActor.data('relevant-stop')) {
                        currentActor.data('relevant-stop',currentData.data('stop'));
                    }

                    if (currentStart > currentData.data('start')) {
                        currentActor.css(currentPromise.data('property'),currentData.data('start-value')+currentPromise.data('unit'));
                        currentStart = currentData.data('start');
                    }
                });
            });
        });

        $(window).scrollTop(0);

        $(window).scroll(() => {
            let scroll = $(window).scrollTop() / $('#stage').outerHeight() * 100;
            scrollDown = lastScroll < scroll;
            actors.removeClass('active');
            actors.filter((index, actor) => {
                let currentActor = $(actor);
                return (currentActor.data('relevant-start') < scroll && currentActor.data('relevant-stop') > scroll)

            }).addClass('active');

            actors.filter(':not(.active)').each((index, actor) => {
                let currentActor = $(actor);
                currentActor.children('.promise').each((index, promise) => {
                    let currentPromise = $(promise);
                    let firstData = currentPromise.children().first();
                    let lastData = currentPromise.children().last();

                    if (firstData.data('start') > scroll) {
                        currentActor.css(currentPromise.data('property'),firstData.data('start-value')+currentPromise.data('unit'));
                        return;
                    }

                    if (firstData.data('stop') < scroll) {
                        currentActor.css(currentPromise.data('property'),lastData.data('stop-value')+currentPromise.data('unit'));
                    }
                });
            });

            actors.filter('.active').each((index, actor) => {
                let currentActor = $(actor);
                currentActor.children('.promise').each((index, promise) => {
                    let currentPromise = $(promise);

                    currentPromise.children().each((index, data) => {
                        let currentData = $(data);
                        let start = currentData.data('start');
                        let stop = currentData.data('stop');

                        if (start < scroll && stop > scroll) {
                            let valPercentage = (scroll - start) / (stop - start);
                            let startVal = currentData.data('start-value');
                            let stopVal = currentData.data('stop-value');
                            let currentVal = (1 - valPercentage) * startVal + valPercentage * stopVal;
                            currentActor.css(currentPromise.data('property'),currentVal+currentPromise.data('unit'));
                        }
                    })
                });
            });
        });
    });
});
