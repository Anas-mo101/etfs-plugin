var frame;
const mediaFileSelector = (identifier) => {
    if (frame) {
        frame.open();
        return;
    }
    frame = wp.media({
        title: 'Select PDF file',
        button: { text: 'Insert' },
        multiple: false,
    }).on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        document.getElementById(`ETF-Pre-${identifier}`).value = attachment.url;
    });
    frame.open();
}