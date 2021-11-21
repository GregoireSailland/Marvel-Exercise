<?php 

if(!is_array($_GET) || !array_key_exists('url',$_GET) || empty($_GET['url']) || strpos($_GET['url'],'https://gateway.marvel.com')===false)exit();

class DotEnv{
	protected $path;
	public function __construct(string $path){
		/*if(!file_exists($path)) {
			throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
		}*/
		$this->path = $path;
	}
	public function load() :void{
		if (!file_exists($this->path) || !is_readable($this->path)) {
			return;
			//throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
		}
		$lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) {
				continue;
			}
			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);
			if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
				//$_SERVER[$name] = $value;
			}
		}
	}
}
(new DotEnv(__DIR__ . '/.env'))->load();
header('Access-Control-Allow-Origin: '.(!empty(getenv('ACCESS-CONTROL-ALLOW-ORIGIN'))?getenv('ACCESS-CONTROL-ALLOW-ORIGIN'):(array_key_exists('HTTP_ORIGIN',$_SERVER)?$_SERVER['HTTP_ORIGIN']:($_SERVER['REMOTE_ADDR'].':'.$_SERVER['SERVER_PORT']))));//.',192.168.1.8:8080'
header('Access-Control-Allow-Credentials: true');
define( 'MARVEL_API_PUBLIC_KEY',!empty(
	getenv('MARVEL_API_PUBLIC_KEY')
)?getenv('MARVEL_API_PUBLIC_KEY'):
"12426e8a35704f4bc5020f8dc5c1dec1");
define( 'MARVEL_API_PRIVATE_KEY',!empty(getenv('MARVEL_API_PRIVATE_KEY'))?getenv('MARVEL_API_PRIVATE_KEY'):"889ddddbdc163d2af0f552b13113f135fa411b90");
//$marvel_endpoint="https://gateway.marvel.com:443/v1/public/";
$ts= time();
$url = $_GET['url'];//"$marvel_endpoint$type";
$url .=strpos($url,'?') !== false?'&':'?';
$url .='apikey='. MARVEL_API_PUBLIC_KEY;
	$url .='&ts='.$ts;// date('Y-m-d H:i:s'), 'Europe/London' )
	$url .='&hash='.hash('md5',$ts . MARVEL_API_PRIVATE_KEY . MARVEL_API_PUBLIC_KEY);//,true
	//$url .=$query;
	/*$url .='&orderBy='. 'name';
	$url .='&limit='. 5;
	$url .='&offset='. 0;*/

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPGET, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json'
	));
	$result = curl_exec($ch);
	curl_close($ch);
	echo $result;
	exit();