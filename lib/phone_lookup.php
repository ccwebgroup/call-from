<?php
/**
 * Library for abstraction of methods to use Whitepages Pro API
 * @require     PHP5
 *
 * @author      Kushal Shah
 * @date        2014-06-04
 */

/**
* Updated by Teddy Patriarca to cater the new api structure
*
*/

include 'whitepages_lib.php';
include 'result.php';

if(!empty($userPhone)) 
{
    if (!empty($userPhone)) 
    {
        
        $param = array(
            'phone' => trim($userPhone)
        );
       
        if (!function_exists('curl_init'))
        {
            die('cURL is not installed. Install and try again.');
        }   
        $url = 'http://www.allareacodes.com/'.substr($userPhone,0,3);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: 1.2.3.4", "HTTP_X_FORWARDED_FOR: 1.2.3.4"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        libxml_use_internal_errors( true);
        $doc =  new DOMDocument();
        $doc->loadHTML($output);
        $xpath = new DOMXpath( $doc);

        // A name attribute on a <div>???
        $node = $xpath->query('/html/body/script[4]')->item( 0);;

        $arr = $doc->getElementsByTagName("script"); // DOMNodeList Object
        foreach($arr as $item) 
        { // DOMElement Object
            //$href =  $item->getAttribute("href");
            $text = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
            $links[] = array(
            //'href' => $href,
            'text' => $text
            );
        }
        
       
        $scrRaw = explode(',', $links[2]['text']);

        if(!empty($scrRaw[0]))
        {
            $scr1 = explode('(', $scrRaw[0]);
            $scr2 = explode(')', $scrRaw[1]);
            $lat = $scr1[2];
            $lng = $scr2[0];

           /* echo "latitude: " . $lat ;
            echo "<br/>";
            echo "logitude: " . $lng;*/
            ?>
            <script>
                var searchedPhone = '<?php echo $userPhone ?>';
                var lat = '<?php echo $lat ?>';
                var lng = '<?php echo $lng ?>';
                var notfound = true;
            </script>
            <?php
        }
        else
        {
            ?>
            <script>
                var invalidPhone = true;
            </script>
            <?php
        }
        //var_dump($lng);exit();
        //echo $doc->saveHTML(); // This will print **GET THIS TEXT**
        //echo htmlspecialchars($output,ENT_QUOTES, 'utf-8');
/*        var_dump($node->textContent);
        exit();*/

/*      $whitepages_obj = new WhitepagesLib();
        $api_response = $whitepages_obj->reverse_phone($param);
        try 
        {
            if (array_key_exists('error', $api_response) === true) 
            {
                throw new Exception;
            }
            $result = new Result($api_response);
?>
<pre>
<?php print_r($result);exit();?>
</pre>
<?php
        } 
        catch(Exception $exception) 
        {
            echo "Error Api response";
            echo '<pre>';print_r($api_response);echo "</pre>"; //exit();
            exit();
        }*/
    } 
    else 
    {
        $error = 'Please enter phone number';
    }
}

