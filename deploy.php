#!/usr/bin/php -q
<?php
$deploy_dir = 'target';
$versions_dir = 'updates';
$updates_file = "$versions_dir/quipu-update.xml";
$src_dir = 'src';
$prod_name = 'quipu';

$dist_file_pref = 'com_quipu-';
$dist_file_suf = '.zip';

if(!is_dir($versions_dir)){
	mkdir($versions_dir);
}


echo "Delete and recreate $deploy_dir...\n";
echo shell_exec("rm -rf $deploy_dir");
mkdir($deploy_dir);

echo "Copy $src_dir to $deploy_dir...\n";
echo shell_exec("rsync -a --exclude-from 'deploy-excludes.txt' $src_dir/* $deploy_dir/");
$descriptor = "$deploy_dir/$prod_name.xml";
$version = getVersion($descriptor);
echo "Version number detected: $version\n";
$filename = str_replace(' ', '-', $dist_file_pref.$version.$dist_file_suf);
if(is_file("$versions_dir/$filename")){
	die("\nERROR: $versions_dir/$filename exists. Change version number or delete old file.\n");
}
$res = shell_exec("cd $deploy_dir; zip -r ../$versions_dir/$filename ./*;cd ..;");
echo "$res\n";

echo "$filename generated successfully.\n";


echo "Updating updates file...\n";
$xml = file_get_contents($updates_file);
$info = generateUpdateInfo($version,$filename);
$xml = str_replace('</updates>',"\n$info\n</updates>",$xml);
file_put_contents($updates_file,$xml);

echo "DONE :-)\n";


/**
* Adds update info to update xml file
**/
function generateUpdateInfo($version,$filename){
	$xml = "
   <update>
      <name>COM_QUIPU</name>
      <description>Quipu ERP</description>
      <element>com_quipu</element>
      <type>component</type>
      <version>$version</version>
 
      <infourl title=\"Quipu ERP\">https://github.com/NachoBrito/QuipuForJoomla</infourl>
      <downloads>
         <downloadurl type=\"full\" format=\"zip\">http://deploy.local/quipu/updates/$filename</downloadurl>
      </downloads>
      <tags>
         <tag>Alpha</tag>
      </tags>
 
      <maintainer>Nacho Brito</maintainer>
      <maintainerurl>http://www.nachobrito.com</maintainerurl>
      <section>some-section</section>
      <targetplatform name=\"joomla\" version=\"2.5\" />
   </update> 
	";

	return $xml;
}


/**
 * reads product version from descriptor
 */
function getVersion($descriptor){	
	echo "Reading version from file $descriptor\n";
	$file = file_get_contents($descriptor);
	$i = strpos($file,'<version>') + 9;
	$j = strpos($file,'</version>');
	$v = substr($file,$i,($j - $i));
	return $v;
}

