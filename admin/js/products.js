const baseURL = "https://" + window.location.hostname + "/wp-json/etf-rest/v1";

document.addEventListener("DOMContentLoaded", () => {

    document.getElementById('ETF-Pre-add-table-button').addEventListener('click', async () => {
        document.getElementById("ETF-Pre-popup-title").innerHTML = "Add New Table";

        document.getElementById('table-submit-button').style.display = "block";
        document.getElementById('table-update-button').style.display = "none";

        document.getElementById('new-table-name').value = "";
        document.getElementById('new-table-order').value = "";
        document.getElementById('new-table-connection').value = undefined;

        const file = document.getElementById('new-table-file');
        file.dataset.file = undefined;
        file.innerHTML = "";

        document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML = "";

        const popup = document.getElementById("ETF-Pre-popup-underlay-new-table-field");
        popup.style.display = "flex";
    });

    document.getElementById('table-submit-button').addEventListener('click', async (e) => {
        const name = document.getElementById('new-table-name').value;
        const order = document.getElementById('new-table-order').value;
        const connectionId = document.getElementById('new-table-connection').value;
        const file = document.getElementById('new-table-file');
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

            let isDefault = false;
            const name = section.dataset.name;
            if (name === "default") {
                isDefault = true;
            }

            const filename = isDefault ? "default" : section.dataset.filename;
            const connectionId = isDefault ? 0 : section.dataset.connection;
            const order = section.dataset.order;

            const popup = document.getElementById("ETF-Pre-popup-underlay-new-table-field");
            popup.style.display = "flex";

            document.getElementById('new-table-order').value = order;

            const name_feild = document.getElementById('new-table-name');
            name_feild.value = name;

            const connection_feild = document.getElementById('new-table-connection');
            connection_feild.value = connectionId;

            const file = document.getElementById('new-table-file');

            document.getElementById('table-submit-button').style.display = "none";
            const edit = document.getElementById('table-update-button');
            edit.style.display = "block"
            edit.dataset.table = id;

            document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML = "";

            if (isDefault) {
                name_feild.disabled = true;
                connection_feild.disabled = true;
                file.innerHTML = "default";
                file.dataset.file = "default.csv";
                file.style.display = "none";
            } else {
                name_feild.disabled = false;
                connection_feild.disabled = false;

                file.dataset.file = filename;
                file.innerHTML = filename;
                file.style.display = "block";

                const loader = document.getElementById("ETFs-Pre-loadinganimation");
                loader.style.display = 'inline-block';
                await scan_connection_dir(connectionId);
                loader.style.display = 'none';
            }
        })
    });

    document.getElementById('table-update-button').addEventListener('click', async (e) => {
        const id = e.target.dataset.table;

        const newName = document.getElementById('new-table-name').value;
        const newOrder = document.getElementById('new-table-order').value;
        const newConnectionId = document.getElementById('new-table-connection').value;

        const file = document.getElementById('new-table-file');
        const newFilename = file.dataset.file;

        edit_table(id, {
            name: newName,
            filename: newName === "default" ? "default.csv" : newFilename,
            connectionId: newName === "default" ? "0" : newConnectionId,
            order: newOrder
        });
    });

    document.getElementById('new-table-connection').addEventListener('change', async (e) => {
        document.getElementById('ETFs-Pre-scaned-file-list-dirc').innerHTML = "";
        document.getElementById('new-table-file').innerHTML = "";
        document.getElementById('new-table-file').dataset.file = undefined;

        const loader = document.getElementById("ETFs-Pre-loadinganimation");
        loader.style.display = 'inline-block';

        const selectElement = e.target;
        const connectionId = selectElement.value;
        await scan_connection_dir(connectionId);

        loader.style.display = 'none';
    });
});

const edit_table = async (tid, args = {}) => {
    const errors = validateFields({ ...args });

    document.getElementById("table-errors").innerHTML = "";
    if (errors) {
        errors.forEach(error => {
            document.getElementById("table-errors").innerHTML += `<p style="color: red;"> ${error} </p>`;
        });
        return;
    }
    document.getElementById("table-errors").innerHTML = "";

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
    const error = validateFields({ name, filename, connectionId, order });

    document.getElementById("table-errors").innerHTML = "";
    if (error) {
        error.forEach(error => {
            document.getElementById("table-errors").innerHTML += `<p style="color: red;"> ${error} </p>`;
        });
        return;
    }
    document.getElementById("table-errors").innerHTML = "";

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
    const container = document.getElementById('ETFs-Pre-scaned-file-list-dirc');

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

const validateFields = ({ name, filename, connectionId, order }) => {
    const errors = [];

    // Validate name (should not be empty)
    if (!name || typeof name !== 'string') {
        errors.push('Name is required and must be a string.');
    }

    // Validate filename (should be a non-empty string and contain an extension like ".txt")
    if (!filename || typeof filename !== 'string' || !/\.\w+$/.test(filename)) {
        errors.push('Filename is required and must be a valid file with an extension (e.g., .csv).');
    }

    // Validate connectionId (should be a non-empty string)
    if (!connectionId || typeof connectionId !== 'string') {
        errors.push('Connection ID is required and must be a string.');
    }

    // Validate order (should be a number greater than or equal to 1)
    if (!connectionId || typeof order !== 'string' || parseInt(order) <= 0) {
        errors.push('Order must be a number greater than or equal to 1.');
    }

    return errors.length ? errors : null;
};

const closeForm = () => {
    Array.from(document.getElementsByClassName('ETF-Pre-general-popup-underlay')).forEach(function (element) {
        element.style.display = 'none';
    });
}