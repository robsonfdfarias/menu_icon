function insertdashicons(obj){
    let span = obj.firstChild;
    let s = document.getElementById('ex');
    s.setAttribute('class', span.getAttribute('class'));
    document.getElementById('fieldIconRff').value = span.getAttribute('class');
    document.getElementById('iconsRff').style.display='none';
}

function btSelectIconRff(){
    let divIcons = document.getElementById('iconsRff');
    divIcons.style.display = 'block';
}

function closeDivIcons(){
    document.getElementById('iconsRff').style.display='none';
}
let formRffIconsMenu = document.getElementById('formRffIconsMenu');
if(formRffIconsMenu){
    formRffIconsMenu.addEventListener('submit', function(event){
        console.log(event)
        console.log(event.submitter.name)
        if(document.getElementById('fieldIconRff').value==''){
            alert('O campo Ícone é obrigatório, selecione um ícone clicando no botão Selecionar.')
            event.preventDefault();
        }
        if(event.submitter.name=="openDelMenuRff"){
            document.getElementById('divDelItemRff').style.display = 'block';
            console.log('Bloqueou com sucesso....')
            event.preventDefault();
        }
        if(event.submitter.name=="abortDeleteMenuRff"){
            document.getElementById('divDelItemRff').style.display = 'none';
            event.preventDefault();
        }
        removeParamsUrl('id')
    });
}

if(document.getElementById('formRffIconsMenu')){
    ifIdSelectedOpenEdit();
}


function ifIdSelectedOpenEdit(){
    let btCreate = document.getElementById('insertMenuRff');
    btCreate.style.display = 'none';
    let btUpdate = document.getElementById('updateMenuRff');
    btUpdate.style.display = 'inline';
    let btDelete = document.getElementById('deleteMenuRff');
    btDelete.style.display = 'inline';
    let order = document.getElementById('menu_icon_rff_orderItems');
    let status = document.getElementById('menu_icon_rff_status');
    let title = document.getElementById('menu_icon_rff_title');
    let url = document.getElementById('menu_icon_rff_url');
    let parent = document.getElementById('menu_icon_rff_parent');
    let category = document.getElementById('menu_icon_rff_cat');
    let id = document.getElementById('micons_rff_id');
    let icon = document.getElementById('fieldIconRff');
    let urlPartes = window.location.href;
    urlPartes = urlPartes.split('?');
    urlPartes = urlPartes[1].split('&');
    if(urlPartes.some(param => param.startsWith('id='))){
        document.getElementById('miconRffForm').style.display='block';
        let json = document.getElementById('contentMenuForId').innerHTML;
        json = json.trim().replace(/\s+/g, ' ');
        try{
            json = JSON.parse(json);
            // console.log(json)
            // console.log(json.id);
        }catch(error){
            console.error("Erro ao converter para JSON:", error);
            return;
        }
        //
        let jsonCat = document.getElementById('contentCatForId').innerHTML;
        jsonCat = jsonCat.trim().replace(/\s+/g, ' ');
        try{
            jsonCat = JSON.parse(jsonCat);
            // console.log(jsonCat)
            // console.log(jsonCat.id);
        }catch(error){
            console.error("Erro ao converter para JSON:", error);
            return;
        }
        //
        let jsonParentId = document.getElementById('contentParentIdForId').innerHTML;
        jsonParentId = jsonParentId.trim().replace(/\s+/g, ' ');
        try{
            jsonParentId = JSON.parse(jsonParentId);
            // console.log(jsonParentId)
            // console.log(jsonParentId.id);
        }catch(error){
            console.error("Erro ao converter para JSON:", error);
            return;
        }
        //
        order.value = json.orderItems;
        title.value = json.title;
        url.value = json.urlPage;
        icon.value = json.iconClass;
        id.value = json.id;
        let s = document.getElementById('ex');
        s.setAttribute('class', json.iconClass);
        let optionStatus = document.createElement('option');
        optionStatus.value = json.statusItem;
        optionStatus.textContent = 'Atual -> '+json.statusItem;
        optionStatus.selected = true;
        // status.value = json.statusItem;
        status.appendChild(optionStatus);
        let optionParent = document.createElement('option');
        optionParent.value = json.parentId;
        optionParent.textContent = 'Atual -> '+jsonParentId.title;
        optionParent.selected = true;
        preventsInsertionItself(parent, json.id);
        parent.appendChild(optionParent);
        // parent.value = json.parentId;
        let optionCat = document.createElement('option');
        optionCat.value = json.category;
        optionCat.textContent = 'Atual -> '+jsonCat.title;
        optionCat.selected = true;
        category.appendChild(optionCat);
        // category.value = json.category;
    }else{
        // console.log('Não existe um id')
    }
}
function preventsInsertionItself(select, opt){
    for(let i=0;i<select.children.length;i++){
        if(select.children[i].getAttribute('value')==opt){
            select.children[i].remove();
        }
    }
}

function removeParamsUrl(param){
    let url = new URL(window.location.href);
    if(url.searchParams.has(param)){
        url.searchParams.delete(param);
        window.history.replaceState({}, '', url);
    }
}

function addParamsUrl(param, value){
    let url = new URL(window.location.href);
    if(url.searchParams.has(param)){
        url.searchParams.delete(param);
    }
    url.searchParams.append(param, value);
    window.history.replaceState({}, '', url);
}

function findParam(param){
    let url = new URL(window.location.href);
    return url.searchParams.has(param);
}
if(findParam('adminCat')){
    document.getElementById('divGeralAdminCat').style.display='block';
}

function getParams(){
    let params = [];
    let url = new URL(window.location.href);
    url.searchParams.forEach((value, key) => {
        params[key] = value;
    });
    return params.join('&');
}


function closeDivAdminCateg(){
    removeParamsUrl('adminCat');
    document.getElementById('divGeralAdminCat').style.display = 'none';
}



/**
 * Aqui começa a administração da Categoria
 */

let formRffCatAdmin = document.getElementById('formRffCatAdmin');
if(formRffCatAdmin){
    formRffCatAdmin.addEventListener('submit', function(event){
        console.log(event)
        console.log(event.submitter.name)
        if(event.submitter.name=="btCancelAdminCat"){
            document.getElementById('divFormRffCatAdmin').style.display = 'none';
            event.preventDefault();
        }
        if(event.submitter.name=="abortDeleteCatRff"){
            document.getElementById('divDelCatRff').style.display = 'none';
            event.preventDefault();
        }
        if(event.submitter.name=="btOpenDelAdminCat"){
            document.getElementById('divDelCatRff').style.display = 'block';
            event.preventDefault();
        }
        removeParamsUrl('id')
        removeParamsUrl('idCat')
    });
}

function clearFormCat(){
    document.getElementById('micon_rff_admin_cat_title').value='';
    let status = document.getElementById('micon_rff_admin_cat_status');
    if(status.options[(status.options.length-1)].text.startsWith('Atual -> ')){
        status.remove(status.options.length-1);
    }
}

function closeDivFormAdminCat(){
    removeParamsUrl('idCat');
    clearFormCat();
    document.getElementById('divFormRffCatAdmin').style.display = 'none';
}

function newCategory(){
    clearFormCat();
    document.getElementById('divFormRffCatAdmin').style.display='block';
    document.getElementById('btCadAdminCat').style.display='inline';
    document.getElementById('btEditAdminCat').style.display='none';
    document.getElementById('btOpenDelAdminCat').style.display='none';
}

function openEditCateg(){
    if(findParam('idCat')){
        document.getElementById('btCadAdminCat').style.display='none';
        document.getElementById('btEditAdminCat').style.display='inline';
        document.getElementById('btOpenDelAdminCat').style.display='inline';
        let id = document.getElementById('micon_rff_admin_cat_id');
        let title = document.getElementById('micon_rff_admin_cat_title');
        let status = document.getElementById('micon_rff_admin_cat_status');
        let divJson = document.getElementById('catForID').innerHTML;
        let json='';
        try{
            json = JSON.parse(divJson);
            // console.log(json)
        }catch(error){
            console.log('Ocorreu o seguinte erro: '+error);
        }
        id.value = json.id;
        title.value = json.title;
        let option = document.createElement('option');
        option.value = json.statusItem;
        option.textContent = 'Atual -> '+json.statusItem;
        option.selected=true;
        status.append(option);
        document.getElementById('divFormRffCatAdmin').style.display='block';
    }
}
openEditCateg();