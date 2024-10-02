<?php
require_once '../../queries/dm_tiktok/data_dmt_index.php';

function yieldContent() {
    global $content;
    echo $content;
}
ob_start();
?>

<style>
    .campaign-name-cell {
        max-width: 0 !important; /* Adjust this value as needed */
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    
    /* Ensure the table respects column widths */
    #test {
        width: 100%;
    }

    div.dataTables_wrapper div.dataTables_filter label {
        text-align: right !important;
    }

    .pagination {
        --bs-pagination-padding-x: 0.5rem;
        --bs-pagination-padding-y: 0.05rem;
    }
</style>

<div class="main-content m-5" style="font-size: 11px;">
    <div class="row">
        <table id="test" class="text-nowrap table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Ad group count</th>
                    <th>Spend</th>
                    <th>Objective Type</th>
                    <th>Advertiser Name</th>
                    <th>KPI Calculation</th>
                    <th>Campaign Metrics</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through campaign data
                foreach ($campaign_data as $cd) {
                ?>
                <tr>
                    <td style="width: 10px" class="campaign-name-cell" data-c-id="<?=$cd['campaign_id']?>">
                        <?= htmlspecialchars($cd['campaign_name']) ?>
                    </td>
                    <td><?=$cd['adgroup_count']?></td>
                    <td><?= htmlspecialchars($cd['spend']) ?></td>
                    <td><?= htmlspecialchars($cd['objective_type']) ?></td>
                    <td class="campaign-name-cell"><?= htmlspecialchars($cd['advertiser_name']) ?></td>
                    <td>TBD</td>
                    <td>Selection Here then Manipulate Table</td>
                </tr>
                <?php
                }
                
                // Free the result sets and close the connection
                $campaign_data->free();
                $mysqli->close();
                ?>
            </tbody>
        </table>
    </div>
</div>


<script>
$(document).ready(function() {
    let table = new DataTable('#test', {
        responsive: true,
        scrollY: 400,

    });
});
</script>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/../../includes/app.php';
?>