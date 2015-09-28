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
        max-width: 50%;
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
        // Query 1  
        "SELECT District, Population 
          FROM city 
          WHERE Name='Springfield' 
          ORDER BY Population DESC",
        // Query 2
        "SELECT name, district, population 
          FROM city 
          WHERE CountryCode = 'BRA' 
          ORDER BY name",
        // Query 3
        "SELECT name, continent, surfacearea AS `Surface Area` 
          FROM country 
          ORDER BY surfacearea 
          LIMIT 20",
        // Query 4
        "SELECT name, 
                continent, 
                governmentform AS `Form of Government`, 
                gnp AS `GNP` 
          FROM country 
          WHERE gnp > 200000 
          ORDER BY name",
        // Query 5
        "SELECT name 
          FROM country 
          WHERE lifeexpectancy IS NOT NULL 
          ORDER BY lifeexpectancy 
          LIMIT 10 
          OFFSET 9",
        // Query 6
        "SELECT name 
          FROM city 
          WHERE name like 'B%s' 
          ORDER BY population DESC",
        // Query 7
        "SELECT city.name AS `City Name`, 
                country.name AS `Country Name`, 
                city.population AS `City Population` 
          FROM city 
          INNER JOIN country 
          ON city.countrycode = country.code 
          WHERE city.population > 6000000 
          ORDER BY city.population DESC",
        // Query 8
        "SELECT country.name AS `Country Name`, 
                country.indepyear AS `Year of Independence`, 
                country.region 
          FROM country 
          INNER JOIN countrylanguage 
          ON country.code = countrylanguage.countrycode 
          WHERE countrylanguage.language = 'English' 
          AND countrylanguage.isofficial='T' 
          ORDER BY country.region, country.name",
        // Query 9
        "SELECT countryName AS `Country Name`, 
                cityName AS `City Name`, 
                (cityPop / countryPop) AS `Percent of Population In Capital` 
          FROM 
            (SELECT country.capital, country.name AS 'countryName', 
                    city.name AS 'cityName', 
                    city.population AS 'cityPop', 
                    country.population AS 'countryPop' 
            FROM city 
            INNER JOIN country 
            ON country.capital = city.id) AS table1 
          ORDER BY `Percent of Population In Capital` DESC",
        // Query 10
        "SELECT language, 
                name, 
                ((percentage * population) / 100) AS `Percentage of Speakers` 
          FROM country 
          INNER JOIN 
            (SELECT countrycode, language, percentage 
            FROM countrylanguage 
            WHERE isOfficial = 'T') AS languageTable 
          ON country.code = languageTable.countrycode 
          ORDER BY `Percentage of Speakers` DESC",
        // Query 11
        "SELECT name, 
                region, 
                gnp AS `GNP`, 
                gnpold AS `Old GNP`, 
                ((gnp - gnpold) / gnpold) AS `Real GNP Change` 
          FROM country 
          WHERE gnp IS NOT NULL 
          AND gnpold IS NOT NULL 
          ORDER BY `Real GNP Change` DESC");
    
    $query_descriptions = array (
      // Query 1
      "The district and population of all cities named Springfiled",
      // Query 2
      "The name, district, and population of each city in Brazil",
      // Query 3
      "The 20 smallest countries by surface area",
      // Query 4
      "All countries with a GNP greater than 200,000",
      // Query 5
      "The 10th through 19th countries in life expectancy",
      // Query 6
      "All cities that start with the letter 'B' and end in the letter 's'",
      // Query 7
      "All cities with a population greater than 6 million",
      // Query 8
      "All countries where English is an offical language, and their year of independence",
      // Query 9
      "Capital cities and the percentage of the county population in the captial",
      // Query 10
      "Offical language of all cities and the number of speakers of that language",
      // Query 11
      "The GNP for all countries, sorted by the most improved relative wealth"
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
                    echo "<option value='" . $i++ . "' $selectedTag >Query $i</option>";
                  }
                ?>
              </select>
            </div>
            <button type="submit" type="submit" name="submit" value="Go" class="btn btn-primary">Submit</button>
          </form>
        <span class="navbar-brand navbar-right">World Database Queries <i class="fa fa-globe fa-lg"></i></span>
      </div>
    </nav>
      
    <br>
    <div class="container">
      <div class="row">
        <?php
          if(isset($_POST['sqlDropDown'])) {
            echo "<div class='well text-center'><b>" . $query_descriptions[$_POST['sqlDropDown']] . "</b></div>";
            
            echo "<table class='table table-hover table-striped table-cust'>";
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
          } else {
            echo "<div class='jumbotron text-center'><h2>Please Select a Query to Begin</h2></div>";
          }
          
        ?>
        </table>
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