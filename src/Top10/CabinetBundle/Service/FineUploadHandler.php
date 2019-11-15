<?php

/**
 * Do not use or reference this directly from your client-side code.
 * Instead, this should be required via the endpoint.php or endpoint-cors.php
 * file(s).
 */
namespace Top10\CabinetBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Top10\CabinetBundle\Entity\Slides;


class FineUploadHandler
{

	/**
     * @var ContainerInterface $container
     */
	protected $container;
	/**
     * @var EntityManager $em
     */
    protected $em;
	/**
     * @var Request $request
     */
    //protected $request;
	

	public $allowedExtensions = array();
    public $sizeLimit = null;
    public $inputName;
    public $dirName;
    public $chunksFolder;


    public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
    public $chunksExpireIn = 604800; // One week

    protected $uploadName;

	public function __construct(ContainerInterface $container)
	{
		$this->em = $container->get('doctrine.orm.entity_manager');
		$this->container = $container;
		$this->request = $container->get('request');
		$this->inputName = "qqfiles"; // matches Fine Uploader's default inputName value by default
		$this->sizeLimit = null;
		$this->allowedExtensions = array(); // all files types allowed by default
	}



	public function getUploadImgAction($dir, $id, $type='Posts', $thumbe='slide' )
	{
		$find = $this->em->getRepository('Top10CabinetBundle:'. $type )->find( $id );

		$this->dirName = $id;

		// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
		//$this->chunksFolder = "chunks";

		$method = $_SERVER["REQUEST_METHOD"];

		//$dir = join( DIRECTORY_SEPARATOR, array( 'img', 'room', $id ) );

		//print_r ($_REQUEST );

		if ( $this->request->isMethod('POST') &&  $this->request->request->get('qqfilename') ) {
			header("Content-Type: text/plain");

			// Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
			// For example: /myserver/handlers/endpoint.php?done
			if ( $this->request->query->get('done') ) {
				$result = $this->combineChunks($dir);
			}
			// Handles upload requests
			else {
				/** @var $postSearch \Top10\CabinetBundle\Service\TranslateChar */
				$translateChar = $this->container->get('cabinet.translate_char');
				$_REQUEST['qqfilename'] = str_replace (" ",  "", $translateChar->getInTranslateToEn($_REQUEST['qqfilename']) );

				// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
				$result = $this->handleUpload($dir);

				// To return a name used for uploaded file you can use the following line.
				$result["uploadName"] = $this->getUploadName();

				//--------------------THUMB image-----------------------------
				$imagemanagerResponse = $this->container
					->get('liip_imagine.controller')
					->filterReplaceFileAction(
						$this->request,
						$result['terget'],
						join( DIRECTORY_SEPARATOR, array( $_SERVER["DOCUMENT_ROOT"], $result['terget'] ) ),
						$thumbe
					);
				//--------------------/THUMB image-----------------------------

				//--------------------------ВСТАВКА ФАЙЛА В ТАБЛИЦУ SLIDERS--------------------------
				$arrSlidesJson = $this->getSlidesUploadsSession( $dir, $id, $type );
				$arrSlides = json_decode($arrSlidesJson);

				//print_r( $arrSlides );
				$sort = count( $arrSlides ) + 1;

				$slides = new slides();

				if( $find )
					eval('$slides->set'. $type .'( $find );');
				else
					$slides->setCode( $id );

				if( $this->request->query->get( 'type' ) ){
					$slides->setType( $this->request->query->get( 'type' ) );
				}

				$slides->setImage( join( DIRECTORY_SEPARATOR, array( '', $result['terget'] ) ) );
				$slides->setSort( $sort );
				$this->em->persist( $slides );
				$this->em->flush();
				//--------------------------/ВСТАВКА ФАЙЛА В ТАБЛИЦУ SLIDERS--------------------------
			}

			$response = array( 'jsoninput' => json_encode( $result ) );

			//$response = new Response(json_encode($result));
			//$response->headers->set('Content-Type', 'application/json; charset=UTF-8');
			//return $this->json( $result );

		}
		// for delete file requests
		else if ( $this->request->request->get('_method') == "DELETE" ) {

			$result = $this->handleDelete($dir);

			$response =  array( 'jsoninput' => json_encode( $result ) );
			//--------------------------УДАЛЕНИЕ ФАЙЛА ИЗ ТАБЛИЦЫ SLIDERS--------------------------
			if( $result['success'] == true ){
				$qb = $this->em->createQueryBuilder();

				$qb->delete('Top10CabinetBundle:Slides','s')
					->andWhere('s.image LIKE :uuid')
						->setParameter('uuid', '%'.$result['uuid'].'%');

				if( $find )
					$qb->andWhere('s.' . strtolower($type) . ' = :id')->setParameter( 'id', $id );
				else
					$qb->andWhere('s.code = :id')->setParameter( 'id', $id );

				$q = $qb->getQuery()->execute();

			}
			$this->getSlidesUploadsSession( $dir, $id, $type );//перезаписываем сортировку
			//--------------------------/УДАЛЕНИЕ ФАЙЛА ИЗ ТАБЛИЦЫ SLIDERS--------------------------

			//$response = new Response(json_encode($result));
			//$response->headers->set('Content-Type', 'application/json; charset=UTF-8');
		}
		else {
			//header("HTTP/1.0 405 Method Not Allowed");
			$response =  array( 'jsoninput' => null );
		}

		return $response;
	}

	/**
     * Get the original filename
     */
    public function getName(){
		if (isset($_REQUEST['qqfilename']))
			return $_REQUEST['qqfilename'];

        if (isset($_FILES[$this->inputName]))            
            return $_FILES[$this->inputName]['name'];
    }

    public function getInitialFiles() {
        $initialFiles = array();

        for ($i = 0; $i < 5000; $i++) {
            array_push($initialFiles, array("name" => "name" + $i, uuid => "uuid" + $i, thumbnailUrl => "/test/dev/handlers/vendor/fineuploader/php-traditional-server/fu.png"));
        }

        return $initialFiles;
    }

    /**
     * Get the name of the uploaded file
     */
    public function getUploadName(){
        return $this->uploadName;
    }

    public function combineChunks($uploadDirectory, $name = null)
	{        
        $uuid = $_POST['qquuid'];
        if ($name === null){
            $name = $this->getName();
        }
        $targetFolder = $this->chunksFolder.DIRECTORY_SEPARATOR.$uuid;
        $totalParts = isset($_REQUEST['qqtotalparts']) ? (int)$_REQUEST['qqtotalparts'] : 1;

        $targetPath = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $uuid, $name));
        $this->uploadName = $name;

        if (!file_exists($targetPath)){
            mkdir(dirname($targetPath), 0777, true);
        }
        $target = fopen($targetPath, 'wb');

        for ($i=0; $i<$totalParts; $i++){
            $chunk = fopen($targetFolder.DIRECTORY_SEPARATOR.$i, "rb");
            stream_copy_to_stream($chunk, $target);
            fclose($chunk);
        }

        // Success
        fclose($target);

        for ($i=0; $i<$totalParts; $i++){
            unlink($targetFolder.DIRECTORY_SEPARATOR.$i);
        }

        rmdir($targetFolder);

        if (!is_null($this->sizeLimit) && filesize($targetPath) > $this->sizeLimit) {
            unlink($targetPath);
            http_response_code(413);
            return array("success" => false, "uuid" => $uuid, "preventRetry" => true);
        }

        return array("success" => true, "uuid" => $uuid);
    }

    /**
     * Process the upload.
     * @param string $uploadDirectory Target directory.
     * @param string $name Overwrites the name of the file.
     */
    public function handleUpload($uploadDirectory, $name = null)
	{

		if (is_writable($this->chunksFolder) &&
            1 == mt_rand(1, 1/$this->chunksCleanupProbability)){

            // Run garbage collection
            $this->cleanupChunks();
        }

        // Check that the max upload size specified in class configuration does not
        // exceed size allowed by server config
        if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit ||
            $this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit){
            $neededRequestSize = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            return array('error'=>"Server error. Increase post_max_size and upload_max_filesize to ".$neededRequestSize);
        }

        //если ОС Винда то проверяет доступ для записи пока убрал
		//if ($this->isInaccessible($uploadDirectory)){
        //    return array('error' => "Server error. " . $uploadDirectory . " Uploads directory isn't writable");
        //}

        $type = $_SERVER['CONTENT_TYPE'];
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            $type = $_SERVER['HTTP_CONTENT_TYPE'];
        }

        if(!isset($type)) {
            return array('error' => "No files were uploaded.");
        } else if (strpos(strtolower($type), 'multipart/') !== 0){
            return array('error' => "Server error. Not a multipart request. Please set forceMultipart to default value (true).");
        }

        // Get size and name
        $file = $_FILES[$this->inputName];


        $size = $file['size'];

//return array('FILES'=>  $size );

        if (isset($_REQUEST['qqtotalfilesize'])) {
            $size = $_REQUEST['qqtotalfilesize'];
        }

        if ($name === null){
            $name = $this->getName();
        }

        // check file error
        if($file['error']) {
            return array('error' => 'Upload Error #'.$file['error']);
        }
        	
        // Validate name
        if ($name === null || $name === ''){
            return array('error' => 'File name empty.');
        }

        // Validate file size
        if ($size == 0){
            return array('error' => 'File is empty.');
        }

        if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
            return array('error' => 'File is too large.', 'preventRetry' => true);
        }

        // Validate file extension
        $pathinfo = pathinfo($name);
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

        if($this->allowedExtensions && !in_array(strtolower($ext), array_map("strtolower", $this->allowedExtensions))){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }

        // Save a chunk
        $totalParts = isset($_REQUEST['qqtotalparts']) ? (int)$_REQUEST['qqtotalparts'] : 1;

		$uuid =  $_REQUEST['qquuid'];

        if ($totalParts > 1){
        # chunked upload

            $chunksFolder = $this->chunksFolder;
            $partIndex = (int)$_REQUEST['qqpartindex'];

            if (!is_writable($chunksFolder) && !is_executable($uploadDirectory)){
                return array('error' => "Server error. Chunks directory isn't writable or executable.");
            }

            $targetFolder = $this->chunksFolder.DIRECTORY_SEPARATOR.$uuid;

            if (!file_exists($targetFolder)){
                mkdir($targetFolder, 0777, true);
            }

            $target = $targetFolder.'/'.$partIndex;
            $success = move_uploaded_file($_FILES[$this->inputName]['tmp_name'], $target);

            return array("success" => true, "uuid" => $uuid);

        }
        else {
        # non-chunked upload

            $target = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $uuid, $name));

            if ($target){
                $this->uploadName = basename($target);

                if (!is_dir(dirname($target))){
					mkdir(dirname($target), 0777, true);
                }

                if (move_uploaded_file( $file['tmp_name'], $target )){
                    return array('success'=> true, 'terget' => $target,  "uuid" => $uuid);
                }
            }

            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
    }

    /**
     * Process a delete.
     * @param string $uploadDirectory Target directory.
     * @params string $name Overwrites the name of the file.
     *
     */
    public function handleDelete($uploadDirectory, $name=null)
    {
        if ($this->isInaccessible($uploadDirectory)) {
            return array('error' => "Server error. Uploads directory isn't writable" . ((!$this->isWindows()) ? " or executable." : "."));
        }

        $targetFolder = $uploadDirectory;
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $tokens = explode('/', $url);
        $uuid = $_REQUEST['qquuid'];//$tokens[sizeof($tokens)-1];

        $target = join( DIRECTORY_SEPARATOR, array( $targetFolder, $uuid ) );
        //$target = $targetFolder;

//return array("DIR" => $uuid, 'DIR2' => $targetFolder );

        if (is_dir($target)){
            $this->removeDir($target);
            return array("success" => true, "uuid" => $uuid);
        } else {
            return array("success" => false,
                "error" => "File not found! Unable to delete." . $url,
                "path" => $uuid
            );
        }

    }

    /**
     * Returns a path to use with this upload. Check that the name does not exist,
     * and appends a suffix otherwise.
     * @param string $uploadDirectory Target directory
     * @param string $filename The name of the file to use.
     */
    protected function getUniqueTargetPath($uploadDirectory, $filename)
    {
        // Allow only one process at the time to get a unique file name, otherwise
        // if multiple people would upload a file with the same name at the same time
        // only the latest would be saved.

        if (function_exists('sem_acquire')){
            $lock = sem_get(ftok(__FILE__, 'u'));
            sem_acquire($lock);
        }

        $pathinfo = pathinfo($filename);
        $base = $pathinfo['filename'];
        $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
        $ext = $ext == '' ? $ext : '.' . $ext;

        $unique = $base;
        $suffix = 0;

        // Get unique file name for the file, by appending random suffix.

        while (file_exists($uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext)){
            $suffix += rand(1, 999);
            $unique = $base.'-'.$suffix;
        }

        $result =  $uploadDirectory . DIRECTORY_SEPARATOR . $unique . $ext;

        // Create an empty target file
        if (!touch($result)){
            // Failed
            $result = false;
        }

        if (function_exists('sem_acquire')){
            sem_release($lock);
        }

        return $result;
    }

	//-----------Вывод в загрузчик json картинок из Слайдера Дома
	public function getSlidesUploadsSession($uploadDirectory, $id, $type='Posts', $typeType=null)
	{
		$jsonArrays = array();
		$jsonArray = array();
		$i = 0;

		$slidesrep = $this->em->getRepository('Top10CabinetBundle:Slides');

		$post = $this->em->getRepository('Top10CabinetBundle:'. $type)->find( $id );

		$qbslides = $slidesrep->createQueryBuilder('s');
		$qbslides->addSelect('s')->addOrderBy('s.sort', 'ASC');

		if( $post )
			$qbslides->where('s.' . strtolower($type) . ' = :id')->setParameter( 'id', $id );
		else
			$qbslides->where('s.code = :id')->setParameter( 'id', $id );

		if( $typeType != null )
			$qbslides->andWhere('s.type = :type')->setParameter( 'type', $typeType );
		else
			$qbslides->andWhere('s.type IS NULL');

		$slides = $qbslides->getQuery()->getArrayResult();

		foreach ( $slides as $item ){
			$item['image'] = ltrim( $item['image'], DIRECTORY_SEPARATOR );
			if ( is_file( $item['image'] ) ){
				$imageArr = explode( DIRECTORY_SEPARATOR, $item['image'] );
				//$imageArr = explode( DIRECTORY_SEPARATOR, $item['image'] );

				$jsonArray['name'] = array_pop( $imageArr );
				$jsonArray['uuid'] = $imageArr[count($imageArr)-1];
				$jsonArray['thumbnailUrl'] = join( DIRECTORY_SEPARATOR, array( '', $item['image'] ) );

				$slidesSort = $slidesrep->find($item['id'] );
				if( $slidesSort ){
					$i++;
					$slidesSort->setSort( $i );
					$this->em->persist( $slidesSort );
					$this->em->flush();
					$jsonArray['sort'] = $i;
				}

				$jsonArrays[] = $jsonArray;
			}
		}
/*print'<pre>';
print_r( $jsonArrays );
print'</pre>';*/
		return json_encode( $jsonArrays );
	}





	public function getUploadsSession($uploadDirectory)
	{
		$jsonArrays = array();
		$jsonArray = array();
		$i = 0;

		foreach (scandir($uploadDirectory) as $item)
		{

			if ($item == "." || $item == "..")
                continue;

			$uuidDir = join(DIRECTORY_SEPARATOR, array($uploadDirectory, $item));

            if (is_dir( $uuidDir ) )
			{

				$i++;                

				foreach ( scandir( $uuidDir ) as $file)
				{

					if ($file == "." || $file == "..")
						continue;
//print $file . '<br>';
					if ( is_file( join(DIRECTORY_SEPARATOR, array( $uploadDirectory, $item, $file )) ) ){
						$jsonArray['name'] = $file;
						$jsonArray['uuid'] = $item;
						$jsonArray['thumbnailUrl'] = join(DIRECTORY_SEPARATOR, array('', $uploadDirectory, $item, $file));
						break;
					}
				}

				$jsonArrays[] = $jsonArray;
            }

        }

		//ksort( $jsonArrays );
		//$jsonArrays = array_values( $jsonArrays );
/*print'<pre>';
print_r( $jsonArrays );
print'</pre>';*/
		return json_encode( $jsonArrays );
	}

    /**
     * Deletes all file parts in the chunks folder for files uploaded
     * more than chunksExpireIn seconds ago
     */
    protected function cleanupChunks(){
        foreach (scandir($this->chunksFolder) as $item){
            if ($item == "." || $item == "..")
                continue;

            $path = $this->chunksFolder.DIRECTORY_SEPARATOR.$item;

            if (!is_dir($path))
                continue;

            if (time() - filemtime($path) > $this->chunksExpireIn){
                $this->removeDir($path);
            }
        }
    }

    /**
     * Removes a directory and all files contained inside
     * @param string $dir
     */
    public function removeDir($dir){
		foreach (scandir($dir) as $item){
            if ($item == "." || $item == "..")
                continue;

            if (is_dir($item)){
                $this->removeDir($item);
            } else {
				//chown( join(DIRECTORY_SEPARATOR, array($dir, $item)), 0777 );
				unlink( join(DIRECTORY_SEPARATOR, array($dir, $item)) );
				//rmdir( join(DIRECTORY_SEPARATOR, array($dir, $item)) );
            }

        }
        rmdir($dir);
    }


	 /**
     * Removes a directory and all files contained inside
     * @param string $dir
     */
	public function removeDir2($dir)
	{
		if ( $objs = glob( join( DIRECTORY_SEPARATOR, array($dir, '*')) ) ){
			foreach($objs as $obj) {
				is_dir($obj) ? $this->removeDir2($obj) : unlink($obj);
			}
		}
		if( file_exists( $dir ) )
			rmdir($dir);
	}

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Determines whether a directory can be accessed.
     *
     * is_executable() is not reliable on Windows prior PHP 5.0.0
     *  (http://www.php.net/manual/en/function.is-executable.php)
     * The following tests if the current OS is Windows and if so, merely
     * checks if the folder is writable;
     * otherwise, it checks additionally for executable status (like before).
     *
     * @param string $directory The target directory to test access
     */
    protected function isInaccessible($directory) {
        $isWin = $this->isWindows();
        $folderInaccessible = ($isWin) ? !is_writable($directory) : ( !is_writable($directory) && !is_executable($directory) );
        return $folderInaccessible;
    }

    /**
     * Determines is the OS is Windows or not
     *
     * @return boolean
     */

    protected function isWindows() {
    	$isWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    	return $isWin;
    }

}
