<?php

require '../../config.php';

// Join campaign_data and adgroup_data to get adgroup count in one query
$campaign_data_q = "
    SELECT c.*, COUNT(a.campaign_id) as adgroup_count 
    FROM campaign_data c
    LEFT JOIN adgroup_data a ON c.campaign_id = a.campaign_id
    GROUP BY c.campaign_id
";
$campaign_data = $mysqli->query($campaign_data_q);

return $campaign_data;
