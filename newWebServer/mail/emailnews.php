<h1> OLÁ, "USUARIO"! </h1>
<h3> ALGUMAS NOTICIAS QUE VOCE PERDEU! </h3>
<br />
<br />
<br />
<div class="table-responsive table-striped ">
  <table class="table table-hover">
    <thead>
        <tr>
            <th> institute_name </th>
            <th> broadcaster_name </th>
            <th> news_id </th>
            <th> news_title </th>
            <th> news_content </th>
        </tr>
    </thead>
    <tbody> 
<?php

foreach ($lastNews as $position => $news) {
    # code...
     echo "<tr>";
        echo "<td>" . $news["institute_name"] . "</td>"; 
        echo "<td>" . $news["broadcaster_name"] . "</td>"; 
        echo "<td>" . $news["news_id"] . "</td>"; 
        echo "<td>" . $news["news_title"] . "</td>"; 
        echo "<td>" . $news["news_content"] . "</td>"; 
    echo "</tr>"; 
}
?>
</tbody> 
</table>
</div>
