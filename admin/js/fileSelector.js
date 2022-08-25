var media_selector_frame;
const mediaFileSelector = (identifier,tog_flag,file_type) => {
    if (media_selector_frame) media_selector_frame = null;

    media_selector_frame = wp.media({
        title: 'Select CSV/XLSM/XLSX file',
        button: {
            text: 'Insert'
        },
        multiple: false,
        library: {
            type: file_type
        }, 
        uploader: {
            type: file_type
        }
    }).on('select', function () {
        var attachment = media_selector_frame.state().get('selection').first().toJSON();
        if(tog_flag){
            document.getElementById(`ETF-Pre-${identifier}-upload-link`).value = attachment.url;
        }else{
            document.getElementById(`ETF-Pre-${identifier}`).value = attachment.url;
        }
    });
    media_selector_frame.open();
}

const toggle_between_gs_and_up = (in_name) => { 
    let tog = document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML.trim();
    if(tog === "Upload file"){
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML = "Google Sheet";
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).dataset.state = "upload";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).name = `ETF-Pre-${in_name}`;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).required = true;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).name = `ETF-Pre-${in_name}-`;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).required = false;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).style.display = 'block';
        document.getElementById(`ETF-Pre-${in_name}-file-upload`).style.display = 'block';
        document.getElementById(`ETF-Pre-${in_name}-google-link`).style.display = 'none';
    }else{
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).innerHTML = "Upload file";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).name = `ETF-Pre-${in_name}-`;
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).required = false;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).name = `ETF-Pre-${in_name}`;
        document.getElementById(`ETF-Pre-${in_name}-google-link`).required = true;
        document.getElementById(`ETF-Pre-${in_name}-toggle-file-option`).dataset.state = "google";
        document.getElementById(`ETF-Pre-${in_name}-upload-link`).style.display = 'none';
        document.getElementById(`ETF-Pre-${in_name}-file-upload`).style.display = 'none';
        document.getElementById(`ETF-Pre-${in_name}-google-link`).style.display = 'block';
    }

    let toggle_state_a = false;
    if(document.getElementById('ETF-Pre-google-nav-url-toggle-file-option').dataset.state === "google"){
        toggle_state_a = true;
    }

    let toggle_state_b = false;
    if(document.getElementById('ETF-Pre-google-holding-url-toggle-file-option').dataset.state === "google"){
        toggle_state_b = true;
    }

    let toggle_state_d = false;
    if(document.getElementById('ETF-Pre-pdf-disturbion-url-toggle-file-option').dataset.state === "google"){
        toggle_state_d = true;
    }

    document.getElementById(`ETF-Pre-google-nav-url-toggle-data`).value = toggle_state_a;
    document.getElementById(`ETF-Pre-google-holding-url-toggle-data`).value = toggle_state_b;
    document.getElementById(`ETF-Pre-pdf-disturbion-url-toggle-data`).value = toggle_state_d;
}