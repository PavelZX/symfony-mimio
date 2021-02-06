<?php

$s3 = new Aws\S3\S3Client([
     'version' => 'latest',
     'region'  => 'us-east-1',
     'endpoint' => 'http://localhost:9000',
     'use_path_style_endpoint' => true,
     'credentials' => [
               'key'    => 'minio-admin',
               'secret' => 'minio-admin',
          ],
]);

// Send a PutObject request and get the result object.
$insert = $s3->putObject([
     'Bucket' => 'test',
     'Key'    => 'testkey',
     'Body'   => 'Hello from MinIO!!'
]);

// Download the contents of the object.
$retrive = $s3->getObject([
     'Bucket' => 'test',
     'Key'    => 'testkey',
     'SaveAs' => 'testkey_local'
]);