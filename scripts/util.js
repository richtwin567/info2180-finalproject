
function simulateClick(element) {
	var click = new MouseEvent("click");

	element.dispatchEvent(click);
}

export {simulateClick};