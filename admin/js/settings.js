let toggle_and_not_saved = false;
const baseURL = "https://" + window.location.hostname + "/wp-json/etf-rest/v1";

document.addEventListener("DOMContentLoaded", () => {
    const addConnectionButton = document.getElementById('ETF-Pre-add-connection-button');
    if (addConnectionButton) {
        addConnectionButton.addEventListener('click', () => {
            const popup = document.querySelector(".ETF-Pre-general-popup-underlay");
            popup.style.display = "flex";
        });
    }

    document.querySelectorAll('.delete-connection').forEach((toggleFile) => {
        toggleFile.addEventListener('click', (e) => {
            const id = e.target.dataset.connection;

            document.getElementById("ETF-Pre-popup-underlay-del-fund-field").style.display = "flex";

            document.getElementById('connection-delete-button').addEventListener('click', (e) => delete_connection(id));
        });
    });

    document.getElementById('name-submit-button').addEventListener('click', async (e) => {
        const name = document.getElementById('new-connection-name').value;
        await add_new_connection(name);
    });


    document.querySelectorAll('.cancel-file-button').forEach(button => {
        button.addEventListener('click', (e) => cancel_file(e.target.dataset.connection));
        cancel_file(button.dataset.connection);
    });

    document.querySelectorAll('.ETFs-Pre-auto').forEach(button => {
        button.addEventListener('click', toggle_switch_text);
    });

    document.querySelectorAll('.ETFs-Pre-toggle-file-view').forEach(toggleFile => {
        toggleFile.addEventListener('change', toggle_file_view);
    });

    document.querySelectorAll('.cancel-button').forEach(button => {
        button.addEventListener('click', (e) => cancel_onclick(e.target.dataset.connection));
        cancel_onclick(button.dataset.connection, false);
    });

    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', edit_onclick);
    });

    document.querySelectorAll('.diz-cancel-button').forEach(button => {
        button.addEventListener('click', cancel_divs_edits);
    });

    document.querySelectorAll('.fp-cancel-button').forEach(button => {
        button.addEventListener('click', () => cancel_fp_edits(true));
    });

    document.querySelectorAll('.edit-file-button').forEach(button => {
        button.addEventListener('click', edit_file_button);
    });

    document.querySelectorAll('.update-files-button').forEach(button => {
        button.addEventListener('click', update_files);
    });

    document.querySelectorAll('.save-button').forEach(button => {
        button.addEventListener('click', save_button);
    });

    document.querySelectorAll('.scan-dir-button').forEach(button => {
        button.addEventListener('click', scan_dir_button);
    });


    /// ==================================

    cancel_fp_edits();
    cancel_divs_edits();

    document.querySelectorAll('.diz-save-button').forEach(button => {
        button.addEventListener('click', divz_save_button);
    });

    document.querySelectorAll('.fp-save-button').forEach(button => {
        button.addEventListener('click', fp_save_button);
    });

    document.querySelectorAll('.fp-layout-fund-edit').forEach(button => {
        button.addEventListener('click', fp_layout);
    });

    document.querySelectorAll('.diz-edit-button').forEach(button => {
        button.addEventListener('click', edit_button);
    });

    document.querySelectorAll('.fp-edit-button').forEach(button => {
        button.addEventListener('click', fp_edit_button);
    });

    document.querySelectorAll(".show-connection").forEach((element) => {
        element.addEventListener("click", (event) => {
            const id = event.target.dataset.connection;
            const el = document.getElementById("ETF-Pre-connection-collapse-" + id);

            if (el.style.display === "block") {
                el.style.display = "none";
            } else {
                el.style.display = "block";
            }
        })
    });
});

const delete_connection = async (id) => {
    await fetch(baseURL + "/remove/connection", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id }),
        cache: 'no-cache'
    });

    location.reload();
}

const edit_button = () => {
    document.querySelectorAll('.diz-toggle-button').forEach(btn => btn.disabled = false);
    document.querySelectorAll('.diz-layout-fund-edit').forEach(btn => btn.disabled = false);
    document.querySelectorAll('.diz-save-button, .diz-cancel-button').forEach(btn => btn.style.display = 'inline-block');
    document.querySelectorAll('.div-chart-input').forEach(input => input.disabled = false);
    document.querySelectorAll('.diz-edit-button').forEach(btn => btn.style.display = 'none');
}

const add_new_connection = async (name) => {
    await fetch(baseURL + "/add/connection", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ name }),
        cache: 'no-cache'
    });

    location.reload();
}

const fp_edit_button = () => {
    const fp = document.getElementById('drop-items');

    Sortable.create(fp, {
        animation: 350,
        chosenClass: "sortable-chosen",
        dragClass: "sortable-drag"
    });

    document.querySelectorAll('.fp-toggle-button').forEach(btn => btn.disabled = false);
    document.querySelectorAll('.fp-layout-fund-edit').forEach(btn => btn.disabled = false);
    document.querySelectorAll('.fp-save-button, .fp-cancel-button').forEach(btn => btn.style.display = 'inline-block');
    document.querySelectorAll('.fp-edit-button').forEach(btn => btn.style.display = 'none');
}

const scan_dir_button = (e) => {
    const connectionId = e.target.dataset.connection;

    document.getElementById("ETF-Pre-file-state-" + connectionId).style.display = 'none';
    document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'inline-block';

    fetch(baseURL + "/list/dir", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: connectionId
        }),
        cache: 'no-cache'
    })
        .then(response => response.json())
        .then(response => {
            document.getElementById('ETFs-Pre-scaned-file-dir-' + connectionId).innerHTML = '';
            document.getElementById('ETFs-Pre-scaned-file-list-dirc-' + connectionId).innerHTML = '';
            if (Array.isArray(response.files)) {
                document.getElementById("ETF-Pre-file-state-" + connectionId).style.display = 'block';
                document.getElementById("ETF-Pre-file-state-" + connectionId).innerHTML = "sftp scan successful";
                response.files.forEach(file => {
                    let ext = file.split('.').pop();
                    const y = `<li id="${file}" draggable="true" ondragstart="event.dataTransfer.setData('text', '${file}')" > ${file} </li>`;
                    const x = `<div class="tile form" draggable="true" ondragstart="event.dataTransfer.setData('text', '${file}')"> 
                        <div class="file-ext-text"> 
                            <svg style="margin: 20px 0;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16"> <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/> </svg>
                            <p id="${file}" style="word-break: break-all;">${file}</p> 
                            </div> <p>${ext} file</p> 
                        </div>`;
                    document.getElementById('ETFs-Pre-scaned-file-dir-' + connectionId).innerHTML += x;
                    document.getElementById('ETFs-Pre-scaned-file-list-dirc-' + connectionId).innerHTML += y;
                });
            }

            const feildIds = ["ETFs-Pre-nav-name-", "ETFs-Pre-holdings-name-", "ETFs-Pre-sec-name-", "ETFs-Pre-ror-name-", "ETFs-Pre-index-name-"];
            feildIds.forEach(id => {
                document.getElementById(`${id}${connectionId}`).disabled = true;
            });

            document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'none';
        })
        .catch(error => {
            console.log(`response failed: ${error}`);
            document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'none';
            document.getElementById("ETF-Pre-file-state-" + connectionId).style.display = 'block';
            document.getElementById("ETF-Pre-file-state-" + connectionId).innerHTML = "server fail";
        });
}

const fp_layout = (e) => {
    const fundDetails = document.getElementById(`${e.currentTarget.dataset.fundId}-fund-details`);
    if (fundDetails.style.display === 'none') {
        fundDetails.style.display = 'block';
    } else {
        fundDetails.style.display = 'none';
    }
}

const update_files = (e) => {
    const id = e.target.dataset.connection;

    document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'inline-block';

    const nav = document.getElementById('ETFs-Pre-nav-name-' + id).innerText.trim() ?? "*";
    const holding = document.getElementById('ETFs-Pre-holding-name-' + id).innerText.trim() ?? "*";
    const ror = document.getElementById('ETFs-Pre-ror-name-' + id).innerText.trim() ?? "*";
    const sec = document.getElementById('ETFs-Pre-sec-name-' + id).innerText.trim() ?? "*";
    const ind = document.getElementById('ETFs-Pre-index-name-' + id).innerText.trim() ?? "*";

    fetch(baseURL + "/set/file", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: id,
            nav: nav,
            holding: holding,
            ror: ror,
            sec: sec,
            ind: ind
        }),
        cache: 'no-cache'
    })
        .then(response => response.json())
        .then(() => {
            cancel_file(id);
            document.getElementById("ETF-Pre-file-state-" + id).innerHTML = "File Requirement Update Successful";
            document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'none';
        })
        .catch(error => {
            console.log(`response failed: ${error}`);
            document.getElementById("ETFs-Pre-loadinganimation-file-settings").style.display = 'none';
            document.getElementById("ETF-Pre-file-state-" + id).innerHTML = "File Requirement Update Unsuccessful";
        });
}

const save_button = (e) => {
    document.getElementById("ETF-Pre-creds-state").style.display = 'none';
    document.getElementById("ETFs-Pre-loadinganimation").style.display = 'inline-block';
    const id = e.target.dataset.connection;

    fetch(baseURL + "/set/file", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            host: document.getElementById('ETFs-Pre-host-' + id).value.trim(),
            port: document.getElementById('ETFs-Pre-port-' + id).value.trim(),
            user: document.getElementById('ETFs-Pre-user-' + id).value.trim(),
            pass: document.getElementById('ETFs-Pre-pass-' + id).value.trim(),
            timing: document.getElementById('ETFs-Pre-freq-' + id).value.trim(),
            automate: document.getElementById('ETFs-Pre-auto-' + id).checked
        })
    }).then(response => response.json())
        .then(result => {
            document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
            document.getElementById("ETF-Pre-creds-state").style.display = 'block';
            cancel_onclick(id);
        })
        .catch(error => console.error('Error:', error));
}

const fp_save_button = () => {
    document.getElementById("ETF-Pre-creds-state-2").style.display = 'none';
    document.getElementById("ETFs-Pre-loadinganimation-2").style.display = 'inline-block';

    let etfs_arr = [];
    counter = 1;
    const card_order = document.querySelectorAll(".drop_info");
    for (var obj of card_order) {
        const card = {
            'id': obj.id,
            'order': counter,
            'display': document.getElementById(`vis-${obj.id}`).checked,
            'type': document.getElementById(`${obj.id}-fund-type`).value.trim(),
            'details': document.getElementById(`${obj.id}-fund-desc`).value.trim()
        };
        counter++;
        etfs_arr.push(card);
    };

    const data = JSON.stringify({
        etfs: etfs_arr,
        structured_title: document.getElementById('ETFs-Pre-structured-title').value.trim(),
        structured_subtitle: document.getElementById('ETFs-Pre-structured-subtitle').value.trim(),
    });

    fetch(baseURL + "/set/layout", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    }).then(response => response.json())
        .then(result => {
            cancel_fp_edits(false);
        })
        .catch(error => {
            console.error('Error:', error)
            cancel_fp_edits(false);
            document.getElementById("ETFs-Pre-loadinganimation-2").style.display = 'none';
            document.getElementById("ETF-Pre-creds-state-2").style.display = 'block';

            document.querySelectorAll(".dropdown-fund-details").forEach(
                (element) => element.style.display = 'none'
            );

            document.getElementById("ETF-Pre-creds-state-2").innerHTML = 'server failed';
        });
}

const divz_save_button = () => {
    document.getElementById("ETF-Pre-creds-state-3").style.display = 'none';
    document.getElementById("ETFs-Pre-loadinganimation-3").style.display = 'inline-block';
    document.querySelectorAll('.diz-toggle-button').forEach(btn => btn.disabled = false);

    const data = {
        divz_no_stocks: document.getElementById('etfs-divz-no-stocks').value.trim() === '' ? 0 : document.getElementById('etfs-divz-no-stocks').value.trim(),
        sp_no_stocks: document.getElementById('etfs-sp-no-stocks').value.trim() === '' ? 0 : document.getElementById('etfs-sp-no-stocks').value.trim(),
        divz_ps: document.getElementById('etfs-divz-ps').value.trim() === '' ? 0 : document.getElementById('etfs-divz-ps').value.trim(),
        sp_ps: document.getElementById('etfs-sp-ps').value.trim() === '' ? 0 : document.getElementById('etfs-sp-ps').value.trim(),
        divz_pe: document.getElementById('etfs-divz-pe').value.trim() === '' ? 0 : document.getElementById('etfs-divz-pe').value.trim(),
        sp_pe: document.getElementById('etfs-sp-pe').value.trim() === '' ? 0 : document.getElementById('etfs-sp-pe').value.trim(),
        divz_pb: document.getElementById('etfs-divz-pb').value.trim() === '' ? 0 : document.getElementById('etfs-divz-pb').value.trim(),
        sp_pb: document.getElementById('etfs-sp-pb').value.trim() === '' ? 0 : document.getElementById('etfs-sp-pb').value.trim(),
        divz_avg: document.getElementById('etfs-divz-avg').value.trim() === '' ? 0 : document.getElementById('etfs-divz-avg').value.trim(),
        sp_avg: document.getElementById('etfs-sp-avg').value.trim() === '' ? 0 : document.getElementById('etfs-sp-avg').value.trim(),
    };

    fetch(baseURL + "/update/charts", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(response => response.json())
        .then(result => {
            document.getElementById("ETFs-Pre-loadinganimation-3").style.display = 'none';
            document.getElementById("ETF-Pre-creds-state-3").style.display = 'block';
            cancel_divs_edits();
        })
        .catch(error => console.error('Error:', error));
}


function toggle_file_view(e) {
    if (e.target.checked) {
        document.getElementById("ETFs-Pre-scaned-file-dir-" + e.target.dataset.connection).style.display = 'inline-block';
        document.getElementById("ETFs-Pre-scaned-file-list-dir-" + e.target.dataset.connection).style.display = 'none';
    } else {
        document.getElementById("ETFs-Pre-scaned-file-dir-" + e.target.dataset.connection).style.display = 'none';
        document.getElementById("ETFs-Pre-scaned-file-list-dir-" + e.target.dataset.connection).style.display = 'inline-block';
    }
}

function cancel_fp_edits(arg = false) {
    if (arg === true) {
        $('#drop-items').sortable('destroy');
    }
    document.getElementById("ETFs-Pre-loadinganimation-2").style.display = 'none';
    document.querySelectorAll('.fp-toggle-button').forEach(btn => btn.disabled = true);
    document.querySelectorAll(`.dropdown-fund-details`).forEach(el => el.style.display = 'none');
    document.querySelectorAll('.fp-layout-fund-edit').forEach(btn => btn.disabled = true);
    document.querySelectorAll('.fp-save-button, .fp-cancel-button').forEach(btn => btn.style.display = 'none');
    document.querySelectorAll('.fp-edit-button').forEach(btn => btn.style.display = 'inline-block');
}

function cancel_divs_edits() {
    document.getElementById("ETFs-Pre-loadinganimation-3").style.display = 'none';
    document.querySelectorAll('.diz-toggle-button').forEach(btn => btn.disabled = true);
    document.querySelectorAll('.div-chart-input').forEach(input => input.disabled = true);
    document.querySelectorAll('.diz-save-button, .diz-cancel-button').forEach(btn => btn.style.display = 'none');
    document.querySelectorAll('.diz-edit-button').forEach(btn => btn.style.display = 'inline-block');
}

function edit_file_button() {
    document.querySelectorAll("#ETFs-Pre-nav-name, #ETFs-Pre-holdings-name, #ETFs-Pre-dist-memo-name, #ETFs-Pre-monthly-name").forEach(el => el.disabled = false);
    document.querySelectorAll(".edit-file-button").forEach(btn => btn.style.display = 'none');
    document.querySelectorAll(".scan-dir-button, .update-files-button, .cancel-file-button, .clear-set-file").forEach(btn => btn.style.display = 'inline-block');
}

function cancel_file(connectionId) {
    const feildIds = ["ETFs-Pre-nav-name-", "ETFs-Pre-holding-name-", "ETFs-Pre-sec-name-", "ETFs-Pre-ror-name-", "ETFs-Pre-index-name-"];
    feildIds.forEach(id => {
        document.getElementById(`${id}${connectionId}`).disabled = true;
    });

    document.querySelectorAll(".edit-file-button").forEach(btn => btn.style.display = 'inline-block');
    document.querySelectorAll(".scan-dir-button, .update-files-button, .cancel-file-button, .clear-set-file").forEach(btn => btn.style.display = 'none');
}

function toggle_switch_text(e) {
    const tid = "ETF-Pre-toggle-state-text-" + e.target.dataset.connection;
    const toggle = document.getElementById(tid);
    if (e.target.checked) {
        toggle.innerHTML = "on";
    } else {
        toggle.innerHTML = "off";
    }
    toggle_and_not_saved = true;
}

function toggle_switch_reset(connectionId) {
    if (toggle_and_not_saved) {
        const tid = "ETF-Pre-toggle-state-text-" + connectionId;
        const toggle = document.getElementById(tid);

        if (e.target.checked) {
            toggle.innerHTML = "off";
            e.target.checked = false;
        } else {
            toggle.innerHTML = "on";
            e.target.checked = true;
        }
    }
}

function edit_onclick(e) {
    const id = e.target.dataset.connection

    document.querySelectorAll('.save-button, .cancel-button').forEach(btn => btn.style.display = 'inline-block');
    document.getElementById("ETFs-Pre-auto-" + id).disabled = false;
    document.querySelectorAll('.edit-button').forEach(btn => btn.style.display = 'none');
    document.querySelectorAll("input, select").forEach(el => el.disabled = false);
}

function cancel_onclick(connectionId, flag = true) {
    const feildIds = ["ETFs-Pre-auto-", "ETFs-Pre-pass-", "ETFs-Pre-user-", "ETFs-Pre-port-", "ETFs-Pre-host-", "ETFs-Pre-freq-"];

    feildIds.forEach(id => {
        document.getElementById(`${id}${connectionId}`).disabled = true;
    });

    document.querySelectorAll('.save-button, .cancel-button').forEach(btn => btn.style.display = 'none');
    document.querySelectorAll('.edit-button').forEach(btn => btn.style.display = 'inline-block');

    if (flag) toggle_switch_reset(connectionId);

    toggle_and_not_saved = false;
}

const closeForm = () => {
    Array.from(document.getElementsByClassName('ETF-Pre-general-popup-underlay')).forEach(function (element) {
        element.style.display = 'none';
    });
}