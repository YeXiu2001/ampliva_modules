<?php

require '../../config.php';

// Join campaign_data and adgroup_data to get adgroup count in one query
$campaign_data_q = "
    SELECT 
    c.*, 
    COUNT(a.campaign_id) as adgroup_count, 
    CONCAT(SUBSTRING(c.campaign_name, 1, 30), '...') AS short_camp,
    CONCAT(SUBSTRING(c.advertiser_name, 1, 20), '...') AS short_adname
    FROM 
        campaign_data c
    LEFT JOIN 
        adgroup_data a ON c.campaign_id = a.campaign_id
    GROUP BY 
        c.campaign_id;
";
$campaign_data_result = $mysqli->query($campaign_data_q);
// Convert the mysqli_result object to an associative array
$campaign_data = [];
if ($campaign_data_result) {
    while ($row = $campaign_data_result->fetch_assoc()) {
        $campaign_data[] = $row;  // Collect rows in an array
    }
}

$adgroup_q = "
SELECT
adg.*,
COUNT(ad.adgroup_id) as ad_count, 
CONCAT(SUBSTRING(adg.adgroup_name, 1, 30), '...') AS short_adg,
CONCAT(SUBSTRING(adg.advertiser_name, 1, 20), '...') AS short_adname
from adgroup_data adg
LEFT JOIN
	ad_data ad ON adg.adgroup_id = ad.adgroup_id
GROUP BY
	adg.adgroup_id;
";
$adgroup_result = $mysqli->query($adgroup_q);
$adgroup_data = [];
if ($adgroup_result) {
    while ($row = $adgroup_result->fetch_assoc()) {
        $adgroup_data[] = $row;
    }
}


$metrics = [
    'Basic Metric',
    'Video Views',
    'Engagement',
    'Interactive Add-on',
    'Live Views',
    'In-App Event',
    'Page Event',
    'Attribution',
    'Web Conversion',
];

return [
    'campaign_data' => $campaign_data,
    'adgroup_data' => $adgroup_data,
    'metrics' => $metrics,
];
