let dataDebug = btoa("{}");

function setData(data)
{
    dataDebug = data;
    closeDebug();
}

function showDebug() {

    let closeButton = document.getElementById('closeButton');
    if(closeButton !== null)
    {
        closeButton.remove();
    }

    let ctx = document.body;
    let modal = document.createElement('div');
    modal.setAttribute("id", "debug");
    modal.setAttribute("style", "background-color: #F2F2F2; width: 100%; height: 300px; padding: 5px; position: fixed; bottom: 0px;");

    let header = document.createElement('div');
    header.setAttribute("style", "border: 1px solid #A4A4A4; width: 100%; height: 15%; padding: 5px; margin-bottom: 5px");

    let close = "<span style='cursor: pointer; color: red; background-color: #F2F2F2; margin: 5px; position: fixed; bottom: 300px; right: 1%;' onclick='closeDebug()'>Close Debug [X]</span>";

    header.innerHTML = close + "<h4><strong style='color: #585858;'>[Debug Mode]</strong></h4>";

    modal.appendChild(header);

    let dataRouter = document.createElement('div');
    dataRouter.innerHTML = getRouterInfo();

    let divRouter = document.createElement('div');
    divRouter.setAttribute('style', 'width: 40%; height: 100%; border-right: 1px solid #A4A4A4; padding: 10px; overflow-x: hidden; overflow-y: auto');

    divRouter.appendChild(dataRouter);

    let dataViwer = document.createElement('div');
    dataViwer.setAttribute('style', 'height: 100%; overflow-x: hidden; overflow-y: auto');
    dataViwer.innerHTML = getDataInfo();

    let divData = document.createElement('div');
    divData.setAttribute('style', 'width: 60%; height: 100%; padding: 10px');

    divData.appendChild(dataViwer);

    let terminal = document.createElement('div');
    terminal.setAttribute("style", "display: flex; background-color: #FFF; width: 100%; height: 80%; margin-bottom: 0%");

    terminal.appendChild(divRouter);
    terminal.appendChild(divData);

    modal.appendChild(terminal);

    ctx.appendChild(modal);
}

function closeDebug()
{
    let debug = document.getElementById('debug');

    if(debug !== null)
    {
        debug.remove();
    }
    
    let ctx = document.body;
    let button = document.createElement('div');

    button.setAttribute("id", "closeButton");
    button.setAttribute("style", "background-color: transparent; width: 100%; height: 30px; padding: 5px; position: fixed; bottom: 300px; right: 1%");
 
    let show = "<span style='cursor: pointer; color: red; background-color: #F2F2F2; margin: 5px; position: fixed; bottom: 0px; right: 1%' onclick='showDebug()'>Show Debug</span>";
    button.innerHTML = show;

    ctx.appendChild(button);
}

function getRouterInfo()
{
    let data = JSON.parse(atob(dataDebug));

    let params = "";
    let i = 0;
    for(const element of data.router.params)
    {
        if(i == 0) {
            params += element;
        } else {
            params += ", " +element;
        }

        i++;
    }

    let html = "<h6>[ROUTER INFO]</h6><table style='width: 100%; font-size: 100%'>";
    html += "<tr><td><label style='color: #8A0808'>Router:</label></td><td>["+ data.router.method+"]</td></tr>";
    html += "<tr><td><label style='color: #8A0808'>Controller:</label></td><td>["+ data.router.class+"]</td></tr>";
    html += "<tr><td><label style='color: #8A0808'>Function:</label></td><td>["+ data.router.function+"]</td></tr>";
    html += "<tr><td><label style='color: #8A0808'>Params:</label></td><td>["+ params +"]</td></tr>";
    html += "</table>";
    return html;
}

function showElement(e)
{
    let display = document.getElementById("obj_"+e).style.display;
    let expand = document.getElementById("expand_"+e);

    if(display == 'initial') {
        document.getElementById("obj_"+e).style.display = 'none';
        expand.innerHTML = "[+]";
    } else {
        document.getElementById("obj_"+e).style.display = 'initial';
        expand.innerHTML = "[x]";
    }
}

function populateObject(indexObj, value, element, countTab)
{
    let html = "";
    let type = typeof value;

    let tab = "";
    
    for (let i = 0; i < countTab; i++)
    {
        tab += "&emsp;"
    }

    html += "<table>";
    html += "<tr><td>"+tab+"["+indexObj+"] <span style='color: #5858FA'>["+type+"]</span> <label style='color: #8A0808; cursor:pointer' onclick='showElement(\""+countTab+"\")'>"+ element +": <span id='expand_"+countTab+"' style='color: #DF7401'>[+]</span></label><div style='display: none;' id='obj_"+countTab+"'>[<br>";
    let index = 1;
    for(const attr of Object.keys(value)) {

        let attrValue = value[attr];
        let typeAttr = typeof attrValue;

        if (typeAttr == 'object') {
            countTab+=1;
            html += populateObject(index, attrValue, attr, countTab);
        } else if(typeAttr == 'string' || typeAttr == 'int' || typeAttr == 'float' || typeAttr == 'boolean') {
            html += tab+"&emsp;["+index+"] <span style='color: #5858FA'>["+typeAttr+"]</span> <label style='color: #8904B1'>"+ attr +":</label> ["+attrValue+"]<br>";
        }

        index++;
    }
    html += tab+"]</div></tr>";
    html += "</table>";

    return html;
}

function getDataInfo()
{
    let data = JSON.parse(atob(dataDebug));
    let html = "<h6>[VARIABLES]</h6><table style='width: 100%; font-size: 100%'>";
    console.log(data);

    let index = 1;

    for(const element of Object.keys(data.dataViwer)) {
        let value = data.dataViwer[element];
        let type = typeof value;

        if(type == 'string' || type == 'int' || type == 'float' || type == 'boolean') {
            let dataValue =value;

            if(value.length > 50) {
                dataValue = value.substring(0, 50) + "...";
            }

            html += "<tr><td>["+index+"] <span style='color: #5858FA'>["+type+"]</span> <label style='color: #8A0808'>"+ element+":</label> <span title='"+value+"'>["+ dataValue  +"]</span></td></tr>";
        }
        if(type == 'object')
        {
            html += populateObject(index, value, element, 0);
        }

        index++;
    }

    
    html += "</table>";
    return html;
}