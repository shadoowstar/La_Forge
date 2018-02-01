<?php

require __DIR__.'/prod.php';

$app['debug'] = true; //activation du mode debug pour afficher les exeptions capturer
//par silex

Symfony\Component\Debug\Debug::enable();

$app->register(new Silex\Provider\WebProfilerServiceProvider(),array(
    'profiler.cache_dir'=> __DIR__.'/../var/cache/profiler',
    'profiler.mount_prefix'=>'/_profiler'
));

?>
