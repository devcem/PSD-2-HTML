var PSD = require('psd');
var filename = '../photoshop.psd';
var psd = PSD.fromFile(filename);
psd.parse();

function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function contain(_list, type){
	//console.log(JSON.stringify(_list));
	return JSON.stringify(_list).toString().search(type) > -1 ? true : false;
}

function getMask(_list){
	mask = {};
	for(_item in _list){
		_item = _list[_item];

		if(_item.mask){
			mask = _item.mask;
		}
	}

	return mask;
}

file = psd.tree().export();
//console.log(file.children[0].children[0].children[2]);
//console.log(psd.tree().childrenAtPath('A/B/C')[0].export());

output = [];

function scan(list){
	for(item in list){
		item = list[item];

		if(item.text){
			//output.push(JSON.stringify(item.text.font.tracking));
			console.log(item);
			transform = item.text.transform.xx;
			output.push({
				type : 'text',
				fontSize : parseInt(item.text.font.sizes[0])*transform,
				fontColor : rgbToHex(item.text.font.colors[0][0], item.text.font.colors[0][1], item.text.font.colors[0][2]),
				fontName : item.text.font.names ? item.text.font.names[0] : item.text.font,
				fontWeight : (item.text.font.names[0].toLowerCase().search('bold') > -1 ? 'bold' : 'normal'),
				alignment : item.text.font.alignment[0],
				transformX : item.text.transform.xx,
				transformY : item.text.transform.yy,
				content : item.text.value,
				leading : item.text.font.leading[0],
				tracking : item.text.font.tracking[0],
				left : item.left,
				top : item.top,
				right : item.right,
				bottom : item.bottom,
				width : item.width,
				height : item.height
			});
		}

		if(item.type == 'group'){
			if(contain(item.children, 'text') || contain(item.children, 'image')){
				//console.log(getMask(item.children));
				mask = getMask(item.children);

				if(mask != {}){
					output.push({
						type : 'button',
						left : mask.left,
						top : mask.top,
						right : mask.right,
						bottom : mask.bottom,
						width : mask.width,
						height : mask.height
					});
				}else{
					output.push({
						type : 'button',
						left : item.left,
						top : item.top,
						right : item.right,
						bottom : item.bottom,
						width : item.width,
						height : item.height
					});
				}
			}

			scan(item.children);
		}
	}
}

//scan(file.children);

//console.log((output));
console.log(JSON.stringify(file));

// You can also use promises syntax for opening and parsing
PSD.open(filename).then(function (psd) {
	return psd.image.saveAsPng('temp/output.png');
});