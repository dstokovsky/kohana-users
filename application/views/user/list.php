<!DOCTYPE html>
<html>
    <head>
        <title>Users</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div>
            <div>Users</div>
            <ul>
                <?php foreach ( $users as $user ):?>
                <li><a href="/user/edit/<?php echo $user->id;?>"><?php echo $user->email; ?></a></li>
                <?php endforeach;?>
            </ul>
            <button class="btn btn-large btn-primary" type="button">А я кнопка из Twitter Bootstrap!</button>
        </div>
    </body>
</html>