var faExpand = document.querySelector("#fa-expand")
var faCompress = document.querySelector("#fa-compress")
var gameContentMax = document.querySelector("#game-content")
var gameContentPlay = document.querySelector(".game-content")

faExpand.onclick = function(){
  faExpand.classList.add("d-none");
  faCompress.classList.remove("d-none");
  gameContentMax.classList.add("maximize-content");
  gameContentPlay.classList.add("maximize-content-game");
};
faCompress.onclick = function(){ 
  faCompress.classList.add("d-none");
  faExpand.classList.remove("d-none");
  gameContentMax.classList.remove("maximize-content");
  gameContentPlay.classList.remove("maximize-content-game");
  
};
