<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $user->email;?> Edit Page</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div>
            <div><?php echo implode( ' ', array( $user->name, $user->surname ) );?></div>
        </div>
    </body>
</html>