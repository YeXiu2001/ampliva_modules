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

    /* Styles for expandable rows */
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

    /* Ensure the nested table fits well */
    .child-row-content table {
        width: 100%;
    }

    /* for scrollx so that table will expand */
    .dataTables_scrollHeadInner, 
        .dataTables_scrollBody table {
            width: 100% !important;
    }
</style>

<div class="main-content m-5">
    <div class="row">
        <div class="col-12 mt-5">
            <!-- <h4 class="text-primary">Tiktok Campaigns</h4> -->
            <!-- <select class="form-select" id="metrics-select">
                            <option value="">original Column</option>
                            <?= $metrics_options ?>
                        </select> -->
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
                    <!-- Basic Metric index 7 - 27 -->
                    <th>Result</th>
                    <th>Cost per Result</th>
                    <th>Total Cost</th>
                    <th>CPC</th>
                    <th>CPM</th>
                    <th>Impressions</th>
                    <th>Clicks</th>
                    <th>Clicks (All)</th>
                    <th>CTR</th>
                    <th>Reach</th>
                    <th>Cost per 1,000 Reached</th>
                    <th>Frequency</th>
                    <th>Conversions</th>
                    <th>CPA</th>
                    <th>CVR</th>
                    <th>Real-time Conversions</th>
                    <th>Real-time CPA</th>
                    <th>Real-time CVR</th>
                    <th>Conversions (SKAN click time)</th>
                    <th>CPA (SKAN click time)</th>
                    <th>CVR (SKAN click time)</th>

                    <!-- Video Views index 28 - 37 -->
                    <th>Video Views</th>
                    <th>2-Second Video Views</th>
                    <th>6-Second Video Views</th>
                    <th>6-Second Views (Focused View)</th>
                    <th>Video Views at 100%</th>
                    <th>Video Views at 75%</th>
                    <th>Video Views at 50%</th>
                    <th>Video Views at 25%</th>
                    <th>Average Watch Time per Video View</th>
                    <th>Average Watch Time per Person</th>

                    <!-- Engagement index 38 - 45 -->
                     <!-- <th>Total Engagement</th>
                     <th>Engagement Rate</th>
                     <th>Paid Followers</th>
                     <th>Paid Likes</th>
                     <th>Paid Comments</th>
                     <th>Paid Shares</th>
                     <th>Paid Profile Visits</th>
                     <th>Paid Profile Visit Rate</th> -->

                     <!-- Interactive Add On index 46 - 51 -->
                      <!-- <th>IA Impressions</th>
                      <th>IA Destination Clicks</th>
                      <th>IA Activity Clicks</th>
                      <th>IA Option A Clicks</th>
                      <th>IA Option B Clicks</th>
                      <th>IA Recall Clicks</th> -->

                      <!-- Live Views index 52 - 55 -->
                    <!-- <th>LIVE Views</th>
                    <th>LIVE Unique Views</th>
                    <th>Effective LIVE Views</th>
                    <th>LIVE Product Clicks</th> -->

                    <!-- In App Event index 56 - 66 -->
                    <!-- <th>Real-time App Install</th>
                    <th>App Install</th>
                    <th>Registration</th>
                    <th>Purchase</th>
                    <th>Add to Cart</th>
                    <th>View Content</th>
                    <th>Day 1 retention</th>
                    <th>Unique Day 7 Retention</th>
                    <th>Add Payment Info</th>
                    <th>Add to Wishlist</th>
                    <th>Launch App</th> -->

                    <!-- Page Event index 67 - 82 -->
                    <!-- <th>Complete Payment ROAS</th>
                    <th>Complete Payment</th>
                    <th>Page View</th>
                    <th>Click Button</th>
                    <th>Contact</th>
                    <th>Complete Registration</th>
                    <th>View Content</th>
                    <th>Add to Cart</th>
                    <th>Place an Order</th>
                    <th>Initiate Checkout</th>
                    <th>Add Payment Info</th>
                    <th>Search</th>
                    <th>Submit Form</th>
                    <th>Download</th>
                    <th>Add to Wishlist</th>
                    <th>Subscribe</th> -->

                    <!-- Attribution index 83-98 -->
                    <!-- <th>VTA Conversion</th>
                    <th>Cost per VTA Conversion</th>
                    <th>VTA App Install</th>
                    <th>Cost per VTA App Install</th>
                    <th>VTA Registration</th>
                    <th>Cost per VTA Registration</th>
                    <th>VTA Purchase</th>
                    <th>Cost per VTA Purchase</th>
                    <th>CTA Conversion</th>
                    <th>Cost per CTA Conversion</th>
                    <th>CTA App Install</th>
                    <th>Cost CTA App Install</th>
                    <th>CTA Registration</th>
                    <th>Cost per CTA Registration</th>
                    <th>CTA Purchase</th>
                    <th>Cost per CTA Purchase</th> -->

                    <!-- Web Conversion index 99 - 110  -->
                    <!-- <th>Complete Payment ROAS (Onsite)</th>
                    <th>Complete Payment (Onsite)</th>
                    <th>Initiate Checkout (Onsite)</th>
                    <th>Product Details Page View (Onsite)</th>
                    <th>Add to Wishlist (Onsite)</th>
                    <th>Add Billing (Onsite)</th>
                    <th>Add to Cart (Onsite)</th>
                    <th>Form Submission (Onsite)</th>
                    <th>App Store Click (Onsite)</th>
                    <th>Page Views (Onsite)</th>
                    <th>CTA Button Clicks (Onsite)</th>
                    <th>Product Clicks (Onsite)</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaign_data as $cd) { ?>
                <tr>
                    <td class="details-control"></td>
                    <td class="text-primary" data-c-id="<?=$cd['campaign_id']?>">
                        <?=$cd['short_camp']?><br>
                        <select class="form-select metrics-select" id="metricstbl" style="font-size: 11px; padding-top:0.1rem; padding-bottom:0.1rem">
                            <option value="">original Column</option>
                            <?= $metrics_options ?>
                        </select>
                    </td>
                    <td>
                        <div style="display: flex; justify-content: space-between;">
                            <span><?=$cd['adgroup_count']?></span>
                            <span class="text-primary m-0 p-0"><i style="font-size:1rem;" class='bx bxs-show'></i></span>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($cd['spend']) ?></td>
                    <td><?= htmlspecialchars($cd['objective_type']) ?></td>
                    <td class="text-primary"><?= htmlspecialchars($cd['short_adname']) ?></td>
                    <td>TBD</td>
                    <td class="metricsHere">Select Metric to Change</td>
                    <!-- basic metric -->
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
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>
                    <td>TBD</td>

                    <!-- Video Views -->
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
        //END details control event listener

         // Attach event listener for metric select dropdowns
         $('#test tbody').on('change', '#metricstbl', function() {
            const selectedValue = $(this).val();
            const $row = $(this).closest('tr');
            const $metricsCell = $row.find('.metricsHere');

            // Clear any existing content in the metricsHere cell
            $metricsCell.empty();
            configInlineMetric(selectedValue, $metricsCell);
        });
    });//DOMContentLoaded  
    
    function configInlineMetric(selectedValue, $metricsCell) {
        let newContentHtml = '';

        switch (selectedValue) {
            case '0': // Basic Metric
                newContentHtml = `
                    <div class="scrollable-row">
                        <span class="text-secondary">Result</span>
                        <span>Cost per Result</span>
                        <span>Total Cost</span>
                        <span>CPC</span>
                        <span>CPM</span>
                        <span>Impressions</span>
                        <span>Clicks</span>
                        <span>Clicks (All)</span>
                        <span>CTR</span>
                        <span>Reach</span>
                        <span>Cost per 1,000 Reached</span>
                        <span>Frequency</span>
                        <span>Conversions</span>
                        <span>CPA</span>
                        <span>CVR</span>
                        <span>Real-time Conversions</span>
                        <span>Real-time CPA</span>
                        <span>Real-time CVR</span>
                        <span>Conversions (SKAN click time)</span>
                        <span>CPA (SKAN click time)</span>
                        <span>CVR (SKAN click time)</span>
                    </div>`;
                break;

            case '1': // Video Views
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>Video Views</span>
                        <span>2-Second Video Views</span>
                        <span>6-Second Video Views</span>
                        <span>6-Second Views (Focused View)</span>
                        <span>Video Views at 100%</span>
                        <span>Video Views at 75%</span>
                        <span>Video Views at 50%</span>
                        <span>Video Views at 25%</span>
                        <span>Average Watch Time per Video View</span>
                        <span>Average Watch Time per Person</span>
                    </div>`;
                break;

            case '2': // Engagement
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>Total Engagement</span>
                        <span>Engagement Rate</span>
                        <span>Paid Followers</span>
                        <span>Paid Likes</span>
                        <span>Paid Comments</span>
                        <span>Paid Shares</span>
                        <span>Paid Profile Visits</span>
                        <span>Paid Profile Visit Rate</span>
                    </div>`;
                break;

            case '3': // Interactive Add On
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>IA Impressions</span>
                        <span>IA Destination Clicks</span>
                        <span>IA Activity Clicks</span>
                        <span>IA Option A Clicks</span>
                        <span>IA Option B Clicks</span>
                        <span>IA Recall Clicks</span>
                    </div>`;
                break;

            case '4': // Live Views
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>LIVE Views</span>
                        <span>LIVE Unique Views</span>
                        <span>Effective LIVE Views</span>
                        <span>LIVE Product Clicks</span>
                    </div>`;
                break;

            case '5': // In App Event
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>Real-time App Install</span>
                        <span>App Install</span>
                        <span>Registration</span>
                        <span>Purchase</span>
                        <span>Add to Cart</span>
                        <span>View Content</span>
                        <span>Day 1 retention</span>
                        <span>Unique Day 7 Retention</span>
                        <span>Add Payment Info</span>
                        <span>Add to Wishlist</span>
                        <span>Launch App</span>
                    </div>`;
                break;

            case '6': // Page Event
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>Complete Payment ROAS</span>
                        <span>Complete Payment</span>
                        <span>Page View</span>
                        <span>Click Button</span>
                        <span>Contact</span>
                        <span>Complete Registration</span>
                        <span>View Content</span>
                        <span>Add to Cart</span>
                        <span>Place an Order</span>
                        <span>Initiate Checkout</span>
                        <span>Add Payment Info</span>
                        <span>Search</span>
                        <span>Submit Form</span>
                        <span>Download</span>
                        <span>Add to Wishlist</span>
                        <span>Subscribe</span>
                    </div>`;
                break;

            case '7': // Attribution
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>VTA Conversion</span>
                        <span>Cost per VTA Conversion</span>
                        <span>VTA App Install</span>
                        <span>Cost per VTA App Install</span>
                        <span>VTA Registration</span>
                        <span>Cost per VTA Registration</span>
                        <span>VTA Purchase</span>
                        <span>Cost per VTA Purchase</span>
                        <span>CTA Conversion</span>
                        <span>Cost per CTA Conversion</span>
                        <span>CTA App Install</span>
                        <span>Cost CTA App Install</span>
                        <span>CTA Registration</span>
                        <span>Cost per CTA Registration</span>
                        <span>CTA Purchase</span>
                        <span>Cost per CTA Purchase</span>
                    </div>`;
                break;

            case '8': // Web Conversion
                newContentHtml = `
                    <div class="scrollable-row">
                        <span>Complete Payment ROAS (Onsite)</span>
                        <span>Complete Payment (Onsite)</span>
                        <span>Initiate Checkout (Onsite)</span>
                        <span>Product Details Page View (Onsite)</span>
                        <span>Add to Wishlist (Onsite)</span>
                        <span>Add Billing (Onsite)</span>
                        <span>Add to Cart (Onsite)</span>
                        <span>Form Submission (Onsite)</span>
                        <span>App Store Click (Onsite)</span>
                        <span>Page Views (Onsite)</span>
                        <span>CTA Button Clicks (Onsite)</span>
                        <span>Product Clicks (Onsite)</span>
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
                    <table class="table table-bordered table-striped" style="width:100%;">
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
                            <td>${adg.adgroup_name}</td>
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
        hideMetricCols();
    }

    // hideMetricCols is for initialization(only shows campaign initial data)
    function hideMetricCols() {
        let totalColumns = table.columns().count(); // Get total number of columns

        // Create an array of indexes from 7 to the last column
        let columnsToHide = Array.from({length: totalColumns - 8}, (v, i) => i + 8);

        // Hide the selected columns
        table.columns(columnsToHide).visible(false);
    }

    // START show metric columns for whole table
    function showBasicMetric(){
        let columnsToShow = Array.from({length: 21}, (v, i) => i + 8);
        table.columns(columnsToShow).visible(true);
        hideInlineMetrics();
    }
    //Prio change28 + 1
    function showVideMetrics(){
        let columnsToShow = Array.from({length: 10}, (v, i) => i + 29);
        table.columns(columnsToShow).visible(true);
    }

    function showEngagement(){
        let columnsToShow = Array.from({length: 8}, (v, i) => i + 39);
        table.columns(columnsToShow).visible(true);
    }

    function showIAMetrics(){
        let columnsToShow = Array.from({length: 6}, (v, i) => i + 47);
        table.columns(columnsToShow).visible(true);
    }

    function showLiveViews(){
        let columnsToShow = Array.from({length: 4}, (v, i) => i + 53);
        table.columns(columnsToShow).visible(true);
    }

    function showInAppEvent(){
        let columnsToShow = Array.from({length: 11}, (v, i) => i + 56);
        table.columns(columnsToShow).visible(true);
    }

    function showPageEvent(){
        let columnsToShow = Array.from({length: 16}, (v, i) => i + 68);
        table.columns(columnsToShow).visible(true);
    }

    function showAttribution(){
        let columnsToShow = Array.from({length: 16}, (v, i) => i + 84);
        table.columns(columnsToShow).visible(true);
    }

    function showWebConversion(){
        let columnsToShow = Array.from({length: 12}, (v, i) => i + 100);
        table.columns(columnsToShow).visible(true);
    }
    // END show metric columns for whole table

    // hide metrics column when metric for whole table is triggered
    function hideInlineMetrics(){
        let columnToHide = 7;
        table.column(columnToHide).visible(false);
    }
</script>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../includes/app.php';
?>