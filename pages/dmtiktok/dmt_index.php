<?php
$data = require_once '../../queries/dm_tiktok/data_dmt_index.php';
$campaign_data = $data['campaign_data'];
$adgroup_data = $data['adgroup_data'];
$metrics = $data['metrics'];
$metrics_options = '';

foreach ($metrics as $index => $metric) {
    $metrics_options .= sprintf(
        '<option value="%d">%s</option>',
        $index,
        htmlspecialchars($metric)
    );
}

function yieldContent() {
    global $content;
    echo $content;
}
ob_start();
?>

<style>
    .scrollable-row {
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
    }

    .scrollable-row span {
        margin-right: 20px; /* Space between columns */
        font-size: 11px; /* Adjust the font size as needed */
    }
    #test {
        width: 100%;
    }

    div.dataTables_wrapper div.dataTables_filter label {
        text-align: right !important;
    }

    .pagination {
        --bs-pagination-padding-x: 0.5rem;
        --bs-pagination-padding-y: 0.05rem;
        padding-top: 3px;
    }
    
    .blurred {
        filter: blur(5px);
        pointer-events: none;
    }

    .main-content {
        font-size: 11px !important;
    }

    .select2-results__option {
        font-size: 12px !important;
        padding: 2px;
        margin: 0 0 5px;
    }

    /*START Styles for expandable rows */
    td.details-control  {
        cursor: pointer;
        width: 20px;
        text-align: center;
    }
    td.adgDetails{
        cursor: pointer;
        width: 20px;
        text-align: center;
    }
    
    td.details-control:before {
        content: '+';
        display: inline-block;
        color: #0275d8;
        font-weight: bold;
    }
    td.adgDetails:before {
        content: '+';
        display: inline-block;
        color: #0275d8;
        font-weight: bold;
    }
    
    tr.shown td.details-control:before {
        content: '-';
    }
    tr.shown td.adgDetails:before {
        content: '-';
    }
    /*END Styles for expandable rows */

    /* Ensure the nested table fits well */
    .child-row-content table {
        width: 100%;
    }

    /* for scrollx so that table will expand */
    .dataTables_scrollHeadInner, 
        .dataTables_scrollBody table {
            width: 100% !important;
    }

    .shortnames{
        cursor: pointer;
    }
</style>

<div class="main-content m-5">
    <div class="row">
        <div class="col-12 mt-5">
            <!-- <h4 class="text-primary">Tiktok Campaigns</h4> -->
        <!-- <div class="col-12 border border-primary">
            <h4 class="text-primary">REFERENCE Campaigns</h4>

        </div> -->
        <table id="test" class="text-nowrap table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th></th>
                    <th>Campaign Name</th>
                    <th>Ad group</th>
                    <th>Spend</th>
                    <th>Objective Type</th>
                    <th>Advertiser Name</th>
                    <th>KPI Calculation</th>
                    <th id="metricHeader">Metric</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaign_data as $cd) { ?>
                <tr>
                    <td class="details-control"></td>
                    <td class="tdcampaign" data-c-id="<?=$cd['campaign_id']?>">
                        <span class="text-primary shortnames"><?=$cd['short_camp']?></span><br>
                        <select class="form-select metrics-select" id="metricstbl" style="font-size: 11px; padding-top:0.1rem; padding-bottom:0.1rem; margin-bottom: 0;">
                            <option value="">Original Column</option>
                            <?= $metrics_options ?>
                        </select>
                    </td>
                    <td data-adg-count="<?=$cd['adgroup_count']?>">
                        <div style="display: flex; justify-content: space-between;">
                            <span><?=$cd['adgroup_count']?></span>
                            <span class="text-primary m-0 p-0"><i style="font-size:1rem;" class='bx bxs-show'></i></span>
                        </div>
                    </td>
                    <td><?= $cd['spend'] ?></td>
                    <td><?= $cd['objective_type'] ?></td>
                    <td><span class="text-primary shortnames"><?= $cd['short_adname'] ?></span></td>
                    <td>TBD</td>
                    <td class="metricsHere">Select Metric to Change</td>
                </tr>
                <?php 
                }
                $mysqli->close();
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
    // Execute immediately, before document ready
    (function() {
        document.body.classList.add('blurred');
    })();
    let allcdata = <?= json_encode($campaign_data) ?>;
    let alladgdata = <?= json_encode($adgroup_data) ?>;
    let table;

    $(document).ready(function() {
        initializeDataTable();

        // Details control event listener
        $('#test tbody').on('click', 'td.details-control', function () {
            let tr = $(this).closest('tr');
            let row = table.row(tr);
            let adgroupcount = tr.find('[data-adg-count]').data('adg-count');

            if (adgroupcount == 0) {
                swal.fire({
                    title: 'No Adgroup Data',
                    text: 'This campaign has no adgroup data',
                    icon: 'info'
                });
                return;
            }
            if (row.child.isShown()) {
                // if row shown, hide it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                let campaignId = tr.find('[data-c-id]').data('c-id');
                let adgroupData = alladgdata.filter(adg => adg.campaign_id == campaignId);
                showAdgroupRow(adgroupData, row, tr);
            }
        });


         // event listener for inline metric select
         $('#test tbody').on('change', '#metricstbl', function() {

            const selectedValue = $(this).val();
            const $row = $(this).closest('tr');
            const $metricsCell = $row.find('.metricsHere');
            const campId = $row.find('.tdcampaign').data('c-id');
            const campdata = allcdata.find(c => c.campaign_id == campId);
            // Clear any existing content in the metricsHere cell
            $metricsCell.empty();
            configInlineMetric(selectedValue, $metricsCell, campId, campdata);
        });

        $('#test').on('click', 'td.adgDetails', function() {
            let tr = $(this).closest('tr');
            let adNestedTable = $(this).closest('table').DataTable();
            let row = adNestedTable.row(tr);
            console.log("ni run ni");
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Pass 'row' and 'tr' to the function to handle ad rows
                showAdRow(row, tr);
            }
        });
    });//DOMContentLoaded  
    
    function configInlineMetric(selectedValue, $metricsCell, campId, campdata) {
        let newContentHtml = '';

        switch (selectedValue) {
            case '0': // Basic Metric

                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.result}</b></span>
                            <span class="text-secondary">Result</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_result}</b></span>
                            <span class="text-secondary">Cost per Result</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Total Cost</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cpc}</b></span>
                            <span class="text-secondary">CPC</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cpm}</b></span>
                            <span class="text-secondary">CPM</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.impressions}</b></span>
                            <span class="text-secondary">Impressions</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.clicks}</b></span>
                            <span class="text-secondary">Clicks</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Clicks (All)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.ctr}</b></span>
                            <span class="text-secondary">CTR</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.reach}</b></span>
                            <span class="text-secondary">Reach</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_1000_reached}</b></span>
                            <span class="text-secondary">Cost per 1,000 Reached</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.frequency}</b></span>
                            <span class="text-secondary">Frequency</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.conversion}</b></span>
                            <span class="text-secondary">Conversions</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_conversion}</b></span>
                            <span class="text-secondary">CPA</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.conversion_rate}</b></span>
                            <span class="text-secondary">CVR</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.real_time_conversion}</b></span>
                            <span class="text-secondary">Real-time Conversions</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.real_time_cost_per_conversion}</b></span>
                            <span class="text-secondary">Real-time CPA</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.real_time_conversion_rate}</b></span>
                            <span class="text-secondary">Real-time CVR</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.skan_conversion}</b></span>
                            <span class="text-secondary">Conversions (SKAN click time)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.skan_cost_per_conversion}</b></span>
                            <span class="text-secondary">CPA (SKAN click time)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.skan_conversion_rate}</b></span>
                            <span class="text-secondary">CVR (SKAN click time)</span>
                        </div>
                    </div>`;
                break;

            case '1': // Video Views
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_play_actions}</b></span>
                            <span class="text-secondary">Video Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_watched_2s}</b></span>
                            <span class="text-secondary">2-Second Video Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_watched_6s}</b></span>
                            <span class="text-secondary">6-Second Video Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">6-Second Views (Focused View)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_views_p100}</b></span>
                            <span class="text-secondary">Video Views at 100%</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_views_p75}</b></span>
                            <span class="text-secondary">Video Views at 75%</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_views_p50}</b></span>
                            <span class="text-secondary">Video Views at 50%</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.video_views_p25}</b></span>
                            <span class="text-secondary">Video Views at 25%</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.average_video_play}</b></span>
                            <span class="text-secondary">Average Watch Time per Video View</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.average_video_play_per_user}</b></span>
                            <span class="text-secondary">Average Watch Time per Person</span>
                        </div>
                    </div>`;

                break;

            case '2': // Engagement
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.engagements}</b></span>
                            <span class="text-secondary">Total Engagement</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.engagement_rate}</b></span>
                            <span class="text-secondary">Engagement Rate</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.follows}</b></span>
                            <span class="text-secondary">Paid Followers</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.likes}</b></span>
                            <span class="text-secondary">Paid Likes</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.comments}</b></span>
                            <span class="text-secondary">Paid Comments</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.shares}</b></span>
                            <span class="text-secondary">Paid Shares</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.profile_visits}</b></span>
                            <span class="text-secondary">Paid Profile Visits</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Paid Profile Visit Rate</span>
                        </div>
                    </div>`;
                break;

            case '3': // Interactive Add On
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.interactive_add_on_impressions}</b></span>
                            <span class="text-secondary">IA Impressions</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.interactive_add_on_destination_clicks}</b></span>
                            <span class="text-secondary">IA Destination Clicks</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.interactive_add_on_activity_clicks}</b></span>
                            <span class="text-secondary">IA Activity Clicks</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.interactive_add_on_option_a_clicks}</b></span>
                            <span class="text-secondary">IA Option A Clicks</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.interactive_add_on_option_b_clicks}</b></span>
                            <span class="text-secondary">IA Option B Clicks</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.countdown_sticker_recall_clicks}</b></span>
                            <span class="text-secondary">IA Recall Clicks</span>
                        </div>
                    </div>`;
                break;

            case '4': // Live Views
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.live_views}</b></span>
                            <span class="text-secondary">LIVE Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.live_unique_views}</b></span>
                            <span class="text-secondary">LIVE Unique Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.live_effective_views}</b></span>
                            <span class="text-secondary">Effective LIVE Views</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.live_product_clicks}</b></span>
                            <span class="text-secondary">LIVE Product Clicks</span>
                        </div>
                    </div>`;
                break;

            case '5': // In App Event
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.app_install}</b></span>
                            <span class="text-secondary">Real-time App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.registration}</b></span>
                            <span class="text-secondary">Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.purchase}</b></span>
                            <span class="text-secondary">Purchase</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.app_event_add_to_cart}</b></span>
                            <span class="text-secondary">Add to Cart</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.view_content}</b></span>
                            <span class="text-secondary">View Content</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.next_day_open}</b></span>
                            <span class="text-secondary">Day 1 Retention</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Unique Day 7 Retention</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.add_payment_info}</b></span>
                            <span class="text-secondary">Add Payment Info</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.add_to_wishlist}</b></span>
                            <span class="text-secondary">Add to Wishlist</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.launch_app}</b></span>
                            <span class="text-secondary">Launch App</span>
                        </div>
                    </div>`;
                break;

            case '6': // Page Event
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.complete_payment_roas}</b></span>
                            <span class="text-secondary">Complete Payment ROAS</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.complete_payment}</b></span>
                            <span class="text-secondary">Complete Payment</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.total_pageview}</b></span>
                            <span class="text-secondary">Page View</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.button_click}</b></span>
                            <span class="text-secondary">Click Button</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.online_consult}</b></span>
                            <span class="text-secondary">Contact</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.user_registration}</b></span>
                            <span class="text-secondary">Complete Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.page_content_view_events}</b></span>
                            <span class="text-secondary">View Content</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.on_web_order}</b></span>
                            <span class="text-secondary">Add to Cart</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.web_event_add_to_cart}</b></span>
                            <span class="text-secondary">Place an Order</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.initiate_checkout}</b></span>
                            <span class="text-secondary">Initiate Checkout</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Add Payment Info</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.page_event_search}</b></span>
                            <span class="text-secondary">Search</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.form}</b></span>
                            <span class="text-secondary">Submit Form</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.download_start}</b></span>
                            <span class="text-secondary">Download</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.on_web_add_to_wishlist}</b></span>
                            <span class="text-secondary">Add to Wishlist</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.on_web_subscribe}</b></span>
                            <span class="text-secondary">Subscribe</span>
                        </div>
                    </div>`;
                break;

            case '7': // Attribution
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.vta_conversion}</b></span>
                            <span class="text-secondary">VTA Conversion</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_vta_conversion}</b></span>
                            <span class="text-secondary">Cost per VTA Conversion</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.vta_app_install}</b></span>
                            <span class="text-secondary">VTA App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_vta_app_install}</b></span>
                            <span class="text-secondary">Cost per VTA App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.vta_registration}</b></span>
                            <span class="text-secondary">VTA Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_vta_registration}</b></span>
                            <span class="text-secondary">Cost per VTA Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.vta_purchase}</b></span>
                            <span class="text-secondary">VTA Purchase</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_vta_purchase}</b></span>
                            <span class="text-secondary">Cost per VTA Purchase</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cta_conversion}</b></span>
                            <span class="text-secondary">CTA Conversion</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_cta_conversion}</b></span>
                            <span class="text-secondary">Cost per CTA Conversion</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cta_app_install}</b></span>
                            <span class="text-secondary">CTA App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_cta_app_install}</b></span>
                            <span class="text-secondary">Cost per CTA App Install</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cta_registration}</b></span>
                            <span class="text-secondary">CTA Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_cta_registration}</b></span>
                            <span class="text-secondary">Cost per CTA Registration</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cta_purchase}</b></span>
                            <span class="text-secondary">CTA Purchase</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.cost_per_cta_purchase}</b></span>
                            <span class="text-secondary">Cost per CTA Purchase</span>
                        </div>
                    </div>`;
                break;

            case '8': // Web Conversion
                newContentHtml = `
                    <div class="scrollable-row d-flex">
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.complete_payment_roas}</b></span>
                            <span class="text-secondary">Complete Payment ROAS (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.complete_payment}</b></span>
                            <span class="text-secondary">Complete Payment (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.initiate_checkout}</b></span>
                            <span class="text-secondary">Initiate Checkout (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Product Details Page View (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.on_web_add_to_wishlist}</b></span>
                            <span class="text-secondary">Add to Wishlist (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">Add Billing (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.web_event_add_to_cart}</b></span>
                            <span class="text-secondary">Add to Cart (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.form}</b></span>
                            <span class="text-secondary">Form Submission (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">App Store Click (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.total_pageview}</b></span>
                            <span class="text-secondary">Page Views (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>TBD!</b></span>
                            <span class="text-secondary">CTA Button Clicks (Onsite)</span>
                        </div>
                        <div class="col d-flex flex-column align-items-center">
                            <span><b>${campdata.live_product_clicks}</b></span>
                            <span class="text-secondary">Product Clicks (Onsite)</span>
                        </div>
                    </div>`;
                break;

            default: // Original or any other value
                newContentHtml = 'Select Metric to Change';
                break;
        }

        // Update the metricsHere cell with the new content
        $metricsCell.html(newContentHtml);

        // If you have any specific styling or functionality for the new content,
        // you can initialize it here. For example, if you have tooltips or other plugins.

        // Optionally, adjust the DataTable layout if necessary
        if ($.fn.dataTable.isDataTable('#test')) {
            const table = $('#test').DataTable();
            table.columns.adjust().draw();
        }
    }
    
    function showAdgroupRow(adgroupData, row, tr) {
        let nestedTable = `
                    <table class="text-nowrap table table-bordered table-striped adgroupTbl align-middle" style="width:100%;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Ad Group Name</th>
                                <th>Ad Count</th>
                                <th>Impressions of the Ad Group</th>
                                <th>CTR of the Ad Group</th>
                                <th>CPC</th>
                                <th>CPM</th>
                                <th>Clicks</th>
                                <th>Reach</th>
                                <th>Ad Group Metrics</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                // Loop through adgroupData and append rows
                adgroupData.forEach(adg => {
                    nestedTable += `
                        <tr>
                            <td class="adgDetails"></td>
                            <td><span class="text-primary shortnames">${adg.short_adg}</span>
                            <select class="form-select metrics-select" id="" style="font-size: 11px; padding-top:0.1rem; padding-bottom:0.1rem; margin-bottom: 0;">
                                <option value="">original Column</option>
                                <?= $metrics_options ?>
                            </select>
                            </td>
                            <td>${adg.ad_count}</td>
                            <td>${adg.impressions}</td>
                            <td>${adg.ctr}</td>
                            <td>${adg.cpc}</td>
                            <td>${adg.cpm}</td>
                            <td>${adg.clicks}</td>
                            <td>${adg.reach}</td>
                            <td>Show Metrics</td>
                        </tr>
                    `;
                });

                // Close the table
                nestedTable += `
                        </tbody>
                    </table>
                `;

                // Show the nested table
                row.child(nestedTable).show();
                tr.addClass('shown');

                nestedTable = tr.next('tr').find('table.adgroupTbl');
                nestedTable.DataTable();
    }

    function showAdRow(row, tr){
        // declare data from the query

        // loop
        let adNestedTable = `
                    <table class="table table-bordered table-striped adTbl" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Ad Name</th>
                                <th>Result of the Ads</th>
                                <th>Impressions of the ad</th>
                                <th>CTR</th>
                                <th>CPC</th>
                                <th>CPM</th>
                                <th>Clicks</th>
                                <th>Cost per Result</th>
                                <th>Reach</th>
                                <th>Frequency</th>
                                <th>Billed Cost</th>
                                <th>Ad Metrics</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <tr>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>TBD</td>
                            </tr>
                        </tbody>
                        

                `;

                // Show the nested ad table
                row.child(adNestedTable).show();
                tr.addClass('shown');

                // Initialize DataTable on the nested ad table
                // adNestedTable.DataTable();
    }
    
    function initializeDataTable() {
        if ($.fn.DataTable.isDataTable('#test')) {
            $('#test').DataTable().destroy();
        }
        
        table = new DataTable('#test', {
            scrollY: 400,
            scrollX: true,
            pageLength: 25,
            fixedColumns: {
                leftColumns: 2
            },
            initComplete: function() {
                $('body').removeClass('blurred');
            }
        });
        //hideMetricCols();
    }
</script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../includes/app.php';
?>