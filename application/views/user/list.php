<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users Dashboard</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/tablesorter/css/theme.bootstrap.css">
    <link href="css/custom/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-9 col-md-10">
                <h1 class="page-header">Users Dashboard</h1>
            </div>
            <div class="col-sm-9 col-md-10">
                <p>
                    <a class="btn btn-primary callUserEditForm" role="button" href="#">Create »</a>
                </p>
            </div>
            <div class="col-sm-9 col-md-10 main">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-striped tablesorter">
                        <thead>
                            <tr>
                                <th class="header">#</th>
                                <th class="header">First Name</th>
                                <th class="header">Second Name</th>
                                <th class="header">Email</th>
                                <th class="header">Personal Code</th>
                                <th class="header">Address</th>
                                <th class="header">City</th>
                                <th class="header">Country</th>
                                <th class="headerInactive">Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="9" class="ts-pager form-horizontal">
                                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                                    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                                    <select class="pagesize input-mini" title="Select page size">
                                            <option selected="selected" value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                    </select>
                                    <select class="pagenum input-mini" title="Select page number"></select>
                                </th>
                        </tr>
                        </tfoot>
                        <tbody>
                            <?php foreach ( $users as $user ):?>
                            <tr id="user_<?php echo $user->id;?>">
                                <td><?php echo $user->id;?></td>
                                <td><?php echo $user->name;?></td>
                                <td><?php echo $user->surname;?></td>
                                <td><?php echo $user->email;?></td>
                                <td><?php echo $user->personal_code;?></td>
                                <td><?php echo $user->address;?></td>
                                <td><?php echo $user->city;?></td>
                                <td><?php echo $user->country;?></td>
                                <td>
                                    <a class="link callUserEditForm" href="#" data="<?php echo $user->id;?>">Edit</a>&nbsp;<a class="link callUserDelete" href="#" data="<?php echo $user->id;?>">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-9 col-md-10">
                <p>
                    <a class="btn btn-primary callUserEditForm" role="button" href="#">Create »</a>
                </p>
            </div>
        </div>
        <div class="container" style="display: none;">
            <p>
                <a class="btn btn-default backButton" role="button" href="#">« Back</a>
            </p>
            <div class="alert-danger" style="display: none;">
            </div>
            <div class="alert-success" style="display: none;">
            </div>
            <form id="userEditForm" class="form-actions" role="form">
                <h1 class="page-header"></h1>
                <input id="user_id" type="hidden" name="user[id]" value="" />
                <input id="user_name" class="form-control" type="text" name="user[name]" autofocus="" required="" maxlength="50" placeholder="First Name" />
                <input id="user_surname" class="form-control" type="text" name="user[surname]" autofocus="" required="" maxlength="50" placeholder="Second Name" />
                <input id="user_email" class="form-control" type="email" name="user[email]" autofocus="" required="" maxlength="100" placeholder="Email" />
                <input id="user_address" class="form-control" type="text" name="user[address]" autofocus="" maxlength="100" placeholder="Address" />
                <input id="user_city" class="form-control" type="text" name="user[city]" autofocus="" required="" maxlength="30" placeholder="City" />
                <input id="user_country" class="form-control" type="text" name="user[country]" autofocus="" required="" maxlength="30" placeholder="Country" />
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery/validate/lib/jquery.form.js"></script>
    <script type="text/javascript" src="js/jquery/validate/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/jquery/tablesorter/jquery.metadata.js"></script>
    <script type="text/javascript" src="js/jquery/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/jquery/tablesorter/jquery.tablesorter.widgets.min.js"></script>
    <script type="text/javascript" src="js/jquery/tablesorter/addons/pager/jquery.tablesorter.pager.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/custom/main.js"></script>
  </body>
</html>