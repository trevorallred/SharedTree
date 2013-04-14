// Custom SharedTree.com JavaScript
// Copyright (c) 2006-2007 Trevor Allred (http://www.sharedtree.com/)

function stConfirm($message, $url) {
	var agree=confirm($message);
	if (agree) {
		window.location = $url;
	}
	else return false ;
}
function stShow(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "block";
}
function stHide(whichLayer) {
	var layer = document.getElementById(whichLayer).style;
	layer.display = "none";
}
