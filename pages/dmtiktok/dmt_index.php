<?php
function yieldContent() {
    global $content;
    echo $content;
}
// Start output buffering to capture the content
ob_start();
?>
<div class="main-content m-5 border border-primary">
    table mo nalng dito
</div>
<?php
// End buffering and store the captured content in a variable
$content = ob_get_clean();
// Include the layout after the content is prepared
require_once __DIR__ . '/../../includes/app.php';