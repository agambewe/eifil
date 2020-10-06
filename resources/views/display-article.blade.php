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
            </div>
        </div>
        <div class="col-md-12">
            <p>Title: <?php echo $article->title; ?></p>
        </div>
        <div class="col-md-12">
            <p>Description: </p>
            <?php echo $article->description; ?>
        </div>
        <div class="col-md-12">
            <p>Hastag: <?php echo $article->hastag; ?></p>
        </div>
        <div class="col-md-12">
            <p>Author Name: <?php echo $article->author; ?></p>
        </div>
    </div>
</body>

</html>
