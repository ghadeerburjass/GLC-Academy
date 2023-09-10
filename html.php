<?php

function GetPage($URL)
{
    #Get the source content of the URL
    $source = file_get_contents($URL);

    #Extract the raw URl from the current one
    $scheme = parse_url($URL, PHP_URL_SCHEME); //Ex: http
    $host = parse_url($URL, PHP_URL_HOST); //Ex: www.google.com
    $raw_url = $scheme . '://' . $host; //Ex: http://www.google.com

    #Replace the relative link by an absolute one
    $relative = array();
    $absolute = array();

    #String to search
    $relative[0] = '/src="\//';
    $relative[1] = '/href="\//';

    #String to remplace by
    $absolute[0] = 'src="' . $raw_url . '/';
    $absolute[1] = 'href="' . $raw_url . '/';

    $source = preg_replace($relative, $absolute, $source); //Ex: src="/image/google.png" to src="http://www.google.com/image/google.png"

    return $source;
}

function SaveToDB($source)
{
    #Connect to the DB
    $db = mysql_connect('localhost', 'root', '');

    #Select the DB name
    mysql_select_db('test');

    #Ask for UTF-8 encoding
    mysql_query("SET NAMES 'utf8'");

    #Escape special chars
    $source = mysql_real_escape_string($source);

    #Set the Query
    $query = "INSERT INTO website (source) VALUES ('$source')"; //Save it in a text row, that's it...

    #Run the query
    mysql_query($query);

    #Close the connection
    mysql_close($db);
}

$source = GetPage('http://www.google.com');

SaveToDB($source);

?>