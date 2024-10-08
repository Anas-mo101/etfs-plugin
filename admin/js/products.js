const baseURL = "https://" + window.location.hostname + "/wp-json/etf-rest/v1";

document.addEventListener("DOMContentLoaded", () => {

    document.getElementById('ETF-Pre-add-table-button').addEventListener('click', async () => {
        document.getElementById("ETF-Pre-popup-title").innerHTML = "Add New Table";

        document.getElementById('table-submit-button').style.display = "block";
        document.getElementById('table-update-button').style.display = "none";

        document.getElementById('new-table-name').value = "";
        document.getElementById('new-table-order').value = "";

        const file = document.getElementById('new-table-file');
        file.dataset.connection = undefined;
        file.dataset.file = undefined;
        file.innerHTML = "";

        const popup = document.getElementById("ETF-Pre-popup-underlay-new-table-field");
        popup.style.display = "flex";

    });

    document.getElementById('table-submit-button').addEventListener('click', async (e) => {
        const name = document.getElementById('new-table-name').value;
        const order = document.getElementById('new-table-order').value;
        const file = document.getElementById('new-table-file');
        const connectionId = file.dataset.connection;
        const filename = file.dataset.file;

        await add_new_table(name, filename, connectionId, order);
    });

    document.querySelectorAll('.delete-table').forEach((del) => {
        del.addEventListener('click', (e) => {
            const id = e.target.dataset.table;
            document.getElementById("ETF-Pre-popup-underlay-del-table-field").style.display = "flex";
            document.getElementById('table-delete-button').addEventListener('click', (e) => delete_table(id));
        });
    });

    document.querySelectorAll(".show-table").forEach((element) => {
        element.addEventListener("click", (event) => {
            const id = event.target.dataset.table;
            const el = document.getElementById("table-section-" + id);
            if (el.style.display === "block") {
                el.style.display = "none";
            } else {
                el.style.display = "block";
            }
        })
    });

    document.querySelectorAll(".edit-table").forEach((element) => {
        element.addEventListener("click", async (event) => {
            document.getElementById("ETF-Pre-popup-title").innerHTML = "Edit Table";

            const id = event.target.dataset.table;
            const section = document.getElementById(id + "-section");

            const name = section.dataset.name;
            const filename = section.dataset.filename;
            const connectionId = section.dataset.connection;
            const order = section.dataset.order;

            const popup = document.getElementById("ETF-Pre-popup-underlay-new-table-field");
            popup.style.display = "flex";

            document.getElementById('new-table-name').value = name;
            document.getElementById('new-table-order').value = order;

            const file = document.getElementById('new-table-file');
            file.dataset.connection = connectionId;
            file.dataset.file = filename;
            file.innerHTML = filename;

            document.getElementById('table-submit-button').style.display = "none";
            const edit = document.getElementById('table-update-button');
            edit.style.display = "block"
            edit.dataset.table = id;
        })
    });

    document.getElementById('table-update-button').addEventListener('click', async (e) => {
        const id = e.target.dataset.table;

        const newName = document.getElementById('new-table-name').value;
        const newOrder = document.getElementById('new-table-order').value;

        const file = document.getElementById('new-table-file');
        const newConnectionId = file.dataset.connection;
        const newFilename = file.dataset.file;

        edit_table(id, {
            name: newName,
            filename: newFilename,
            connectionId: newConnectionId,
            order: newOrder
        });
    });

    fillFileContainers();
});

const fillFileContainers = async () => {
    const loader = document.getElementById("ETFs-Pre-loadinganimation");

    if (loader.style.display === "inline-block") {
        return;
    }

    loader.style.display = 'inline-block';
    const containers = document.querySelectorAll(".file-container");
    for (const element of containers) {
        const connectionId = element.dataset.connection;
        await scan_connection_dir(connectionId);
    }
    loader.style.display = 'none';
}

const edit_table = async (tid, args = {}) => {
    document.getElementById("ETFs-Pre-loadinganimation").style.display = 'inline-block';

    fetch(baseURL + "/table/update", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: tid,
            ...args
        }),
        cache: 'no-cache'
    }).then((response) => response.json()).then((response) => {
        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
        document.getElementById("ETF-Pre-popup-underlay-new-table-field").style.display = "none";

        document.getElementById('table-submit-button').style.display = "block";
        document.getElementById('table-update-button').style.display = "none";

        location.reload();
    }).catch((error) => {
        document.getElementById('table-submit-button').style.display = "block";
        document.getElementById('table-update-button').style.display = "none";

        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
        console.log(error);
    });
}

const add_new_table = async (name, filename, connectionId, order) => {
    document.getElementById("ETFs-Pre-loadinganimation").style.display = 'inline-block';

    fetch(baseURL + "/table/create", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            connectionId: connectionId,
            name: name,
            fileName: filename,
            order: order
        }),
        cache: 'no-cache'
    }).then(response => response.json()).then(response => {
        console.log(response);
        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
        const popup = document.getElementById("ETF-Pre-popup-underlay-new-table-field");
        popup.style.display = "none";
        location.reload()
    }).catch(error => {
        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
        console.log(error);
    });
}

const delete_table = async (id) => {
    document.getElementById("ETFs-Pre-loadinganimation").style.display = 'inline-block';
    document.getElementById("ETF-Pre-popup-underlay-del-table-field").style.display = "none";
    fetch(baseURL + "/table/remove", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id: id
        }),
        cache: 'no-cache'
    }).then(response => response.json()).then(response => {
        console.log(response);
        const section = document.getElementById(`${id}-section`);
        section.remove();
        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
    }).catch(error => {
        console.log(error);
        document.getElementById("ETFs-Pre-loadinganimation").style.display = 'none';
    });
}

const scan_connection_dir = async (connectionId) => {
    const container = document.getElementById('ETFs-Pre-scaned-file-list-dirc-' + connectionId);
    if (container.hasChildNodes()) {
        return;
    }

    await fetch(baseURL + "/list/dir", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: connectionId, ext: "csv" }),
        cache: 'no-cache'
    }).then(response => response.json()).then(response => {
        container.innerHTML = '';
        if (Array.isArray(response.files)) {
            response.files.forEach(file => {
                const id = connectionId + "-" + file;
                const y = `<li id="${id}" data-file="${file}" data-connection="${connectionId}" draggable="true" ondragstart="event.dataTransfer.setData('text', '${id}')"> ${file} </li>`
                container.innerHTML += y;
            });
        }
    }).catch(error => {
        console.log(`response failed: ${error}`);
        document.getElementById("ETFs-Pre-loadinganimation").style.display = "none";
    });
}


const closeForm = () => {
    Array.from(document.getElementsByClassName('ETF-Pre-general-popup-underlay')).forEach(function (element) {
        element.style.display = 'none';
    });
}