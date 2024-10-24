function insertdashicons(obj){
    let span = obj.firstChild;
    console.log(span.getAttribute('class'));
    let s = document.getElementById('ex');
    s.setAttribute('class', span.getAttribute('class'));
}