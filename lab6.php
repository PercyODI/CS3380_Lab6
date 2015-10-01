<!-- Author:
      Pearse Hutson
      pah9qd
      14040826
-->

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
      select[name="sqlDropDown"] {
        height: 34px;
      }
      
      body {
        padding-top: 50px;
        padding-bottom: 70px;
      }
      .nav-bottom-cust {
        border-top: 1px solid lightslategray;
      }
      
      .nav-top-cust {
        border-bottom: 1px solid lightslategray;
      }
      
      .table-cust {
        table-layout: fixed;
        margin: auto;
      }
      
      .well {
        max-width: 51%;
        margin: auto;
        margin-bottom: 10px;
        font-size: 16px;
      }
    </style>

    <?php
      ini_set('display_errors',1);
      ini_set('display_startup_errors',1);
      error_reporting(-1);
    
      $SERVER = 'us-cdbr-azure-central-a.cloudapp.net';
      $USER = 'bf7f0622e9427e';
      $PASS = '720ad0bb';
      $DATABASE = 'cs3380-pah9qd';
      
      $mylink = mysqli_connect( $SERVER, $USER, $PASS, $DATABASE) or die("<h3>Sorry, could not connect to database.</h3><br/>Please contact your system's admin for more help\n");
      $_SESSION['mylink'] = $mylink;
      
      $query_list = array(
        //View 1
        //create view weight AS SELECT person.pid, fname, lname FROM person INNER JOIN body_composition ON person.pid = body_composition.pid WHERE weight > 140;     
        "SELECT * FROM weight",
        //View 2
        //create view BMI AS SELECT fname, lname, round(703 * weight / pow(height, 2)) AS bmi FROM weight INNER JOIN body_composition ON weight.pid = body_composition.pid WHERE weight > 150;
        "SELECT * FROM bmi",
        //Query 3
        "SELECT university_name AS `University Name`, city 
        FROM university 
        WHERE NOT EXISTS (
          SELECT * 
          FROM person 
          WHERE person.uid = university.uid
        )",
        //Query 4
        "SELECT fname AS `First Name`, lname AS `Last Name` 
        FROM person 
        WHERE person.uid IN (
          SELECT uid 
          FROM university 
          WHERE city = 'Columbia'
        )",
        //Query 5
        "SELECT * 
        FROM activity 
        WHERE activity_name NOT IN (
          SELECT a.activity_name 
          FROM activity AS a 
          INNER JOIN participated_in AS pi 
          ON pi.activity_name = a.activity_name
        )",
        //Query 6
        "SELECT pid AS `PID` 
        FROM participated_in 
        WHERE activity_name = 'running' 
        UNION 
        SELECT pid 
        FROM participated_in 
        WHERE activity_name = 'racquetball'",
        //Query 7
        "SELECT age_t.fname, age_t.lname
        FROM (
            SELECT fname, lname, pid
            FROM person
            JOIN body_composition USING (pid)
            WHERE age > 30
        ) AS age_t JOIN (
            SELECT fname, lname, pid
            FROM person
            JOIN body_composition USING (pid)
            WHERE height > 65
        ) AS height_t USING (pid)",
        //Query 8
        "SELECT p.fname AS `First Name`, 
          p.lname AS `Last Name`, 
          b.weight, 
          b.height, 
          b.age 
        FROM person AS p 
        INNER JOIN body_composition AS b 
        USING (pid) 
        ORDER BY b.height DESC, b.weight, p.lname"
        );
    
    $query_descriptions = array (
      
      );  
      
      function run_sql_query() {
        global $query_list;
        if (!isset($_POST['sqlDropDown'])) {
          $_POST['sqlDropDown'] = 0;
        }
        $result = mysqli_query($_SESSION['mylink'], $query_list[$_POST['sqlDropDown']]);
        
        $column_names = mysqli_fetch_fields($result);
        
        $j = 0;
        while($data = mysqli_fetch_row($result)) {
          for($i = 0; $i < count($column_names); $i++) {
            $query_data[$j][] = $data[$i - 0];
          }
          $j++;
        }
        return array($column_names, $query_data);
      }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  
  <body>
    <nav class="navbar navbar-default navbar-fixed-top nav-top-cust">
      <div class="container">
          <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="navbar-form navbar-left">
            <div class="form-group">
              <select name="sqlDropDown">
                <?php
                  $i = 0;
                  foreach($query_list as $value) {
                    $i == $_POST['sqlDropDown'] ? $selectedTag = "selected" : $selectedTag = "";
                    echo "<option value='" . $i++ . "' $selectedTag >Query 3.3.$i</option>";
                  }
                ?>
              </select>
            </div>
            <button type="submit" type="submit" name="submit" value="Go" class="btn btn-primary">Submit</button>
          </form>
        <span class="navbar-brand navbar-right">University Athlete Queries <i class="fa fa-futbol-o"></i></span>
      </div>
    </nav>
      
    <br>
    <div class="container">
      <div class="row">
        <?php
          if(isset($_POST['sqlDropDown'])) {
            // echo "<div class='well text-center'><b>" . $query_descriptions[$_POST['sqlDropDown']] . "</b></div>";
            
            echo "<div class='col-md-4 col-md-offset-4'><table class='table table-hover table-striped table-cust'>";
            // Runs the sql query, the query is stored in return, 
            // The column names are stored in columns
            list($column_names, $query_data) = run_sql_query();
            
            // Creates the table headers based off of $column_names
            echo "<tr>";
            foreach($column_names as $value) {
              echo "<th class='text-center'>" . ucwords($value->name) . "</th>";
            }
            echo "</tr>";
            
            //Creates the table data
            foreach($query_data as $value) {
              echo "<tr>\n";
              for($i = 0; $i < count($column_names); $i++) {
                is_numeric($value[$i]) ? $textAlign = " class='text-right'" : $textAlign = "";
                echo "<td$textAlign>" . $value[$i] . "</td>";
              }
              echo "\n</tr>\n";
            }
            echo "</table></div>";
          } else {
            echo "<div class='jumbotron text-center'><h2>Please Select a Query to Begin</h2></div>";
          }
          
        ?>
      </div>
    </div>
    <nav class = "navbar navbar-default navbar-fixed-bottom nav-bottom-cust">
      <div class="container">
        <span class="navbar-brand">Pearse Hutson - pah9qd</span>
        <p></p>
        <button class='btn btn-primary navbar-right ' id='queryResults'>
          <?php
          if(isset($_POST['sqlDropDown'])) {
            echo "Number of query results: <span class='badge'>" . count($query_data) . "</span>";
          } else {
            echo "No Query Selected";
          }
          ?>
        </button>
      </dvi>
    </nav>
  </body>
</html>