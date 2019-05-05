/*!
 * @copyright Copyright &copy; Broadposter 2018
 * @version 1.0
 *
 */

function init() {
    var _inputFile;
    
    $("img[data-type=editable]").each(function (i, e) {
        _inputFile = $('<input/>')
	    .attr('class', 'hide')
            .attr('type', 'file')
            .attr('hidden', 'hidden')
            .attr('accept', 'image/*')
	    .attr('name', e.id)
            .attr('onchange', 'readImage()')
            .attr('data-image-placeholder', e.id);

        $('#kp_new_form').append(_inputFile); //e.parentElement

        $(e).on("click", _inputFile, triggerClick);
    });
}

function triggerClick(e) {
    $('#global_error_message').attr('class', 'hide');
    e.data.click();
}

Element.prototype.readImage = function () {
    var _inputFile = this;

    if (_inputFile && _inputFile.files && _inputFile.files[0]) {
	if (_inputFile.files[0].size/1024/1024 <= 2) { // if picture size is acceptable
	    var _fileReader = new FileReader();
	    _fileReader.onload = function (e) {
		var _imagePlaceholder = _inputFile.attributes.getNamedItem("data-image-placeholder").value;
		var _img = $("#" + _imagePlaceholder);
		_img.attr("src", e.target.result);
	    };
	    _fileReader.readAsDataURL(_inputFile.files[0]);
	    $('#picture_error').attr('class', 'error hide');
	} else { //otherwise show inline error message
	    $('#picture_error').attr('class', 'error show');
	}
    }
};

// 
// IIFE - Immediately Invoked Function Expression
// https://stackoverflow.com/questions/18307078/jquery-best-practises-in-case-of-document-ready
(

function (yourcode) {
    "use strict";
    // The global jQuery object is passed as a parameter
    yourcode(window.jQuery, window, document);
}(

function ($, window, document) {
    "use strict";
    // The $ is now locally scoped 
    $(function () {
        // The DOM is ready!
        init();
    });

    // The rest of your code goes here!
}));