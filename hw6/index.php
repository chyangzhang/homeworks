<!-- Chenyang Zhang CSCI 571 homework6 2016 FALL USC -->
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Congress Information Search</title>
<style>
html, body, form, fieldset, table, tr, td, input, select, div, a, img{
	margin: 0 auto;
	padding: 0;
}
#searchform {
	width : 265px;
}
table {
	width : 100%;
}
#formTable tr td {
	width : 50%;
	text-align: center;
}
#result {
    width : 700px;
    text-align: center;
}

#resultTable {
    border-collapse: collapse;
}
#resultTable th, #resultTable td {
    border: 1px solid black;
}
td.name {
    padding-left : 10%;
    text-align : left;
}
.details {
    width : 100%;
    border: 1px solid black;
}
.detailTable {
    width : 70%;
    margin : 20px auto;
    text-align : left;
}
.detailTable td {
    width : 50%;
}

#bioTable {
    left-padding : 30%;
}

img {
    display : block;
    margin : 20px auto;
}

</style>

<script type="text/javascript">
    function clearForm() {
        document.getElementById("congressdb").selectedIndex = 0;
        document.getElementById("senate").checked = "checked";
        document.getElementById("keyword").innerHTML = "Keyword*";
        document.getElementById("keywordText").value = "";
        document.getElementById("result").innerHTML = "";
    }

    function validation() {
        var str = document.getElementById("keywordText").value.trim();
        str = str.replace(/ /g, '_');
        document.getElementById("keywordText").value = str;
        if (document.getElementById("congressdb").value == "default" || document.getElementById("keywordText").value == "") {
            var count = 0;
            var warning = "Please enter the following missing information:";
            if (document.getElementById("congressdb").value == "default") {
                warning += " Congress database";
                count++;
            }
            if (document.getElementById("keywordText").value == "") {
                if(count > 0) warning += ",";
                warning += " keyword";
            }
            alert(warning);
            return false;
        }
        return true;
    }

    function changeKeyword() {
        var db = document.getElementById("congressdb").value;
        if(db == "default") document.getElementById("keyword").innerHTML = "Keyword*";
        if(db == "legislators") document.getElementById("keyword").innerHTML = "State/Representative*";
        if(db == "committees") document.getElementById("keyword").innerHTML = "Committee ID*";
        if(db == "bills") document.getElementById("keyword").innerHTML = "Bill ID*";
        if(db == "amendments") document.getElementById("keyword").innerHTML = "Amendment ID*";
        document.getElementById("keywordText").value = "";

    }

</script>

</script>

<?php
    $apikey = "0ba00a02ec0b4eaf842c420b30266251";
    $states = array(
        'Alabama'=>'AL',
        'Alaska'=>'AK',
        'Arizona'=>'AZ',
        'Arkansas'=>'AR',
        'California'=>'CA',
        'Colorado'=>'CO',
        'Connecticut'=>'CT',
        'Delaware'=>'DE',
        'Florida'=>'FL',
        'Georgia'=>'GA',
        'Hawaii'=>'HI',
        'Idaho'=>'ID',
        'Illinois'=>'IL',
        'Indiana'=>'IN',
        'Iowa'=>'IA',
        'Kansas'=>'KS',
        'Kentucky'=>'KY',
        'Louisiana'=>'LA',
        'Maine'=>'ME',
        'Maryland'=>'MD',
        'Massachusetts'=>'MA',
        'Michigan'=>'MI',
        'Minnesota'=>'MN',
        'Mississippi'=>'MS',
        'Missouri'=>'MO',
        'Montana'=>'MT',
        'Nebraska'=>'NE',
        'Nevada'=>'NV',
        'New Hampshire'=>'NH',
        'New Jersey'=>'NJ',
        'New Mexico'=>'NM',
        'New York'=>'NY',
        'North Carolina'=>'NC',
        'North Dakota'=>'ND',
        'Ohio'=>'OH',
        'Oklahoma'=>'OK',
        'Oregon'=>'OR',
        'Pennsylvania'=>'PA',
        'Rhode Island'=>'RI',
        'South Carolina'=>'SC',
        'South Dakota'=>'SD',
        'Tennessee'=>'TN',
        'Texas'=>'TX',
        'Utah'=>'UT',
        'Vermont'=>'VT',
        'Virginia'=>'VA',
        'Washington'=>'WA',
        'West Virginia'=>'WV',
        'Wisconsin'=>'WI',
        'Wyoming'=>'WY'
    ); 
?>

</head>

<body>
	<h2 align = "center">Congress Information Search</h2>
    <form id = "searchform" action = "" method = "POST" onsubmit="return validation()">
    	<fieldset>
        	<table id = "formTable"> 
            	<tr>
                	<td>
                        <label>Congress Database<label>
                    </td>
                    <td> 
                        <select id = "congressdb" name = "congressdb" onchange = "changeKeyword()">
                            <option value = "default"> Select your option </option>
                            <option value = "legislators" <?php echo (isset($_POST["congressdb"]) && $_POST["congressdb"] == "legislators") || (isset($_POST["bioguide"]))  ? "selected=\"selected\"" : "" ?>> 
                            Legislators </option>
                            <option value = "committees" <?php echo isset($_POST["congressdb"]) && $_POST["congressdb"] == "committees"  ? "selected=\"selected\"" : "" ?>> 
                            Committees </option>
                            <option value = "bills" <?php echo (isset($_POST["congressdb"]) && $_POST["congressdb"] == "bills") || (isset($_POST["bill_id"]))  ? "selected=\"selected\"" : "" ?>>
                            Bills </option>
                            <option value = "amendments" <?php echo isset($_POST["congressdb"]) && $_POST["congressdb"] == "amendments"  ? "selected=\"selected\"" : "" ?>>
                            Amendments </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Chamber</label>
                    </td>
                    <td> 
                        <input type = "radio" name = "chamber" id = "senate" value = "senate" checked = "checked"/> Senate
                        <input type = "radio" name = "chamber" id = "house" value = "house" <?php echo isset($_POST["chamber"]) && $_POST["chamber"] == "house"  ? "checked=\"checked\"" : "" ?>/> House
                    </td>
                </tr>
                <tr>
                    <td>
                        <label id = "keyword">
                            <?php
                                if(isset($_POST["congressdb"])) {
                                    switch ($_POST["congressdb"]) {
                                        case 'legislators':
                                            echo "State/Representative*";
                                            break;
                                        case 'committees':
                                            echo "Committee ID*";
                                            break;
                                        case 'bills':
                                            echo "Bill ID*";
                                            break;
                                        case 'amendments':
                                            echo "Amendment ID*";
                                            break;
                                    }
                                }
                                elseif(isset($_POST["bioguide"])) {
                                    echo "State/Representative*";
                                }
                                elseif(isset($_POST["bill_id"])) {
                                    echo "Bill ID*";
                                }
                                else {
                                    echo "Keyword*";
                                }
                            ?>
                         </label>
                    </td> 
                    <td>
                        <input type = "text" id = "keywordText" name = "keywordText" 
                                <?php $str = "";
                                    if(isset($_POST["keywordText"])) {
                                        $str = str_replace("_", " ", $_POST["keywordText"]);
                                    }
                                    elseif (isset($_POST["bioguide"])) {
                                        $str = str_replace("_", " ", $_POST["state"]);
                                    }
                                    elseif (isset($_POST["bill_id"])) {
                                        $str = str_replace("_", " ", $_POST["bill_id"]);
                                    }
                                    echo "value = \"{$str}\"";
                                ?>>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type = "submit" name = "submit" value = "Submit" />
                        <input type = "button" name = "clear" value = "Clear" onclick="clearForm()" />
                    </td>
                </tr>
        	</table>
            <div align = "center">
            	<a href = "http://sunlightfoundation.com/" target="_blank"> Powerd by Sunlight Foudation</a>
            </div>
        </fieldset>
    </form>
    <br/>
    <br/>
    <div id = "result">
    <?php
    if(isset($_POST["submit"])) {
        $keyword = $_POST["keywordText"];
        $searchurl = "http://congress.api.sunlightfoundation.com/";
        switch ($_POST["congressdb"]) {
            case 'legislators':
                $statename = ucfirst(strtolower($keyword));
                $searchurl .= "legislators?chamber={$_POST["chamber"]}";
                if(isset($states[$statename]))
                    $searchurl .= "&state={$states[$statename]}";
                else {
                    $words = explode("_", $keyword);
                    foreach ($words as $word)
                        $searchurl .= "&query={$word}";
                }
                break;
            case 'committees':
                $searchurl .= "committees?committee_id={$keyword}&chamber={$_POST["chamber"]}";
                break;
            case 'bills':
                $searchurl .= "bills?bill_id={$keyword}&chamber={$_POST["chamber"]}";
                break;
            case 'amendments':
                $searchurl .= "amendments?amendment_id={$keyword}&chamber={$_POST["chamber"]}";
                break;
        }
        $searchurl .= "&apikey={$apikey}";

        $result = file_get_contents($searchurl);
        if (!empty($result)) {
            $resultArr = json_decode($result, true);
            if ($resultArr["count"] == 0) {
                echo "<p>The API returned zero result for the request.</p>";
            }
            elseif ($resultArr["count"] > 0) {
                echo  "<table id='resultTable'>";
                switch ($_POST["congressdb"]) {
                    case 'legislators':
                        echo "<tr>
                                <th>Name</th>
                                <th>State</th>
                                <th>Chamber</th>
                                <th>Details</th>
                              </tr>";
                        foreach($resultArr["results"] as $legislator) {
                            echo "<tr>
                                    <td class = \"name\">{$legislator["first_name"]} {$legislator["last_name"]}</td>
                                    <td>{$legislator["state_name"]}</td>
                                    <td>{$legislator["chamber"]}</td>
                                    <td>
                                        <form action = \"\" method = \"POST\">
                                           <input type=\"hidden\" name=\"chamber\" value={$legislator["chamber"]} />
                                           <input type=\"hidden\" name=\"state\" value={$_POST["keywordText"]} />
                                           <input type=\"hidden\" name=\"bioguide\" value={$legislator["bioguide_id"]} />
                                           <a href = \"\" onclick=\"this.parentNode.submit(); return false;\">View Details</a></td>
                                        </form>
                                  </tr>";
                        }
                        break;
                    case 'committees':
                        echo "<tr>
                                <th>Committee ID</th>
                                <th>Committee Name</th>
                                <th>Chamber</th>
                              </tr>";
                        foreach($resultArr["results"] as $committee) {
                            echo "<tr>
                                    <td>{$committee["committee_id"]}</td>
                                    <td>{$committee["name"]}</td>
                                    <td>{$committee["chamber"]}</td>
                                  </tr>";
                        }
                        break;
                    case 'bills':
                        echo "<tr>
                                <th>Bill ID</th>
                                <th>Short Title</th>
                                <th>Chamber</th>
                                <th>Details</th>
                              </tr>";
                        foreach($resultArr["results"] as $bill) {
                            echo "<tr>
                                    <td>{$bill["bill_id"]}</td>
                                    <td>{$bill["short_title"]}</td>
                                    <td>{$bill["chamber"]}</td>
                                    <td>
                                        <form action = \"\" method = \"POST\">
                                               <input type=\"hidden\" name=\"chamber\" value={$bill["chamber"]} />
                                               <input type=\"hidden\" name=\"bill_id\" value={$_POST["keywordText"]} />
                                               <a href = \"\" onclick=\"this.parentNode.submit(); return false;\">View Details</a></td>
                                        </form>
                                    </td>
                                </tr>";
                        }
                        break;
                    case 'amendments':
                        echo "<tr>
                                <th>Amendment ID</th>
                                <th>Amendment Type</th>
                                <th>Chamber</th>
                                <th>Introduced on</th>
                              </tr>";
                        foreach($resultArr["results"] as $amendment) {
                            echo "<tr>
                                    <td>{$amendment["amendment_id"]}</td>
                                    <td>{$amendment["amendment_type"]}</td>
                                    <td>{$amendment["chamber"]}</td>
                                    <td>{$amendment["introduced_on"]}</td>
                                  </tr>";
                        }
                        break;
                }
                echo  "</table>";
            }
        }
    }

    if(isset($_POST["bioguide"])) {
        $biourl = "http://congress.api.sunlightfoundation.com/legislators?bioguide_id={$_POST["bioguide"]}&apikey={$apikey}";
        $bioRes = json_decode(file_get_contents($biourl), true);
        if ($bioRes["count"] == 0) {
            echo "<p>The API returned zero result for the request.</p>";
        }
        else {
            $bioArr = $bioRes["results"][0];
            echo "<div class = \"details\">
                    <img src = \"http://theunitedstates.io/images/congress/225x275/{$_POST["bioguide"]}.jpg\" alt = \"profile image\"/>
                    <table id = \"bioTable\" class = \"detailTable\">
                        <tr>
                            <td> Full Name </td>
                            <td>{$bioArr["title"]} {$bioArr["first_name"]} {$bioArr["last_name"]}</td>
                        </tr>
                        <tr>
                            <td> Term Ends on </td>
                            <td>";
                            if(empty($bioArr["term_end"]))
                                echo "N/A";
                            else
                                echo "{$bioArr["term_end"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td> Website </td>
                            <td>";
                            if(empty($bioArr["website"]))
                                echo "N/A";
                            else
                                echo "<a href = {$bioArr["website"]} target = \"_blank\">{$bioArr["website"]}</a>";
                        echo "</td>
                        </tr>
                        <tr>
                            <td> Office </td>
                            <td>";
                            if(empty($bioArr["office"]))
                                echo "N/A";
                            else
                                echo "{$bioArr["office"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td> Facebook </td>
                            <td>";
                            if(empty($bioArr["facebook_id"]))
                                echo "N/A";
                            else
                                echo "<a href = \"http://www.facebook.com/{$bioArr["facebook_id"]}\" target = \"_blank\">{$bioArr["first_name"]} {$bioArr["last_name"]}</a>";
                        echo "</td>
                        </tr>
                        <tr>
                            <td> Twitter </td>
                            <td>";
                            if(empty($bioArr["twitter_id"]))
                                echo "N/A";
                            else
                                echo "<a href = \"http://twitter.com/{$bioArr["twitter_id"]}\" target = \"_blank\">{$bioArr["first_name"]} {$bioArr["last_name"]}</a>";
                        echo "</td>
                        </tr>
                     </table>
                </div>";
         }
    }
    if(isset($_POST["bill_id"])) {
        $billurl = "http://congress.api.sunlightfoundation.com/bills?bill_id={$_POST["bill_id"]}&apikey={$apikey}";
        $billRes = json_decode(file_get_contents($billurl), true);
        if ($billRes["count"] == 0) {
            echo "<p>The API returned zero result for the request.</p>";
        }
        else {
            $billdetails = $billRes["results"][0];
            echo "<div class = \"details\">
                    <table id = \"billTable\" class = \"detailTable\">
                        <tr>
                            <td>Bill ID</td>
                            <td>{$billdetails["bill_id"]}</td>
                        </tr>
                        <tr>
                            <td>Bill Title</td>
                            <td>";
                            if(empty($billdetails["short_title"]))
                                echo "N/A";
                            else
                                echo "{$billdetails["short_title"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td>Sponsor</td>
                            <td>";
                            if(empty($billdetails["sponsor"]))
                                echo "N/A";
                            else
                                echo "{$billdetails["sponsor"]["title"]} {$billdetails["sponsor"]["first_name"]} {$billdetails["sponsor"]["last_name"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td>Introduced On</td>
                            <td>";
                            if(empty($billdetails["introduced_on"]))
                                echo "N/A";
                            else
                                echo "{$billdetails["introduced_on"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td>Last action with date</td>
                            <td>";
                            if(empty($billdetails["last_version"]))
                                echo "N/A";
                            else
                                echo "{$billdetails["last_version"]["version_name"]}";
                            echo ", ";
                            if(empty($billdetails["last_action_at"]))
                                echo "N/A";
                            else
                                echo "{$billdetails["last_action_at"]}";
                        echo "</td>
                        </tr>
                        <tr>
                            <td>Bill URL</td>
                            <td>";
                            if(empty($billdetails["last_version"]["urls"]["pdf"]) || empty($billdetails["last_version"]["urls"]) || empty($billdetails["last_version"]))
                                echo "N/A";
                            elseif(empty($billdetails["short_title"]))
                                echo "<a href = \"{$billdetails["last_version"]["urls"]["pdf"]}\" target = \"_blank\">{$billdetails["bill_id"]}</a>";
                            else
                                echo "<a href = \"{$billdetails["last_version"]["urls"]["pdf"]}\" target = \"_blank\">{$billdetails["short_title"]}</a>";
                            echo "</td>
                        </tr>
                    </table>
                </div>";
         }
    }
    ?>
    </div>
    <noscript>
</body>
</html>