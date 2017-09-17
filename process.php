<?php
/**
 * Created by Gayan Hewa
 * User: Gayan
 * Date: 6/7/13
 * Time: 3:12 AM
 */
 include('header.php');

class Process
{
    protected $_xml;
    protected $path;

    public function __construct()
    {
        $this->_xml = simplexml_load_file("data.xml");
    }

    public function getXml()
    {
        return $this->_xml;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function userlist()
    {
        echo "<table class='u-full-width'>
				<thead>
					<tr>
					  <th>Name</th>
					  <th>Notes</th>
					</tr>
				</thead>
		
		";

        foreach ($this->_xml as $user) {
            echo "
            <tr>
                    <td>
                     <a href='process.php?edit=" . $user->attributes()->id . "'>{$user->name}</a> 
                    </td>
					
					<td>
						{$user->notes} 
                    </td>
            </tr>
            ";
        }
        echo "</table>";
		
    }

    public function filterList($post)
    {
        $xml = $this->_xml->xpath('user[name="' . strtolower($post) . '"]');
        echo "<table>";

        foreach ($xml as $user) {

            echo "
            <tr>
                    <td>{$user->name}</td>
                    <td>{$user->notes}</td>
            </tr>
            ";
        }
        echo "</table>";
    }
	public function format_xml_file()
	{
		$xmlFile = $this->path;
		if( !file_exists($xmlFile) ) die('Missing file: ' . $xmlFile);
		else
		{
		  $dom = new DOMDocument('1.0');
		  $dom->preserveWhiteSpace = false;
		  $dom->formatOutput = true;
		  $dl = @$dom->load($xmlFile); // remove error control operator //(@) to print any error message generated while loading.
		  if ( !$dl ) die('Error while parsing the document: ' . $xmlFile);
		  $dom->save($xmlFile);
		}
	}
    public function adduser($post)
    {
        if ($post["name"] != "") 
         {
            $xml = $this->_xml;
            $user = $xml->addChild('user');
            $name = $user->addChild("name", $post["name"]);
            $user_notes = $user->addChild("notes", $post["notes"]);
			$user->addAttribute("id", $this->user_id());
            $xml->asXML($this->path);
        }
		$this->format_xml_file();
		header('location:process.php?list');
    }
	public function user_id()
	{
		$id = 0;
		$ids = $this->_xml; 
			foreach ($this->_xml as $ids) 
			{
				if ($id < (int) $ids->attributes()->id)
				{
					$id = $ids->attributes()->id;				
				}
			}
		return $id +=1 ;
	}
	
    public function getUserById($id)
    {
        $user = $this->_xml->xpath('//user[@id="' . $id . '"]');
        return $user[0];
    }

    public function updateUser($post)
    {
        $user = $this->_xml->xpath('user[@id="' . $post['id'] . '"]');
        $user[0]->name = $post["name"];
        $user[0]->notes = $post["notes"];
        $this->_xml->asXML($this->path);
		header('location:process.php?list');
    }
	public function deleteUser($post)
	{
		$id = $post['id'];
		$i = 0;
		foreach ($this->getXml() as $user){
			if ($user->attributes()->id == $id){
				unset($this->getXml()->user[$i]);
				$this->getXml()->asXML($this->path);
				break;
			}
        $i++;
		}
		header('location:process.php?list');
	}
}

//Include template
//include 'index.php';



//Controller
$param = $_SERVER['QUERY_STRING'];
$arr = explode("=", $param);
if (count($arr) > 1) {
    $param = $arr[0];
}
$path = getcwd()."/data.xml";
$process = new Process();
$process->setPath($path);

if ($param == "list") {
    $process->userlist();
}
if ($param == "add") {
    $post = $_POST;
    $process->adduser($post);
	//echo $process->id();
    //include 'user.php';
}
if ($param == "filter") {
    $post = $_POST["name"];
    $process->filterList($post);
}

if ($param == "delete") {
	$post = $_POST;
    $process->deleteUser($post);
}

if ($param == "edit") {
    $id = $arr[1];
    $user = $process->getUserById($id);
    include 'user.php';
}

if ($param == "update") {
    $post = $_POST;
    $process->updateUser($post);
}

include('footer.php');
?>
 