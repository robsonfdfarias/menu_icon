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

document.getElementById('formRffIconsMenu').addEventListener('submit', function(event){
    if(document.getElementById('fieldIconRff').value==''){
        alert('O campo Ícone é obrigatório, selecione um ícone clicando no botão Selecionar.')
        event.preventDefault();
    }
});

ifIdSelectedOpenEdit();

function ifIdSelectedOpenEdit(){
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
            console.log(json)
            console.log(json.id);
        }catch(error){
            console.error("Erro ao converter para JSON:", error);
            return;
        }
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
        optionParent.textContent = 'Atual -> '+json.parentId;
        optionParent.selected = true;
        parent.appendChild(optionParent);
        // parent.value = json.parentId;
        let optionCat = document.createElement('option');
        optionCat.value = json.category;
        optionCat.textContent = 'Atual -> '+json.category;
        optionCat.selected = true;
        category.appendChild(optionCat);
        // category.value = json.category;
    }else{
        console.log('Não existe um id')
    }
}

function removeParamsUrl(param){
    let url = new URL(window.location.href);
    url.searchParams.delete(param);
    window.history.replaceState({}, '', url);
}