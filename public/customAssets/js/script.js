function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

var count = 0;
var delButton = document.getElementById("customDelButton");
delButton.addEventListener('click',function(){
    var leftMarg = getRandomInt(100);
    var bottomMarg = getRandomInt(90);
    count += 1;
    delButton.style = "position: absolute; left: "+leftMarg+"%; top: "+bottomMarg+"%;";
    if (count == 3) {
        alert("You can't delete Batterie ! This is the best instrument !");
        delButton.style = "display: none;";
    }
});