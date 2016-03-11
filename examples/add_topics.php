<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topics
$topic_1_id = addTopic('Events', true);
$topic_2_id = addTopic('Places', true);
$topic_3_id = addTopic('Sodom and Gomorrah');

# Add parents
addTopicParent($topic_3_id, $topic_1_id);
addTopicParent($topic_3_id, $topic_2_id);

# Add links
addTopicsLink($topic_1_id, $topic_2_id, 1);
addTopicsLink($topic_1_id, $topic_3_id, 3);