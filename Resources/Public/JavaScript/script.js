var canvas;
var ctx;
var image;

$(function(){
	prepareColorPicker(imageSrc);
	var $colorthief = $('#colorthief');
	var $span = $('#ct-toggle').find('span');
	
	$('#ct-toggle').toggle( function(){
		changeContent($span);
		colorThief();
		$colorthief.show();
	},function(){
		changeContent($span);
		$colorthief.hide();
	});
});

function changeContent(element){
	var newContent = element.data('content');
	var oldContent = element.text();
	element.text(newContent);
	element.data('content', oldContent);
};

var prepareColorPicker = function(imageSrc){

    // drawing active image
    image = new Image();
    image.onload = function () {
        canvas.width = image.width;
        canvas.height = image.height;
        ctx.drawImage(image, 0, 0, image.width, image.height, 0, 0, canvas.width, canvas.height); // draw the image on the canvas
		if($('#colorthief:visible').length === 1){
			colorThief();
		}
    }

    image.src = imageSrc;
    // creating canvas object
    canvas = document.getElementById('panel');
    ctx = canvas.getContext('2d');

    $('#panel').mousemove(function(e) { // mouse move handler
        var canvasOffset = $(canvas).offset();
        var canvasX = Math.floor(e.pageX - canvasOffset.left);
        var canvasY = Math.floor(e.pageY - canvasOffset.top);
        var imageData = ctx.getImageData(canvasX, canvasY, 1, 1);
        var pixel = imageData.data;
        setPreviewBackgroundColor(pixel);
    });

    $('#panel').click(function(e) { // mouse click handler
        var canvasOffset = $(canvas).offset();
        var canvasX = Math.floor(e.pageX - canvasOffset.left);
        var canvasY = Math.floor(e.pageY - canvasOffset.top);
        var imageData = ctx.getImageData(canvasX, canvasY, 1, 1);
        var pixel = imageData.data;
        setColors(pixel);
    });
  };  
    function changeSize(whatToDo) {
        var newZoomValue;
        var minValue = 0;
        var increment = 0.2;
        switch (whatToDo) {
            case "smaller":
                newZoomValue = 1 - increment;
                break;
            case "larger":
                newZoomValue = 1 + increment;
                break;
        }

        if (newZoomValue < minValue) {
            newZoomValue = minValue;
        }
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.canvas.width *=newZoomValue;
        ctx.canvas.height *=newZoomValue;
        ctx.drawImage(image, 0, 0, image.width , image.height, 0, 0, canvas.width, canvas.height);
    }
    $('#plus').click(function () {
        changeSize('larger');
    });
    $('#minus').click(function () {
        changeSize('smaller');
    });

    $('.column1').drag(function( ev, dd ){
        $( this ).css({
            top: dd.offsetY,
            left: dd.offsetX
        });
    },{handle:".handle"});



	function setPreviewBackgroundColor(pixel){
        if(pixel[3] === undefined){
            pixel[3] = '255';
        }
        var pixelColor = "rgba("+pixel[0]+", "+pixel[1]+", "+pixel[2]+", "+pixel[3]+")";
        $('#preview').css('backgroundColor', pixelColor);
    }
	
    function setColors(pixel){
        if(pixel[3] === undefined){
            pixel[3] = '255';
        }
        $('#rVal').val(pixel[0]);
        $('#gVal').val(pixel[1]);
        $('#bVal').val(pixel[2]);

        $('#rgbVal').val(pixel[0]+','+pixel[1]+','+pixel[2]);
        $('#rgbaVal').val(pixel[0]+','+pixel[1]+','+pixel[2]+','+pixel[3]);
        var dColor = pixel[2] + 256 * pixel[1] + 65536 * pixel[0];
        $('#hexVal').val( '#' + dColor.toString(16) );
    }
    // Color thief
   function colorThief(){
		var $image = $(image);
        var imageSection = $('.colors');
        var appendColors = function (colors, root) {
            $.each(colors, function (index, value) {
                var swatchEl = $('<div>', {'class': 'swatch'})
                    .css('background-color', 'rgba('+ value +', 1)')
                    .click(function(){
                            setColors(value);
                        })
                    .mousemove(function(){
                            setPreviewBackgroundColor(value);
                        })
                    ;
                root.append(swatchEl);
            });
        };

        // Dominant Color
        var dominantColor = getDominantColor(image);
        var dominantSwatch = imageSection.find('.dominantColor .swatches');
		dominantSwatch.html('');
        appendColors([dominantColor], dominantSwatch);

        // Palette
        var colorCount = $image.attr('data-colorcount') ? $image.data('colorcount') : 9;
        var medianPalette = createPalette(image, colorCount);
        var medianCutPalette = imageSection.find('.medianCutPalette .swatches');
		medianCutPalette.html('');
        appendColors(medianPalette, medianCutPalette);
    };