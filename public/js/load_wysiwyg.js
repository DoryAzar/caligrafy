$(document).ready(function() {
        if (document.documentElement.clientWidth > 600) {
            configuration = {
                minHeight: 200,
                //maxHeight: 400,
				dialogsInBody: true,
                toolbar:[
                         ['style', ['style', 'fontsize', 'bold', 'underline']],
                         ['para', ['ul', 'ol', 'paragraph', 'height']],
                         ['insert', ['link', 'table', 'picture', 'video', 'codeview']]
                ]
            };
        } else if(document.documentElement.clientWidth > 320) {
            configuration = {
                minHeight: 200,
                //maxHeight: 400,
				dialogsInBody: true,
                toolbar:[
                         ['style', ['style', 'fontsize', 'bold', 'underline']],
                         ['para', ['ul', 'ol', 'paragraph']]
                ]
            };
        } else {
            configuration = {
                minHeight: 200,
                //maxHeight: 400,
				dialogsInBody: true,
                toolbar:[
                         ['style', ['style', 'fontsize', 'bold', 'underline']]
                ]
            };            
            
        }
	$('#summernote').summernote(configuration);
});