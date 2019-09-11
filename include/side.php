<nav>
    <div class="searchBrowse">
        <div class="search">
            <h3 class="searchTitle">Search for Books</h3>
            <form action="searchBrowse.php">
                <input type="text" name="search" class="searchBox" autofocus>
                <input type="submit" value="Search" class="submitBTN">
            </form>
        </div>
        <div class="browse">
            <h3 class="browseTitle">Browse our Categories!</h3>
            <?php
                $link = fConnectToDatabase();

                $sql = "SELECT DISTINCT CategoryName, bookcategories.CategoryID, COUNT(*)
                        FROM bookcategories, bookcategoriesbooks
                        WHERE bookcategories.CategoryID = bookcategoriesbooks.CategoryID
                        GROUP BY CategoryName
                        ORDER BY COUNT(CategoryName) DESC";

                $result = mysqli_query($link, $sql) or die('SQL syntax error: '.mysqli_error($link));

                while($row = mysqli_fetch_array($result)){
                    echo "<p class = 'browseCategory'><a href = 'searchBrowse.php?browse=$row[CategoryName]'>$row[CategoryName] (". $row['COUNT(*)'].")</a></p>";
                }
            ?>
            <i class="fas fa-tree" id = 'spinTree'></i>
        </div>
    </div>
</nav>
<div class = 'clearFloat'></div>
