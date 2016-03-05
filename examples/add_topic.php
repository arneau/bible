<?php

require_once '../vendor/autoload.php';
require_once '../generated-conf/config.php';
require_once '../app/functions/functions.php';

# Add topics
$topic_1_id = addTopic('Events');
$topic_2_id = addTopic('Places');
$topic_3_id = addTopic('Sodom and Gomorrah');

# Add parents
addTopicParent($topic_3_id, $topic_1_id);
addTopicParent($topic_3_id, $topic_2_id);

# Get topic 1 children
$topic_1_children_data = getTopicChildrenData($topic_1_id);
var_dump($topic_1_children_data);

# Add links
addTopicsLink($topic_1_id, $topic_2_id, 1);
addTopicsLink($topic_1_id, $topic_3_id, 3);

# Get topic 1 links
$topic_1_links_data = getTopicLinksData($topic_1_id);
var_dump($topic_1_links_data);