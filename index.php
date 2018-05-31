<?php
include('functions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isAdmin()) {
    header('location: admin/home.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
        <style>
            #prediction{
                background-color: grey;
            }

            .text-center{text-align: center;}

            td {
                text-align: center;
                vertical-align:middle;
            }

            ::-webkit-input-placeholder {
                color: lightblue;
                text-align: center;
            }

            :-moz-placeholder {
            color: blue;
            text-align: center;
            }

            #table {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            #table td, #table th {
                border: 1px solid #ddd;
                padding: 8px;
            }

            #table tr:nth-child(2n-1){background-color: #D1E8A3; border-bottom: 4px solid;}
            tr:nth-child(2n){background-color: #BED395; border-bottom: 4px solid;}

            #table tr:nth-child(4n-1){background-color: #EFDB9E; border-bottom: 4px solid;}
            tr:nth-child(4n){background-color: #DAC890; border-bottom: 4px solid;}

            #table th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                background-color: #0F356D;
                color: white;
            }
            
            #table tr:hover, tr:hover + tr {
                background:#82BCBE;
            }
            
            /* #table tr:hover {background-color: red;}
            #table tr:hover {background-color: red;} */
        </style>
    </head>
    <body>        
        <div style="color:blue"><strong><?php message(); ?></strong></div>
 
        <div>
            <div>
                <div>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <strong>Hi, <?php echo $_SESSION['user']['username']; ?></strong>
                        <i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                        <br>
                        <a href="index.php?logout='1'" style="color: red;">logout</a>
                    <?php endif ?>
                </div>
                <hr><br>
                <?php
                    $sql = "SELECT
                    m.id,
                    p.user_id,
                    p.home_prediction,
                    t1.abbreviation AS 'home_abbreviation',
                    t1.flag AS 'home_flag',
                    t1.team AS 'home_team',
                    r.home_score,
                    r.away_score,
                    t2.team AS 'away_team',
                    t2.flag AS 'away_flag',
                    t2.abbreviation AS 'away_abbreviation',
                    p.away_prediction,
                    m.match_date AS 'date',
                    TIME_FORMAT(m.match_time, '%H:%i') AS 'time'
                    FROM matches m
                    LEFT JOIN teams t1
                    ON m.home_team = t1.id
                    LEFT JOIN teams t2
                    ON m.away_team = t2.id
                    LEFT JOIN results r
                    ON m.id = r.match_id
                    LEFT JOIN (SELECT * FROM predictions WHERE user_id='{$_SESSION['user']['id']}') p
                    ON m.id = p.match_id
                    ORDER BY m.id ASC";
                    $results = mysqli_query($db, $sql);
                ?>
                <h1>Matches</h1>
                <table id='table' border="1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2">Home Team</th>
                            <th colspan='3'>Score</th>
                            <th colspan="2">Away Team</th>
                            <th>Date/Time</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>

                    <?php $i = 1; while ($row = mysqli_fetch_array($results)) { ?>
                    <tr>
                        <td rowspan='2' style='border-right: 2px solid'><?php echo $i; ?>.</td>
                        <td rowspan='2'><?php echo $row['home_team']; ?></td>
                        <td rowspan='2' style='border-right: 2px solid'><img src="img2/<?php echo $row['home_flag']; ?>" alt="<?php echo $row['home_abbreviation']; ?>" height="52" width="52"></td> 
                        
                        <?php if(((isset($row['home_prediction']) && $row['home_prediction'] === "0") || !empty($row['home_prediction'])) && ((isset($row['away_prediction']) && $row['away_prediction'] === "0") || !empty($row['away_prediction']))){ ?>
                            <td colspan='3'>
                                <form action="index.php" method="POST">
                                    <div id='prediction'>
                                        <?php echo $row['home_prediction']; ?>
                                        :
                                        <?php echo $row['away_prediction']; ?>
                                    </div>
                                    <p><input type="submit" name="edit" value="Edit" ></p>
                                </form>
                        <?php } else { ?>
                            <td colspan='3'>
                                <form action="index.php" method="POST">
                                    <input type="hidden" name="match_id" value="<?php echo $row['id']; ?>">
                                    <input type="text" name="home_prediction" size="2" placeholder='<?php echo $row['home_abbreviation']; ?>' class='text-center'>
                                    :
                                    <input type="text" name="away_prediction" size="2" placeholder='<?php echo $row['away_abbreviation']; ?>' class='text-center'>
                                    <p><input type="submit" name="submit" value="Submit"></p>
                                    <p><button type="button" onclick="insert()">aha</button>
                                </form>
                            </td>
                        <?php } ?>
                        <td rowspan='2' style='border-left: 2px solid;'><img src="img2/<?php echo $row['away_flag']; ?>" alt="<?php echo $row['away_abbreviation']; ?>" height="52" width="52"></td>
                        <td rowspan='2'><?php echo $row['away_team']; ?></td>
                        <td rowspan='2'><?php echo $row['date']; ?><br><?php echo $row['time']; ?></td>
                        <td rowspan='2'>
                            <a href="edit.php?edit=<?php echo $row['id']; ?>">Edit</a>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $row['home_score']; ?></td>
                        <td>:</td>
                        <td><?php echo $row['away_score']; ?></td>
                    </tr>
                    <?php $i++; }
                    if($_POST){
                        if(isset($_POST['submit'])){
                            insert();
                        }
                    }    

                    ?>
                </table>
            </div>
        </div>
        <script>
        </script>
    </body>
</html>