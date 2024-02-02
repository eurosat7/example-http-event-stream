jQuery(window).on('load start', () => {
    jQuery('div.progress').not('.progress-loaded').addClass('progress-loaded').each(function () {
        const $bar = jQuery(this);
        const $text = $bar.find('div.text');
        const $meter = $bar.find('div.graph div.meter');
        const urlStart = $bar.data('process-start');
        const urlStatus = $bar.data('process-status');

        let keepon = true;

        $text.load(
            urlStart,
            (response) => {
                $text.html(response);
                keepon = false;
            }
        );

        const getStatus = () => {
            if (!keepon) {
                return false;
            }
            jQuery.getJSON(urlStatus, [], (data) => {
                if (data['max'] === 0 || data['act'] === data.max) {
                    $meter.css('width', '100%');
                    keepon = false;
                    return true;
                }
                let pos = 100 / data['max'] * data['act'];
                $meter.css('width', pos + '%');
                pos = Math.floor(pos);
                $text.html(pos + '%');
                window.setTimeout(getStatus, 1000000);
            });
        };
    });
});
