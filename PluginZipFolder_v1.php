<?php
/**
 <h1>Zip test</h1>
 */
class PluginZipFolder_v1{
  /**
   * Widget to test zip to folder method.
   * Set params zip_to_folder, zip_folder, zip_filename.
   */
  public static function widget_zip_to_folder($data){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    PluginZipFolder_v1::zip_to_folder($data->get('data'));
    /**
     * Create element.
     */
    $table = wfDocument::createHtmlElementAsObject('table');
    foreach ($data->get('data') as $key => $value) {
      $tr = wfDocument::createHtmlElementAsObject('tr');
      $th = wfDocument::createHtmlElement('th', $key);
      $td = wfDocument::createHtmlElement('td', $value);
      $tr->set('innerHTML', array($th, $td));
      $table->set('innerHTML/', $tr->get());
    }
    /**
     * Render element.
     */
    wfDocument::renderElement(array($table->get()));
  }
  /**
   * Zip folder method.
   * @param type array('zip_folder' => '/Path/To/Folder', 'zip_filename' => '/Path/To/Zipfile/test.zip')
   * @throws Exception
   */
  public static function zip_to_folder($data = array('zip_folder' => '/Path/To/Folder', 'zip_filename' => '/Path/To/Zipfile/test.zip')){
    wfPlugin::includeonce('wf/array');
    $data = new PluginWfArray($data);
    /**
     * Set data.
     */
    $zip_folder = $data->get('zip_folder');
    $zip_filename = $data->get('zip_filename');
    /**
     * Check if folder exists.
     */
    if(!wfFilesystem::fileExist($zip_folder)){
      throw new Exception("PluginTestZip_v1 says ($zip_folder) does not exist for param zip_folder.");
    }
    if(!wfFilesystem::fileExist(dirname($zip_filename))){
      throw new Exception("PluginTestZip_v1 says (".dirname($zip_filename).") does not exist for param zip_filename.");
    }
    /**
     * Init ZipArchive.
     */
    $zip_archive = new ZipArchive();
    $zip_archive->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($zip_folder), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $name => $file)
    {
      if ($file->isDir() == false)
      {
        $real_path = $file->getRealPath();
        $relative_path = substr($real_path, strlen(($zip_folder)) + 1);
        $zip_archive->addFile($real_path, $relative_path);
      }
    }
    $zip_archive->close();
  }
}