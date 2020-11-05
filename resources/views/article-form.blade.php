<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">TinyMce Ini boss</h1><br> 
                <form method="post" action="{{ route('store.article') }}" class="form form-horizontal"> 
                @csrf              
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control"/>
                    </div>  
                    <div class="form-group">
                        <label>Description</label>
                        <!-- <textarea name="description" rows="5" cols="40" class="form-control tinymce-editor"></textarea> -->
                        <textarea id="rich-editor" name="description" rows="15" cols="40" class="form-control tinymce-editor"></textarea>
                    </div>  
                    <div class="form-group">
                        <label>Hastag</label>
                        <input type="text" name="hastag" class="form-control"/>
                    </div>  
                    <div class="form-group">
                        <label>Author Name</label>
                        <input type="text" name="author" class="form-control"/>
                    </div>   
                    <div class="form-group">
                        <input type="submit" value="Submit" class="btn btn-primary"/>
                    </div> 
                </form>             
            </div>
        </div>
    </div>
    <style>
        .mce-notification {display: none !important;}
    </style>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>  
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.9.2/tinymce.min.js" referrerpolicy="origin"></script>   -->
    <script type="text/javascript">
    tinymce.init({
        // setup : function(ed) {
        //     ed.on('keydown', function(event) {
        //         if (event.keyCode == 9) { // tab pressed
        //             ed.execCommand('mceInsertContent', false, '&emsp;&emsp;'); // inserts tab
        //             event.preventDefault();
        //             return false;
        //         }
        //         if (event.keyCode == 32) { // space bar
        //             if (event.shiftKey) {
        //                 ed.execCommand('mceInsertContent', false, '&hairsp;'); // inserts small space
        //                 event.preventDefault();
        //                 return false;
        //             }
        //         }
        //     });
        // },
        // selector: 'textarea.tinymce-editor',
        selector: '#rich-editor',
        width:'100%',
        height: 450,
        plugins: 'image code nonbreaking',
        content_style: "p { margin: 0; }",
        nonbreaking_force_tab: true,
        browser_spellcheck : true,
        menu: {
            file: { 
                title: 'File', 
                items: 'newdocument restoredraft | preview | print' 
            },
            edit: { 
                title: 'Edit', 
                items: 'undo redo | cut copy paste | selectall | searchreplace' 
            },
            view: { 
                title: 'View', 
                items: 'code | visualaid visualchars visualblocks | preview fullscreen' 
            },
            insert: { 
                title: 'Insert', 
                items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' 
            },
            format: { 
                title: 'Format', 
                items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' 
            },
            tools: { 
                title: 'Tools', 
                items: 'code wordcount' 
            },
            table: { 
                title: 'Table', 
                items: 'inserttable | cell row column | tableprops deletetable' 
            },
            help: { 
                title: 'Help', items: 'help' 
            }
        },
        nonbreaking_force_tab: true,
        branding: false,
        mobile: {
            menubar: true
        },
        // upload image functionality
        images_upload_url: 'http://api.eifil-indonesia.org/api/V1/img-upload',
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = true;
            xhr.open('POST', 'http://api.eifil-indonesia.org/api/V1/img-upload');
            xhr.setRequestHeader('Authorization', 'Bearer ' + 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9hcGkuZWlmaWwtaW5kb25lc2lhLm9yZ1wvYXBpXC9WMVwvbG9naW4iLCJpYXQiOjE2MDI1MjgyMjIsImV4cCI6MTYwMjUzMTgyMiwibmJmIjoxNjAyNTI4MjIyLCJqdGkiOiJjZ0xYUmJDWXJHM3IzakF6Iiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.5EmmNdzxeLoJLvJvsZMrknXF5erGxjag2LyEcOmxsgk');
            xhr.onload = function() {
                var json;
                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }
                json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }
                success(json.location);
            };
            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        },
    });	
    </script>
</body>
</html>