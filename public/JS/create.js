$(function (){
    display_info();
})

$(document).on('change',"#lieu",function(){
    display_info();
})

function display_info(){
    let lieu = $("#lieu :selected").data('lieu');
    $("#ville_js_sortie").text(lieu.ville);
    $("#rue_js_sortie").text(lieu.rue);
    $("#code_js_sortie").text(lieu.codePostale);
    $("#longitude_js_sortie").text(lieu.longitude);
    $("#latitude_js_sortie").text(lieu.latitude);
}



