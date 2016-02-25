<?php
/*getting the json array input getting from android phone*/
$json = file_get_contents('php://input');
//decoding the json string into an array
$obj = json_decode($json,true);

/*the host ,database ,user, and password for setting up connection with database*/
$mysql_host = "mysq851.435eb34st.com";
$mysql_database = "a4106109_hassan";
$mysql_user = "s941573_ism";
$mysql_password = "softwareproject";
/**/

// Create connection with database
$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password,$mysql_database);

/*checking the variable in the first object of array that contains whether to insert an new score or getting all highscores*/
if($obj[0]['type']=="insert"){//if true means insert a new score
//sql query for insert
$check="SELECT score FROM scores  ORDER BY score LIMIT 1";
//excuting query
$querycheck=$conn->query($check);
if($querycheck){
//getting the row	
$row1 = $querycheck->fetch_assoc();
//if it is greater than the lowest score in the table in database
if($row1['score']<$obj[0]['score']){
//delete the last score
$deleting="DELETE FROM scores WHERE score='".$row1['score']."' LIMIT 1";
$querydel=$conn->query($deleting);
//inserting the new score into database
$sql = "INSERT INTO scores (name, score) VALUES ('".$obj[0]['name']."',".$obj[0]['score'].")";
if (mysqli_query($conn, $sql)) {
    $obj[0][]="success";
    echo json_encode($obj);//encode back to json with string message success which is send as http page to android phone
} else {
    $obj[0][]="failed";
    echo json_encode($obj);//encode back to json with string message failed which is send as http page to android phone
}}
}
}else if($obj[0]['type']=="getscores"){//if fo getting highscore
//the json array sent from phone to here contain a variable limit that defines us the number of score to send back to android phone
//query for getting highscores
$sql = "SELECT name, score  FROM scores  ORDER BY score DESC LIMIT ".$obj[0]['limit'];
$result = $conn->query($sql);

if($result){
$arr=array();
while($row = $result->fetch_assoc()) {
        $arr[]=$row;

}//end loop
echo json_encode($arr);//encode the array back to json and will be received by phone
}}?>
