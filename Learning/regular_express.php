<?php
// read contents of BCLE webpage
$html = file_get_contents('https://www.bcel.com.la/bcel/home.html');

// patthern to crop the table containing the exchange rate
$pattern = "/<table id=\"fxRate\".*?>(.*?)<\/table>/is";

preg_match($pattern, $html, $matches);

// Replace $html with matched content
$html = $matches[1];

// pattern to crop all <tr> (the table rows)
$pattern = "/<tr>(.*?)<\/tr>/is";

preg_match_all($pattern, $html, $matches);

// Replace $matches with matched content
$matches = $matches[1];

// delete first and last element out of the array $matches
array_shift($matches);
array_pop($matches);

$data = [];
foreach ($matches as $key => $value) {
    // pattern to crop contents of each <td>
    $pattern = "/<td.*?>(.*?)<\/td>/is";
    preg_match_all($pattern, $value, $matches2);

    // Remodify img src
    $flag_img = $matches2[1][0];

    if (preg_match('/<img.*?src="(.*?)".*?>/i', $flag_img, $matches3)) {
        $flag_img = "<img src=\"https://www.bcel.com.la{$matches3[1]}\">";
    }
    $matches2[1][0] = $flag_img;

    // Collect the exchange rate data to $data array
    $data[$key] = $matches2[1];
}

?>
 <!-- show the exchange rate in a table -->
<table border="1">
    <tr>
        <th>ສະກຸນເງິນ</th>
        <th>ອັນຕາຊື້</th>
        <th>ອັນຕາຂ່າຍ</th>
    </tr>
<?php
    foreach ($data as $key => $value) {
        echo "<tr>";
        echo "<td>$value[0] $value[1]</td><td>$value[2]</td><td>$value[3]</td>";
        echo "</tr>";
    }
    ?>
</table>