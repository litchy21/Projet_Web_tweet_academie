function showForms(){
    if (document.getElementById("passForm").style.display == "block") {
        document.getElementById("passForm").style.display = "none";
    }else{
        document.getElementById("passForm").style.display = "block";
    }
    if (document.getElementById("emailForm").style.display == "block") {
        document.getElementById("emailForm").style.display = "none";
    }else{
        document.getElementById("emailForm").style.display = "block";
    }
}
// window.onload = function() {
//     document.getElementById("backGround").onchange = function() {
//         y = document.getElementById("backGround");
//         document.getElementById('body-background').style.backgroundColor = y.value;
//     }
//     document.getElementById("color-text").onchange = function() {
//         y = document.getElementById("color-text");
//         document.getElementById('body-background').style.color = y.value;
//      }
//      document.getElementById("fontfamily").onchange = function() {
//         y = document.getElementById("fontfamily");
//         document.getElementById('body-background').style.fontFamily = y.value;
//      }
// }

// function retweetContent(){
//     document.getElementsByClassName("formRetweet").style.display = "block";
//     return false;
// }
// function cancelRetweet() {
//     document.getElementsByClassName("formRetweet").style.display = "none";
//     return false;
// }